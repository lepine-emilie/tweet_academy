<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use App\Form\LoginType;


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
     * @Route("/signup", name="signup")
     */
    public function sign_up(Request $request, ObjectManager $manager)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('registration/index.html.twig', [
            'formSignup' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function log_in()
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        return $this->render('registration/login.html.twig', [
            'formLogin' => $form->createView()
        ]);
    }
}