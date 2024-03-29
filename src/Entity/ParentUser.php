<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParentUserRepository")
 * @UniqueEntity("email", message="Cette adresse email est déjà utilisée")
 */
class ParentUser implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(
     *     message = "L'adresse e-mail {{ value }} n'est pas valide",
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $emailToken;

    /**
     * not persisted plainPassword
     * @Assert\Length(
     *     min=8,
     *     minMessage="Le mot de passe doit être de {{ limit }} caractères minimum"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=1023)
     * @Assert\Length(
     *     min=8,
     *     minMessage="Le mot de passe doit être de {{ limit }} caractères minimum"
     * )
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="ChildUser", mappedBy="parent", cascade={"persist","remove"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="Quest", mappedBy="owner", cascade={"persist","remove"})
     */
    private $quests;

    /**
     * DC2Type:array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $lostPasswordToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lostPasswordDate;

    /**
     * Non mapped field, used when changing the password
     */
    private $oldPassword;

    /**
     * @ORM\OneToMany(targetEntity="CustomReward", mappedBy="rewardOwner", cascade={"remove"}, orphanRemoval=true)
     */
    private $customRewards;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="parentUsers", cascade={"remove"}, orphanRemoval=true)
     */
    private $notifications;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->quests = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->setRoles(['ROLE_USER']);
        $this->customRewards = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }
//////////
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
//////////
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
//////////
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
//////////
    /**
     * @return Collection|ChildUser[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(ChildUser $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(ChildUser $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }
//////////
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
            $quest->setOwner($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): self
    {
        if ($this->quests->contains($quest)) {
            $this->quests->removeElement($quest);
            // set the owning side to null (unless already changed)
            if ($quest->getOwner() === $this) {
                $quest->setOwner(null);
            }
        }

        return $this;
    }
//////////
    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
        ) = unserialize($serialized);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }
//////////
    public function getEmailToken(): ?string
    {
        return $this->emailToken;
    }

    public function setEmailToken(?string $emailToken): self
    {
        $this->emailToken = $emailToken;

        return $this;
    }
//////////

    public function setPlainPassword($password) {
        $this->plainPassword = $password;
        return $this;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }
//////////
    public function getLostPasswordToken(): ?string
    {
        return $this->lostPasswordToken;
    }

    public function setLostPasswordToken(?string $lostPasswordToken): self
    {
        $this->lostPasswordToken = $lostPasswordToken;

        return $this;
    }
//////////
    public function getLostPasswordDate(): ?\DateTimeInterface
    {
        return $this->lostPasswordDate;
    }

    public function setLostPasswordDate(?\DateTimeInterface $lostPasswordDate): self
    {
        $this->lostPasswordDate = $lostPasswordDate;

        return $this;
    }
//////////
    /**
     * Add a role
     *
     * @param string $role
     * @return ParentUser
     */
    public function addRole($role){
        $roles = $this->getRoles();
        if(!in_array($role, $roles)){
            $this->setRoles(array_merge($roles, array($role)));
        }

        return $this;
    }

    /**
     * Remove a role
     *
     * @param string $role
     * @return ParentUser
     */
    public function removeRole($role){
        $roles = $this->getRoles();
        if(in_array($role, $roles)){
            $this->setRoles(array_diff($roles, array($role)));
        }

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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
            $notification->setParentUsers($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getParentUsers() === $this) {
                $notification->setParentUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CustomReward[]
     */
    public function getCustomRewards(): Collection
    {
        return $this->customRewards;
    }

    public function addCustomReward(CustomReward $customReward): self
    {
        if (!$this->customRewards->contains($customReward)) {
            $this->customRewards[] = $customReward;
            $customReward->setRewardOwner($this);
        }

        return $this;
    }

    public function removeCustomReward(CustomReward $customReward): self
    {
        if ($this->customRewards->contains($customReward)) {
            $this->customRewards->removeElement($customReward);
            // set the owning side to null (unless already changed)
            if ($customReward->getRewardOwner() === $this) {
                $customReward->setRewardOwner(null);
            }
        }

        return $this;
    }
}
