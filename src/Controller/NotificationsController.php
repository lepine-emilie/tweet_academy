<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationsController extends AbstractController
{
    /**
     * @Route("/notifications", name="notifications")
     */
    public function index()
    {
        return $this->render('notifications/index.html.twig');
    }

    public function mentions()
    {
        return $this->render('notifications/mentions.html.twig');
    }
}
