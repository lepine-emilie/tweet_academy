<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordChangeType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     */
    public function index()
    {
        return $this->redirectToRoute('settings_account');
    }

    /**
     * @Route("/settings/account", name="settings_account")
     */
    public function accountSettings(Request $request,
                                    ObjectManager $em,
                                    UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(PasswordChangeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var User $user */
            $user = $this->getUser();

            $password = $form->get('new_password')->getData();
            $hash = $encoder->encodePassword($user, $password);
            $user->setPassword($hash);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("app_login");
        }

        return $this->render('settings/account.html.twig', [
            'formChangePassword' => $form->createView()
        ]);
    }
}
