<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ChatConversation;
use App\Form\ChatType;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;


class ChatController extends AbstractController
{
    /**
     * @Route("/conv", name="conv")
     */
    public function index()
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }

    /**
     * @Route("/messages", name="messages")
     */
    public function chat(Request $request, ObjectManager $manager)
    {
        $conv = new ChatConversation();
        $form = $this->createForm(ChatType::class, $conv);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $conv->addParticipant($this->getUser());
            $conv->addParticipant($form->get('conv')->getData());
            $manager->persist($conv);
            $manager->flush();
        }
        return $this->render('chat/index.html.twig', [
            'formChat' => $form->createView(),
            'route' => 'messages'
        ]);
    }
}
