<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post")
     */
    private $parent_post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post")
     */
    private $source_post;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $media_url;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $media_type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $submit_time;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getParentPost(): ?self
    {
        return $this->parent_post;
    }

    public function setParentPost(?self $parent_post): self
    {
        $this->parent_post = $parent_post;

        return $this;
    }

    public function getSourcePost(): ?self
    {
        return $this->source_post;
    }

    public function setSourcePost(?self $source_post): self
    {
        $this->source_post = $source_post;

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

    public function getMediaUrl(): ?string
    {
        return $this->media_url;
    }

    public function setMediaUrl(?string $media_url): self
    {
        $this->media_url = $media_url;

        return $this;
    }

    public function getMediaType(): ?int
    {
        return $this->media_type;
    }

    public function setMediaType(?int $media_type): self
    {
        $this->media_type = $media_type;

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
