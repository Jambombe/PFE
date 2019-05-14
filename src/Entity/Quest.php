<?php

namespace App\Entity;

use App\Service\QuestStatusService;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestRepository")
 */
class Quest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=5,
     *     minMessage="Le titre de la quête doit être de minimum {{ limit }} caractères",
     *     max=255,
     *     maxMessage="Le titre est trop long"
     * )
     * @Assert\NotNull
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=2047, nullable=true)
     * @Assert\Length(
     *     max=2047,
     *     maxMessage="La description est trop longue"
     * )
     */
    private $description;


    /**
     * @ORM\Column(type="integer")
     */
    private $exp;

    /**
     * @ORM\Column(type="integer")
     */
    private $goldCoins;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $daily;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $assignatedDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $returnDate;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $limitTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="ParentUser", inversedBy="quests", cascade={"persist"})
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="ChildUser", inversedBy="quests")
     */
    private $child;

    public function __construct()
    {
        $this->status = QuestStatusService::CREATED;
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDaily(): ?\DateTimeInterface
    {
        return $this->daily;
    }

    public function setDaily(?\DateTimeInterface $daily): self
    {
        $this->daily = $daily;

        return $this;
    }

    public function getAssignatedDate(): ?\DateTimeInterface
    {
        return $this->assignatedDate;
    }

    public function setAssignatedDate(?\DateTimeInterface $assignatedDate): self
    {
        $this->assignatedDate = $assignatedDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(?\DateTimeInterface $returnDate): self
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function getLimitTime(): ?\DateTimeInterface
    {
        return $this->limitTime;
    }

    public function setLimitTime(?\DateTimeInterface $limitTime): self
    {
        $this->limitTime = $limitTime;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOwner(): ?ParentUser
    {
        return $this->owner;
    }

    public function setOwner(?ParentUser $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getChild(): ?ChildUser
    {
        return $this->child;
    }

    public function setChild(?ChildUser $child): self
    {
        $this->child = $child;

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

    public function getGoldCoins(): ?int
    {
        return $this->goldCoins;
    }

    public function setGoldCoins(int $goldCoins): self
    {
        $this->goldCoins = $goldCoins;

        return $this;
    }
}
