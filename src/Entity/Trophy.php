<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrophyRepository")
 */
class Trophy
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
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $category;

    /**
     * @ORM\Column(type="integer")
     */
    private $argument;

    /**
     * @ORM\ManyToMany(targetEntity="ChildUser", mappedBy="trophies")
     */
    private $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

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
            $child->addTrophy($this);
        }

        return $this;
    }

    public function removeChild(ChildUser $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            $child->removeTrophy($this);
        }

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getArgument(): ?int
    {
        return $this->argument;
    }

    public function setArgument(int $argument): self
    {
        $this->argument = $argument;

        return $this;
    }
}
