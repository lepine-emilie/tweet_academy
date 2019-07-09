<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ChatConversation;
use App\Entity\ChatMessage;
use App\Repository\ChatMessageRepository;
use App\Form\ChatType;
use App\Form\ChatMessageType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;


class ChatController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function chat(Request $request, ObjectManager $manager)
    {
        $conv = new ChatConversation();
        $form = $this->createForm(ChatType::class, $conv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $conv->addParticipant($this->getUser());
            $conv->addParticipant($form->get('conv')->getData());
            $manager->persist($conv);
            $manager->flush();
        }
        else {
            return $this->render('chat/index.html.twig', [
            'formChat' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("/conversations/{id}", name="conversations")
     */
    public function conversation(ChatConversation $chatConversation, Request $request, ObjectManager $manager, ChatMessageRepository $chatMessageRepository)
    {
        $messages = new ChatMessage();
        $formMsg = $this->createForm(ChatMessageType::class, $messages);
        $formMsg->handleRequest($request);

        if ($formMsg->isSubmitted() && $formMsg->isValid())
        {
            $messages->getConversation();
            $messages->setSender($this->getUser());
            $messages->getContent($formMsg->get('content')->getData());
            $messages->setSubmitTime(new \DateTime());
            $manager->persist($messages);
            $manager->flush();
        }
        else {
            return $this->render('chat/conversation.html.twig', [
            'formMessages'=> $formMsg->createView(),
            'messages' => $chatMessageRepository->getLastMessages()
            ]);
        }
    }
}