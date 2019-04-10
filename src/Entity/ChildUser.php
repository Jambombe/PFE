<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChildUserRepository")
 */
class ChildUser
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length(
     *     min=3,
     *     minMessage="Le nom d'aventurier doit être de {{limit}} caractères minimum"
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=1023)
     * @Assert\Length(
     *     min=8,
     *     minMessage="Le mot de passe doit être de {{limit}} caractères minimum"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     */
    private $exp;

    /**
     * @ORM\ManyToOne(targetEntity="ParentUser", inversedBy="children", cascade={"persist"})
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Quest", mappedBy="child")
     */
    private $quests;

    /**
     * @ORM\ManyToMany(targetEntity="Trophy", inversedBy="children")
     */
    private $trophies;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="childUsers", cascade={"remove"}, orphanRemoval=true)
     */
    private $notifications;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
        $this->trophies = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->setExp(0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getExp(): ?int
    {
        return $this->exp;
    }

    public function setExp(int $exp): self
    {
        $this->exp = $exp;

        return $this;
    }

    public function getParent(): ?ParentUser
    {
        return $this->parent;
    }

    public function setParent(?ParentUser $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|Quest[]
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function addQuest(Quest $quest): self
    {
        if (!$this->quests->contains($quest)) {
            $this->quests[] = $quest;
            $quest->setChild($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): self
    {
        if ($this->quests->contains($quest)) {
            $this->quests->removeElement($quest);
            // set the owning side to null (unless already changed)
            if ($quest->getChild() === $this) {
                $quest->setChild(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Trophy[]
     */
    public function getTrophies(): Collection
    {
        return $this->trophies;
    }

    public function addTrophy(Trophy $trophy): self
    {
        if (!$this->trophies->contains($trophy)) {
            $this->trophies[] = $trophy;
        }

        return $this;
    }

    public function removeTrophy(Trophy $trophy): self
    {
        if ($this->trophies->contains($trophy)) {
            $this->trophies->removeElement($trophy);
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setChildUsers($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getChildUsers() === $this) {
                $notification->setChildUsers(null);
            }
        }

        return $this;
    }
}
