<?php

namespace App\Controller;

use App\Entity\Notification;
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
        $notifications = $repository->findBy([
            'is_read' => false
        ]);

        return $this->render('notifications/index.html.twig', [
            'notifications' => $notifications
        ]);
    }

    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentions(NotificationRepository $repository)
    {
        $notifications = $repository->findBy([
            'notification_type' => Notification::TYPE_MENTION,
            'is_read' => false
        ]);

        return $this->render('notifications/mentions.html.twig', [
            'notifications' => $notifications
        ]);
    }
}
