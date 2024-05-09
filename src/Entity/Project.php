<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(repositoryClass:ProjectRepository::class)]
class Project
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private \DateTimeImmutable $startDate;

    #[ORM\Column(type:"boolean")]
    private bool $isArchived = false;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project')]
    #[Groups(['project_tasks'])]
    private Collection $tasks;
    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_employees')]
    #[Groups(['project_employees'])]
    private Collection $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->startDate = new \DateTimeImmutable();
    }

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
     * @return Collection
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    /**
     * @param Collection $employees
     */
    public function setEmployees(Collection $employees): self
    {
        $this->employees = $employees;
        $this->employees = $employees;
        return $this;
    }
    public function addEmployee(Employee $employee): void
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
        }
    }

    public function removeEmployee(Employee $employee): void
    {
        $this->employees->removeElement($employee);
    }


    /**
     * @return Collection
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param Collection $tasks
     */
    public function setTasks(Collection $tasks): self
    {
        $this->tasks = $tasks;
        return $this;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    /**
     * @param bool $isArchived
     */
    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;
        return $this;
    }
}