<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\Post;
use App\Repository\FavoriteRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    public function repost() { }
    public function delete() { }
}
