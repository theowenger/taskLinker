<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class AppFixtures extends Fixture
{
    /** @var Employee[] */
    private array $employees = [];

    /** @var Project[]  */
    private array $projects = [];

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {

        $this->loadEmployees($manager, 20);
        $this->loadProjects($manager, 20);

        foreach ($this->projects as $project) {
            $this->loadTasks($manager, $project);
        }

        $manager->flush();
    }


    public function loadEmployees(ObjectManager $manager, int $count): void
    {

        $firstNames = ['John', 'Jane', 'Michael', 'Emily', 'William', 'Olivia', 'James', 'Emma', 'Benjamin', 'Ava', 'Daniel', 'Sophia', 'Alexander', 'Isabella', 'Matthew', 'Mia', 'Christopher', 'Charlotte', 'Andrew', 'Amelia'];

        $lastNames = ['Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Moore', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White', 'Harris', 'Martin', 'Thompson', 'Garcia', 'Martinez', 'Robinson'];

        for ($i = 1; $i < $count; $i++) {
            $employee = new Employee();
            $manager->persist($employee);

            $employee->setFirstName($firstNames[$i])
                ->setLastName($lastNames[$i])
                ->setMail($firstNames[$i] . '.' . $lastNames[$i] . '@gmail.com')
                ->setStartDate(new \DateTime())
                ->setStatus($i % 2 === 0 ? 'CDI' : 'CDD')
                ->generateAvatar();

            $this->employees[] = $employee;
        }
    }

    /**
     * @throws RandomException
     */
    public function loadProjects(ObjectManager $manager, int $count): void
    {
        //todo
        for ($i = 1; $i < $count; $i++) {

            $project = new Project();
            $project
                ->setName("project n°$i")
                ->setIsArchived($i % 2 === 0);

            for ($j = 0; $j < 3; $j++) {
                $randomEmployee = $this->employees[random_int(0, count($this->employees) - 1)];
                $project->addEmployee($randomEmployee);
            }

            $manager->persist($project);


            $this->projects[] = $project;
        }
    }

    /**
     * @throws \Exception
     */
    public function loadTasks(ObjectManager $manager, Project $project): void
    {
        for($i = 1; $i <= 6; $i++) {
            $arrayEmployee = $project->getEmployees()->toArray();
            $randomEmployee = $arrayEmployee[random_int(0, count($arrayEmployee) - 1)];

            $task = new Task();
            $task->setProject($project)
                ->setStartDate(new \DateTimeImmutable('now -' . $i . ' day'))
                ->setName("Task $i")
                ->setDescription("Task description $i")
                ->setDeadline(new \DateTime('now +' . $i . ' day'))
                ->setEmployee($randomEmployee)
            ;
            if ($i === 1 || $i === 2) {
                $task->setStatus(TaskStatus::TODO->value);
            }
            if ($i === 3 || $i === 4) {
                $task->setStatus(TaskStatus::DOING->value);
            }
            if ($i === 5 || $i === 6) {
                $task->setStatus(TaskStatus::DONE->value);
            }

            $manager->persist($task);
        }
    }
}
