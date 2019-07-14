<?php

namespace App\Controller;

use App\Entity\Hashtag;
use App\Repository\HashtagRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search_results", name="search_home")
     */
    public function search_from_home(Request $request, UserRepository $userRepository, PostRepository $postRepository)
    {
        $type = $request->query->get('search_type') ?? "post";
        $term = $request->query->get('search_term');

        if ($type === "people")
            $result = $userRepository->search_user($term);
        elseif ($type === "post")
            $result = $postRepository->search_post($term);

        return $this->render('search/index.html.twig', [
            "results" => $result ?? null,
            "search_info" => $term,
            "search_type" => $type
        ]);
    }

    /**
     * @Route("/hashtag/{name}", name="hashtag_search")
     */
    public function hashtag_search(string $name, HashtagRepository $repository)
    {
        $hashtag = $repository->findOneBy([
            'name' => '#' . $name
        ], ['id' => 'DESC']);

        if ($hashtag == null)
            throw $this->createNotFoundException();

        return $this->render('search/index.html.twig', [
            "results" => $hashtag->getPosts(),
            'search_info' => $name,
            'search_type' => 'post',
            'search_type_ex' => 'hashtag'
        ]);
    }
}
