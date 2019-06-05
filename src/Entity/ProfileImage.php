<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileImageRepository")
 */
class ProfileImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1023)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLocal;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $requiredLevel;

    /**
     * @ORM\ManyToMany(targetEntity="ChildUser", mappedBy="unlockedImages")
     */
    private $unlockedByChildren;

    /**
     * @ORM\OneToMany(targetEntity="ChildUser", mappedBy="profileImage")
     */
    private $setByChildren;

    public function __construct()
    {
        $this->unlockedByChildren = new ArrayCollection();
        $this->setByChildren = new ArrayCollection();
        $this->requiredLevel = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getIsLocal(): ?bool
    {
        return $this->isLocal;
    }

    public function setIsLocal(bool $isLocal): self
    {
        $this->isLocal = $isLocal;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|ChildUser[]
     */
    public function getUnlockedByChildren(): Collection
    {
        return $this->unlockedByChildren;
    }

    public function addUnlockedByChild(ChildUser $unlockedByChild): self
    {
        if (!$this->unlockedByChildren->contains($unlockedByChild)) {
            $this->unlockedByChildren[] = $unlockedByChild;
            $unlockedByChild->setUnlockedImages($this);
        }

        return $this;
    }

    public function removeUnlockedByChild(ChildUser $unlockedByChild): self
    {
        if ($this->unlockedByChildren->contains($unlockedByChild)) {
            $this->unlockedByChildren->removeElement($unlockedByChild);
            // set the owning side to null (unless already changed)
            if ($unlockedByChild->getUnlockedImages() === $this) {
                $unlockedByChild->setUnlockedImages(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ChildUser[]
     */
    public function getSetByChildren(): Collection
    {
        return $this->setByChildren;
    }

    public function addSetByChild(ChildUser $setByChild): self
    {
        if (!$this->setByChildren->contains($setByChild)) {
            $this->setByChildren[] = $setByChild;
            $setByChild->setProfileImage($this);
        }

        return $this;
    }

    public function removeSetByChild(ChildUser $setByChild): self
    {
        if ($this->setByChildren->contains($setByChild)) {
            $this->setByChildren->removeElement($setByChild);
            // set the owning side to null (unless already changed)
            if ($setByChild->getProfileImage() === $this) {
                $setByChild->setProfileImage(null);
            }
        }

        return $this;
    }

    public function getRequiredLevel(): ?int
    {
        return $this->requiredLevel;
    }

    public function setRequiredLevel(int $requiredLevel): self
    {
        $this->requiredLevel = $requiredLevel;

        return $this;
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
}
