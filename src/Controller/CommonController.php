<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\FavoriteRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommonController extends AbstractController
{
    public function miniProfile()
    {
        return $this->render('common/mini_profile.html.twig', [
            'post_count' => 69,
            'followers_count' => 1337
        ]);
    }
    public function trends()
    {
        return $this->render('common/trends.html.twig');
    }

    public function footer()
    {
        return $this->render('common/notafooter.html.twig');
    }

    public function postCard(Post $post, PostRepository $postRepository, FavoriteRepository $favoriteRepository)
    {
        $repost_count = $postRepository->count([
            'source_post' => $post
        ]);

        $favorite_count = $favoriteRepository->count([
            'post' => $post
        ]);

        return $this->render('common/post_card_impl.html.twig', [
            'post' => $post,
            'repost_count' => $repost_count,
            'favorite_count' => $favorite_count
        ]);
    }
}
