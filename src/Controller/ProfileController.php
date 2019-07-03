<?php

namespace App\Controller;

use App\Repository\FollowerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/{username}", name="profile_view")
     */
    public function index(string $username)
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * @Route("/{username}/following", name="profile_following")
     */
    public function following(string $username, FollowerRepository $repository)
    {
        return $this->render('profile/following.html.twig');
    }

    /**
     * @Route("/{username}/followers", name="profile_followers")
     */
    public function followers(string $username, FollowerRepository $repository)
    {
        return $this->render('profile/followers.html.twig');
    }
}
