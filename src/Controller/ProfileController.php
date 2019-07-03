<?php

namespace App\Controller;

use App\Repository\FollowerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * @Route("/profile/following", name="profile_following")
     */
    public function following(FollowerRepository $repository)
    {
        return $this->render('profile/following.html.twig');
    }

    /**
     * @Route("/profile/followers", name="profile_followers")
     */
    public function followers(FollowerRepository $repository)
    {
        return $this->render('profile/followers.html.twig');
    }

    /**
     * @Route("/profile/view/{username}", name="profile_view")
     */
    public function view(string $username)
    {
        return $this->render('profile/index.html.twig');
    }
}
