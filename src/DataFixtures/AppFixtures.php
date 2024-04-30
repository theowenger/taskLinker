<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /** @var Employee[] */
    private array $employees = [];

    /** @var Project[]  */
    private array $projects = [];

    public function load(ObjectManager $manager): void
    {

        $this->loadEmployees($manager, 20);
        $this->loadProjects($manager, 20);
        $this->loadTasks();

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

    public function loadProjects(ObjectManager $manager, int $count): void
    {
        //todo
        for ($i = 1; $i < $count; $i++) {
            $project = new Project();
            $project
                ->setName("project n°$i")
                ->setIsArchived($i % 2 === 0);

            for ($j = 0; $j < 3; $j++) {
                $project->addEmployee($this->employees[$j]);
            }

            $manager->persist($project);


            $this->projects[] = $project;
        }
    }


    public function loadTasks(): void
    {
        //todo
    }
}
