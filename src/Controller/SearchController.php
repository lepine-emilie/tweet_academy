<?php

namespace App\Controller;

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
    public function search_from_home(UserRepository $userRepository){

        $result = $userRepository->search_user($_GET['search-info']);
        return $this->render('search/index.html.twig', [
            "results"=>$result
        ]);
    }
}
