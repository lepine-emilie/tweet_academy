<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\UserPostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use function MongoDB\BSON\toJSON;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $repository, Request $request,
    UserRepository $userRepository, ObjectManager $em)
    {
        $posts = $repository->findBy([], ['id' => 'desc', 'submit_time' => 'desc']);
        $post = new Post();
        $form = $this->createForm(UserPostType::class, $post);
        $user = $userRepository->findById(35)[0];
        $post->setSender($user);
        $post->setSubmitTime(new \DateTime());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($post);
            return(new Response());
        }
        elseif ($form->isSubmitted() && !$form->isValid())
        {
            return(new Response("hoho"));
        }
        else
        {
            return $this->render('home/index.html.twig', [
                'timeline' => $posts,
                'formPost' => $form->createView()
            ]);
        }
    }
}
