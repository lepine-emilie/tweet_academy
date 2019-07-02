<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notification", name="notification")
     */
    public function index()
    {
        return $this->render('notification/index.html.twig', [
            'controller_name' => 'NotificationController',
        ]);
    }
}
