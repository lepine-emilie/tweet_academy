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
    public function index(PostRepository $repository, Request $request,
                          UserRepository $userRepository, ObjectManager $em,
                          HashtagRepository $hashtagRepository)
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
                return new JsonResponse([
                    "success" => false,
                    'errors' => $form->getErrors()
                ]);

            $post->setSender($this->getUser());
            $post->setSubmitTime(new \DateTime());


            $parent_post_id = $form->get('parent_post_id')->getData();
            if (false === empty($parent_post_id))
            {
                $parent_post = $repository->find($parent_post_id);
                if ($parent_post !== null)
                    $post->setParentPost($parent_post);
            }

            $em->persist($post);
            if ($form['media_url']->getData() != null)
            {
                $file = $form['media_url']->getData();
                $filename = md5(random_bytes(20)) .
                    "." . pathinfo($file->getClientOriginalName(),
                        PATHINFO_EXTENSION);
                $file->move($this->getParameter('PostUploads_directory'),
                    $filename);
                $post->setMediaUrl($filename);
            }
            if (preg_match('/#([A-Za-z0-9])\w+/',
                $form['content']->getData()))
            {
                $matches = [];
                preg_match_all('/#([A-Za-z0-9])\w+/',
                    $form['content']->getData(), $matches);
                $matches[0] = array_unique($matches[0]);
                foreach ($matches[0] as $key => $valTag)
                {
                    if ($hashtagRepository->findOneBy(['name' => $valTag]) !== null)
                    {
                        $hashtag = $hashtagRepository->findOneBy(['name' => $valTag]);
                        $hashtag->setUseCount($hashtag->getUseCount() + 1);
                        $hashtag->addPost($post);
                    }
                    else
                    {
                        $hashtag = new Hashtag();
                        $hashtag->setName($valTag);
                        $hashtag->setUseCount(1);
                        $hashtag->addPost($post);
                        $em->persist($hashtag);
                    }
                }
            }
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