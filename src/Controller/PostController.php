<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{id}/like", name="post_like_ajax")
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
                'post' => $realPost,
                'user' => $user
            ]);
            $notification->setIsRead(false);
            $objectManager->persist($notification);

            $objectManager->persist($entry);
            $objectManager->flush();
            return new JsonResponse(['favorite' => true]);
        }
        else
        {
            foreach($favorite as $f)
                $objectManager->remove($f);

            $objectManager->flush();
            return new JsonResponse(['favorite' => false]);
        }

    }

    /**
     * @Route("/post/{id}/repost", name="post_repost_ajax")
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
}
