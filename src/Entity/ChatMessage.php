<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatMessageRepository")
 */
class ChatMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ChatConversation", inversedBy="chatMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $submit_time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversation(): ?ChatConversation
    {
        return $this->conversation;
    }

    public function setConversation(?ChatConversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSubmitTime(): ?\DateTimeInterface
    {
        return $this->submit_time;
    }

    public function setSubmitTime(\DateTimeInterface $submit_time): self
    {
        $this->submit_time = $submit_time;

        return $this;
    }
}
