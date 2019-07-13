<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationsController extends AbstractController
{
    /**
     * @Route("/notifications", name="notifications")
     * @IsGranted("ROLE_USER", statusCode=403)
     */
    public function index(NotificationRepository $repository, ObjectManager $manager)
    {
        $notifications = $repository->findBy([
            'user' => $this->getUser(),
            'is_read' => false
        ]);

        foreach($notifications as $n)
        {
            $n->setIsRead(true);
            $manager->persist($n);
        }

        $manager->flush();

        return $this->render('notifications/index.html.twig', [
            'notifications' => $notifications
        ]);
    }

    /**
     * @Route("/mentions", name="mentions")
     * @IsGranted("ROLE_USER", statusCode=403)
     */
    public function mentions(NotificationRepository $repository, ObjectManager $manager)
    {
        $notifications = $repository->findBy([
            'user' => $this->getUser(),
            'notification_type' => Notification::TYPE_MENTION,
            'is_read' => false
        ]);

        foreach($notifications as $n)
        {
            $n->setIsRead(true);
            $manager->persist($n);
        }

        $manager->flush();

        return $this->render('notifications/mentions.html.twig', [
            'notifications' => $notifications
        ]);
    }
}
