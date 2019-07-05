<?php

namespace App\Controller;

use App\Entity\Favorite;
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
        $favorite = $favoriteRepository->findBy([
            'user' => $this->getUser(),
            'post' => $post
        ]);

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (empty($favorite))
        {
            $entry = new Favorite();
            $entry->setPost($post);
            $entry->setUser($user);
            $user->addFavorite($entry);

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
    public function repost(Post $post, ObjectManager $objectManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        $newPost = new Post();
        $newPost->setContent('');
        $newPost->setSubmitTime(new \DateTime);
        $newPost->setSourcePost($post);
        $newPost->setSender($user);

        $user->addPost($newPost);
        $objectManager->persist($newPost);
        $objectManager->flush();
        return new JsonResponse(['success' => true]);
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
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }
}
