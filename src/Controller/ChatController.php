<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ChatConversation;
use App\Repository\ChatMessageRepository;
use App\Form\ChatType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Driver\Connection;


class ChatController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function chat(Request $request, ObjectManager $manager, ChatMessageRepository $chatMessageRepository)
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
            'messages' => $chatMessageRepository->getLastMessages()
        ]);
    }
}