<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\UploadFilePostType;
use App\Form\UserPostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use function MongoDB\BSON\toJSON;
use function Sodium\crypto_box_seal_open;
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
    public function index(PostRepository $repository, Request $request, UserRepository $userRepository, ObjectManager $em)
    {
        if ($this->getUser() === null)
            return $this->redirectToRoute("app_login");
            
        $posts = $repository->findBy([], ['id' => 'desc', 'submit_time' => 'desc']);
        $post = new Post();
        $form = $this->createForm(UserPostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if (!$form->isValid())
                return new JsonResponse(["success" => false]);
            if($form['media_url']->getData() != null)
            {
                $file = $form['media_url']->getData();
                $filename = md5(random_bytes(20)) .
                    ".".pathinfo($file->getClientOriginalName(),
                        PATHINFO_EXTENSION);
                $file->move($this->getParameter('PostUploads_directory'),
                    $filename);
                $post->setMediaUrl($filename);
            }
            $post->setSender($this->getUser());
            $post->setSubmitTime(new \DateTime());
            $em->persist($post);
            $em->flush();
            $view = $this->renderView("common/post_card.html.twig", ['post' => $post]);
            return new JsonResponse(["success" => true, 'htmlTemplate' => $view]);
        }
        return $this->render('home/index.html.twig', [
            'timeline' => $posts,
            'formPost' => $form->createView(),
        ]);
    }
    /**
     * @Route("/actualisation", name="actualisation")
     */
    public function actualisation(PostRepository $repository)
    {
        $repoResponse = $repository->postRepository($this->getUser()->getId());
        $templates = [];
        $cards = [];
        if ($repoResponse !== null)
        {
            foreach ($repoResponse as $value)
                $cards[] = $repository->find($value['id']);
            foreach ($cards as $card)
            {
                $templates[] = $this->renderView('common/post_card.html.twig', [
                    'post' => $card
                ]);
            }
        }
        else
        {
            return new JsonResponse([
                'success' => false
            ]);
        }
        return new JsonResponse([
            'success' => true,
            'htmlTemplate' => $templates
        ]);
    }
}
