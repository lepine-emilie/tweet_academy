<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\UserPostType;
use App\Repository\FavoriteRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommonController extends AbstractController
{
    public function miniProfile()
    {
        return $this->render('common/mini_profile.html.twig');
    }
    public function trends()
    {
        return $this->render('common/trends.html.twig');
    }

    public function footer()
    {
        return $this->render('common/notafooter.html.twig');
    }

    public function postMaker()
    {
        $post = new Post();
        $form = $this->createForm(UserPostType::class, $post);

        return $this->render('common/post_maker.html.twig', [
            'formPost' => $form->createView()
        ]);
    }

    public function suggestions(UserRepository $userRepository)
    {
        $suggestions = [];

        foreach($userRepository->findAll() as $u)
        {
            if (count($suggestions) >= 3)
                break;

            if ($u->getId() != $this->getUser()->getId() && $u->getFollowers()->isEmpty())
                array_push($suggestions, $u);
        }

        return $this->render('common/suggestions.html.twig', [
            'suggestions' => $suggestions
        ]);
    }
}
