<?php

namespace App\Controller;

use App\Repository\FollowerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/{username}", name="profile_view")
     */
    public function index(string $username, UserRepository $repository)
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
    public function following(string $username, FollowerRepository $repository)
    {
        $user = $repository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/following.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{username}/followers", name="profile_followers")
     */
    public function followers(string $username, FollowerRepository $repository)
    {
        $user = $repository->findOneBy(['username' => $username]);

        if ($user === null)
            throw $this->createNotFoundException('The user does not exist');

        return $this->render('profile/followers.html.twig', [
            'user' => $user
        ]);
    }
}
