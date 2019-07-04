<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FollowerRepository")
 */
class Follower
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="followers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="following")
     * @ORM\JoinColumn(nullable=false)
     */
    private $follower;

    /**
     * @ORM\Column(type="datetime")
     */
    private $follow_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $unfollow_date;

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

    public function getFollower(): ?User
    {
        return $this->follower_id;
    }

    public function setFollower(?User $follower): self
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowDate(): ?\DateTimeInterface
    {
        return $this->follow_date;
    }

    public function setFollowDate(\DateTimeInterface $follow_date): self
    {
        $this->follow_date = $follow_date;

        return $this;
    }

    public function getUnfollowDate(): ?\DateTimeInterface
    {
        return $this->unfollow_date;
    }

    public function setUnfollowDate(?\DateTimeInterface $unfollow_date): self
    {
        $this->unfollow_date = $unfollow_date;

        return $this;
    }
}
