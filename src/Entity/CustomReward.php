<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomRewardRepository")
 */
class CustomReward
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
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $goldCoinPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbUnitAvailable;

    /**
     * @ORM\Column(type="string", length=1023)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="ParentUser", inversedBy="customRewards")
     */
    private $rewardOwner;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGoldCoinPrice(): ?int
    {
        return $this->goldCoinPrice;
    }

    public function setGoldCoinPrice(int $goldCoinPrice): self
    {
        $this->goldCoinPrice = $goldCoinPrice;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getRewardOwner(): ?ParentUser
    {
        return $this->rewardOwner;
    }

    public function setRewardOwner(?ParentUser $rewardOwner): self
    {
        $this->rewardOwner = $rewardOwner;

        return $this;
    }

    public function getNbUnitAvailable(): ?int
    {
        return $this->nbUnitAvailable;
    }

    public function setNbUnitAvailable(int $nbUnitAvailable): self
    {
        $this->nbUnitAvailable = $nbUnitAvailable;

        return $this;
    }

    public function removeOneUnit(): self {
        $this->nbUnitAvailable -= 1;

        return $this;
    }
}
