<?php

namespace App\Controller;

use App\Entity\Hashtag;
use App\Repository\HashtagRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
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

    /**
     * @Route("/hashtag/{name}", name="hashtag_search")
     */
    public function hashtag_search(string $name, HashtagRepository $repository)
    {
        $hashtag = $repository->findOneBy([
            'name' => '#' . $name
        ]);

        if ($hashtag == null)
            throw $this->createNotFoundException();

        return $this->render('search/index.html.twig', [
            "results" => $hashtag->getPosts(),
            'search_info' => $name,
            'search_type' => 'post'
        ]);
    }
}
