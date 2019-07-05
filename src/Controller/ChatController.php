<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ChatConversation;
use App\Form\ChatType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;


class ChatController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index()
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }

    /**
     * @Route("/conv", name="conv")
     */
    public function chat(Request $request, ObjectManager $manager)
    {
        $chat = new ChatConversation();
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $participant = 'test';
            $manager->addParticipant($participant);
            $manager->persist($chat);
            $manager->flush();
        }
        return $this->render('chat/index.html.twig', [
            'formChat' => $form->createView(),
            'route' => 'conv'
        ]);
    }
}
