<?php

namespace App\Controller;

use App\Entity\Hashtag;
use App\Entity\Post;
use App\Form\UploadFilePostType;
use App\Form\UserPostType;
use App\Repository\HashtagRepository;
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
    public function index(PostRepository $repository)
    {
        if ($this->getUser() === null)
            return $this->redirectToRoute("app_login");

        // TODO: query posts from us + following
        $posts = $repository->findBy([], ['id' => 'desc', 'submit_time' => 'desc']);
        $form = $this->createForm(UserPostType::class);

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
        if ($this->getUser() === null)
            throw $this->createNotFoundException();

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