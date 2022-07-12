<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Task
{
    public const STATE_NEW = 'Новая';
    public const STATE_PROGRESS = 'В процессе';
    public const STATE_CANCEL = 'Отменена';
    public const STATE_END = 'Закончена';

    public const STATE_LIST = [self::STATE_NEW,self::STATE_PROGRESS,self::STATE_CANCEL,self::STATE_END];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *@ORM\Column(type="datetime_immutable")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices=Task::STATE_LIST, message="Choose a valid state.")
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->state=self::STATE_NEW;
    }


    public function Comparison():bool
    {
        if ( $this -> getDateCreated() < new \DateTime('-1 hour') and ($this -> getState() == 'Новая')) {
            return  true;
        }
        else {
            return  false;
        }
    }

    /**
     * @ORM\PrePersist()
     */
    public function SetCreatedAtValue():void
    {
        $this->dateCreated = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreated(): DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function setDateCreated(DateTimeImmutable $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}

