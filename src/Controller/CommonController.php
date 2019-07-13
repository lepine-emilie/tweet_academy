<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\User;
use App\Form\UserPostType;
use App\Repository\FavoriteRepository;
use App\Repository\HashtagRepository;
use App\Repository\NotificationRepository;
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

    public function trends(HashtagRepository $hashtagRepository)
    {
        $trends = $hashtagRepository->findBy([],
            ['use_count' => 'desc'], 10
        );

        return $this->render('common/trends.html.twig', [
            'trends' => $trends
        ]);
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
        /** @var User $user */
        $user = $this->getUser();
        //$suggestions = $userRepository->getNonFollowedByUser($user);
        $suggestions = [];

        return $this->render('common/suggestions.html.twig', [
            'suggestions' => $suggestions
        ]);
    }

    public function navBar(string $routeForward, NotificationRepository $notificationRepository)
    {
        $notificationCount = $notificationRepository->count([
            'user' => $this->getUser(),
            'is_read' => false
        ]);

        return $this->render('navbar.html.twig', [
            'route_fwd' => $routeForward,
            'pending_notification_count' => $notificationCount ?? 0
        ]);
    }

    public function notificationCard(Notification $n,
                                     UserRepository $userRepository,
                                     PostRepository $postRepository)
    {
        $json = $n->getNotificationData();

        if (array_key_exists('user_id', $json))
            $user = $userRepository->find($json['user_id']);

        if (array_key_exists('post_id', $json))
            $post = $postRepository->find($json['post_id']);

        return $this->render('notifications/_card_data.html.twig', [
            'notification' => $n,
            'user' => $user ?? null,
            'post' => $post ?? null
        ]);
    }
}
