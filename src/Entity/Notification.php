<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="smallint")
     */
    private $notification_type;

    /**
     * @ORM\Column(type="json")
     */
    private $notification_data = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_read;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNotificationType(): ?int
    {
        return $this->notification_type;
    }

    public function setNotificationType(int $notification_type): self
    {
        $this->notification_type = $notification_type;

        return $this;
    }

    public function getNotificationData(): ?array
    {
        return $this->notification_data;
    }

    public function setNotificationData(array $notification_data): self
    {
        $this->notification_data = $notification_data;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }
}
