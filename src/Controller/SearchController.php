<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index()
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    /**
     * @Route("/search_results", name="search_home")
     */
    public function search_from_home(UserRepository $userRepository, PostRepository $postRepository){
        $type = $_GET['search_type'] ?? "post";
        if ($type ==="people"){
            $result = $userRepository->search_user($_GET['search_term']);
        }
        elseif ($type === "post"){
            $result = $postRepository->search_post($_GET['search_term']);
        }


        return $this->render('search/index.html.twig', [
            "results"=>$result,
            "search_info"=>$_GET['search_term'],
            "search_type"=>$type
        ]);
    }
}
