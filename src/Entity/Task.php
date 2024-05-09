<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping as ORM;

#[Entity(repositoryClass: TaskRepository::class)]
class Task
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private string $name;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;
    #[ORM\Column]
    private \DateTimeImmutable $startDate;
    #[ORM\Column(nullable: true)]
    private ?\DateTime $deadline = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Employee $employee = null;
    #[ORM\Column]
    private string $status;
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'tasks')]
    private Project $project;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Task
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @param \DateTimeImmutable $startDate
     */
    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeadline(): ?\DateTime
    {
        return $this->deadline;
    }

    /**
     * @param \DateTime|null $deadline
     */
    public function setDeadline(?\DateTime $deadline): self
    {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Employee|null
     */
    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    /**
     * @param Employee|null $employee
     * @return Task
     */
    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project): self
    {
        $this->project = $project;
        return $this;
    }
}