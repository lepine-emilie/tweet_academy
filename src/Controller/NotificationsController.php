<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationsController extends AbstractController
{
    /**
     * @Route("/notifications", name="notifications")
     */
    public function index(NotificationRepository $repository)
    {
        $notifications = $repository->findAll();

        return $this->render('notifications/index.html.twig', [
            'notifications' => $notifications
        ]);
    }

    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentions(NotificationRepository $repository)
    {
        $notifications = $repository->findAll();

        return $this->render('notifications/mentions.html.twig', [
            'notifications' => $notifications
        ]);
    }
}
