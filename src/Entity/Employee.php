<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @method string getUserIdentifier()
 */
#[Entity(repositoryClass:EmployeeRepository::class)]
class Employee implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type:"uuid", unique:true)]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255)]
    private string $firstName;
    #[ORM\Column(length: 255)]
    private string $lastName;

    private string $fullName;
    #[ORM\Column(length: 255)]
    private string $mail;

    #[ORM\Column(length: 255)]
    private string $password;
    #[ORM\Column(length: 255)]
    private string $status;
    #[ORM\Column(length: 255)]
    private string $avatar;
    #[ORM\Column(length: 255)]
    private \DateTime $startDate;

    #[ORM\Column(type:"json")]
    private $roles = [];

    #[ORM\Column(type: "string", nullable: true)]
    private $authCode;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'employees')]
    #[Groups(['employee_projects'])]
    private Collection $projects;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'employee')]
    private Collection $tasks;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->lastName . ' ' .  $this->firstName;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail): self
    {
        $this->mail = $mail;
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
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setEmployee($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getEmployee() === $this) {
                $task->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * @param Collection $projects
     * @return Employee
     */
    public function setProjects(Collection $projects): self
    {
        $this->projects = $projects;
        return $this;
    }

    public function addProject(Project $project): void
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
        }
    }

    public function removeProject(Project $project): void
    {
        $this->projects->removeElement($project);
    }


    public function generateAvatar(): void
    {
        $firstInitial = $this->firstName[0];
        $lastInitial = $this->lastName[0];

        $this->avatar = strtoupper($firstInitial . $lastInitial);
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {}

    public function getUsername()
    {
        return $this->mail;
    }

    public function __call(string $name, array $arguments)
    {
        return $this->getUsername();
    }

    public function isEmailAuthEnabled(): bool
    {
        return true;
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->mail;
    }

    public function getEmailAuthCode(): ?string
    {
        if(null === $this->authCode){
            throw new \LogicException("the mail authentication method has not been set");
        }
        return $this->authCode;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }
}