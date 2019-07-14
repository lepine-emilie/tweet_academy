<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Hashtag;
use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\User;
use App\Form\UserPostType;
use App\Repository\FavoriteRepository;
use App\Repository\HashtagRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{id}/like", name="post_like_ajax")
     * @IsGranted("ROLE_USER", statusCode=403)
     */
    public function like(Post $post, FavoriteRepository $favoriteRepository, ObjectManager $objectManager)
    {
        $realPost = $post->getSourcePost() ?? $post;
        $favorite = $favoriteRepository->findBy([
            'user' => $this->getUser(),
            'post' => $realPost
        ]);

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (empty($favorite))
        {
            $entry = new Favorite();
            $entry->setPost($realPost);
            $entry->setUser($user);
            $user->addFavorite($entry);

            $notification = new Notification();
            $notification->setUser($realPost->getSender());
            $notification->setNotificationType(Notification::TYPE_LIKE);
            $notification->setNotificationData([
                'post_id' => $realPost->getId(),
                'user_id' => $user->getId()
            ]);
            $notification->setIsRead(false);

            $objectManager->persist($notification);
            $objectManager->persist($entry);
            $objectManager->flush();
            return new JsonResponse(['favorite' => true]);
        }
        else
        {
            foreach ($favorite as $f)
                $objectManager->remove($f);

            $objectManager->flush();
            return new JsonResponse(['favorite' => false]);
        }
    }

    /**
     * @Route("/post/{id}/repost", name="post_repost_ajax")
     * @IsGranted("ROLE_USER", statusCode=403)
     */
    public function repost(Post $post, PostRepository $postRepository, ObjectManager $objectManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        // TODO: delete already reposted
        $existing = $postRepository->findOneBy([
            'sender' => $user,
            'source_post' => $post->getSourcePost() ?? $post
        ]);

        if ($existing !== null)
        {
            $objectManager->remove($existing);
            $objectManager->flush();
            return new JsonResponse([
                'success' => true,
                'reposted' => false
            ]);
        }

        $newPost = new Post();
        $newPost->setContent('');
        $newPost->setSubmitTime(new \DateTime);
        $newPost->setSourcePost($post->getSourcePost() ?? $post);
        $newPost->setSender($user);
        $user->addPost($newPost);

        $notification = new Notification();
        $notification->setUser($newPost->getSourcePost()->getSender());
        $notification->setNotificationType(Notification::TYPE_REPOST);
        $notification->setNotificationData([
            'user_id' => $user->getId(),
            'post_id' => $newPost->getSourcePost()->getId()
        ]);
        $notification->setIsRead(false);

        $objectManager->persist($notification);
        $objectManager->persist($newPost);
        $objectManager->flush();

        $template = $this->renderView('common/post_card.html.twig', [
            'post' => $newPost
        ]);

        return new JsonResponse([
            'success' => true,
            'reposted' => true,
            'htmlTemplate' => $template
        ]);
    }

    /**
     * @Route("/post/{id}/delete", name="post_delete_ajax")
     * @IsGranted("ROLE_USER", statusCode=403)
     */
    public function delete(Post $post, ObjectManager $objectManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($post->getSender()->getId() === $user->getId())
        {
            $objectManager->remove($post);
            $objectManager->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

    /**
     * @Route("/post/{id}/", name="view_post")
     */
    public function view_post(Post $post, Request $request)
    {
        $reply = new Post();
        $form = $this->createForm(UserPostType::class, $reply);
        $form->handleRequest($request);

        return $this->render("/post/view.html.twig", [
            'post' => $post,
            'formReply' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/submit", name="submit_post")
     */
    public function submit(Request $request,
                           PostRepository $repository,
                           HashtagRepository $hashtagRepository,
                           UserRepository $userRepository, ObjectManager $em)
    {
        $post = new Post();
        $form = $this->createForm(UserPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            if (!$form->isValid())
                return new JsonResponse([
                    "success" => false,
                    'errors' => $form->getErrors()
                ]);

            $post->setSender($this->getUser());
            $post->setSubmitTime(new \DateTime());

            $parent_post_id = $form->get('parent_post_id')->getData();
            if (false === empty($parent_post_id))
            {
                $parent_post = $repository->find($parent_post_id);
                if ($parent_post !== null)
                    $post->setParentPost($parent_post);
            }

            $em->persist($post);
            $em->flush();

            if ($form['media_url']->getData() != null)
            {
                $file = $form['media_url']->getData();
                $filename = md5(random_bytes(20)) .
                    "." . pathinfo($file->getClientOriginalName(),
                        PATHINFO_EXTENSION);
                $file->move($this->getParameter('PostUploads_directory'),
                    $filename);
                $post->setMediaUrl($filename);
            }
            if (preg_match('/#([A-Za-z0-9])\w+/',
                $form['content']->getData()))
            {
                $matches = [];
                preg_match_all('/#([A-Za-z0-9])\w+/',
                    $form['content']->getData(), $matches);
                $matches[0] = array_unique($matches[0]);
                foreach ($matches[0] as $key => $valTag)
                {
                    if ($hashtagRepository->findOneBy(['name' => $valTag]) !== null)
                    {
                        $hashtag = $hashtagRepository->findOneBy(['name' => $valTag]);
                        $hashtag->setUseCount($hashtag->getUseCount() + 1);
                        $hashtag->addPost($post);
                    }
                    else
                    {
                        $hashtag = new Hashtag();
                        $hashtag->setName($valTag);
                        $hashtag->setUseCount(1);
                        $hashtag->addPost($post);
                        $em->persist($hashtag);
                    }
                }
            }
            if (preg_match('/@([A-Za-z0-9])\w+/',
                $form['content']->getData()))
            {
                $matches = [];
                preg_match_all('/@([A-Za-z0-9])\w+/',
                    $form['content']->getData(), $matches);
                $matches[0] = array_unique($matches[0]);
                foreach ($matches[0] as $key => $userTag)
                {
                    $userTagged = $userRepository->findOneBy([
                        'username' => substr($userTag, 1)]);
                    if ($userTagged === null)
                        continue;
                    $notif = new Notification();
                    $notif->setUser($userTagged);
                    $notif->setIsRead(0);
                    $notif->setNotificationData([
                        'user_id' => $this->getUser()->getId(),
                        'post_id' => $post->getId()
                    ]);
                    $notif->setNotificationType(Notification::TYPE_MENTION);
                    $em->persist($notif);
                }
            }

            $em->flush();
            $view = $this->renderView("common/post_card.html.twig", ['post' => $post]);
            return new JsonResponse(["success" => true, 'htmlTemplate' => $view]);
        }

        return new JsonResponse(["success" => false]);
    }

    /**
     * @Route("/completionSuggest", name="completion")
     */
    public function autoCompletion(UserRepository $repository)
    {
        $results = $repository->getCompletionInt(substr($_POST['entry'], 1));
        if (isset($results[0]))
        {
            $templates = [];
            foreach ($results as $key => $user)
            {
                $usr = $repository->findOneBy([
                    'username' => $user['username']
                ]);
                $templates[] = $this->
                renderView('common/mini_user_card.html.twig', [
                    'user' => $usr
                ]);
            }
            return new JsonResponse([
                'success' => true,
                'htmlTemplate' => $templates,
            ]);
        }
        else
        {
            return new JsonResponse([
                'success' => false
            ]);
        }
    }
}