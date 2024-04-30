<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Entity\Project;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class DeleteEmployeeController extends AbstractController
{

    public function __construct(private readonly  ProjectRepository $projectRepository, private readonly EmployeeRepository $employeeRepository)
    {

    }
    #[Route('/employee/{id}/delete', name: 'api_delete_employee', methods: ['POST'])]
    public function __invoke(Request $request, string $id, EntityManagerInterface $entityManager): Response
    {

        /** @var Employee $employee */
        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            throw $this->createNotFoundException('Aucun employé trouvé pour cet ID.');
        }

        $projects = $employee->getProjects();

        foreach ($projects as $project) {
            $project->removeEmployee($employee);
//            $employee->removeProject($project);
//            $entityManager->persist($projects);
        }


        //todo: retirer l'employé des projets/ taches sur lequel il est nommé

        $entityManager->remove($employee);
        $entityManager->flush();

        return new Response(200);
    }
}