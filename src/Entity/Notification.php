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
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="ChildUser", inversedBy="notifications")
     */
    private $childUsers;

    /**
     * @ORM\ManyToOne(targetEntity="ParentUser", inversedBy="notifications")
     */
    private $parentUsers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getChildUsers(): ?ChildUser
    {
        return $this->childUsers;
    }

    public function setChildUsers(?ChildUser $childUsers): self
    {
        $this->childUsers = $childUsers;

        return $this;
    }

    public function getParentUsers(): ?ParentUser
    {
        return $this->parentUsers;
    }

    public function setParentUsers(?ParentUser $parentUsers): self
    {
        $this->parentUsers = $parentUsers;

        return $this;
    }
}
