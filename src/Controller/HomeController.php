<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $repository)
    {
        $posts = $repository->findBy([], ['id' => 'desc', 'submit_time' => 'desc']);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'timeline' => $posts
        ]);
    }
}
