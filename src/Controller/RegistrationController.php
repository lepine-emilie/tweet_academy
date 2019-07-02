<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function index()
    {
        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
        ]);
    }

    /**
     * @Route("/registration/sign_up", name="sign_up")
     */
    public function sign_up()
    {
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);

        return $this->render('registration/index.html.twig', [
            'formRegistration' => $form->createView()
        ]);
    }
}