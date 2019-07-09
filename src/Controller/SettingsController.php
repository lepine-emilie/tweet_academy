<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    public function accountSettings()
    {
        return $this->render('settings/account.html.twig');
    }

    /**
     * @Route("/settings/privacy", name="settings_account")
     */
    public function privacySettings()
    {
        return $this->render('settings/account.html.twig');
    }

    /**
     * @Route("/settings/password", name="settings_password")
     */
    public function passwordSettings()
    {
        return $this->render('settings/account.html.twig');
    }
}
