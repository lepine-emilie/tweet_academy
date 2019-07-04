<?php

namespace App\Controller;

use App\Repository\FavoriteRepository;
use App\Repository\FollowerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/{username}", name="profile_view")
     */
    public function view(string $username, UserRepository $repository)
    {
        $user = $repository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{username}/following", name="profile_following")
     */
    public function following(string $username, UserRepository $userRepository, FollowerRepository $repository)
    {
        $user = $userRepository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/following.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{username}/followers", name="profile_followers")
     */
    public function followers(string $username, UserRepository $userRepository, FollowerRepository $repository)
    {
        $user = $userRepository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/followers.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{username}/likes", name="profile_favorites")
     */
    public function favorites(string $username, UserRepository $userRepository, FavoriteRepository $repository)
    {
        $user = $userRepository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/favorites.html.twig', [
            'user' => $user
        ]);
    }
}
