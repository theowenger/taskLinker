<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Form\ProjectType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class UpdateProjectController extends AbstractController
{

    public function __construct(private readonly Environment $twig,private readonly ProjectRepository $projectRepository, private readonly EmployeeRepository $employeeRepository)
    {

    }

    #[Route('/project/{id}/update', name: 'api_update_project', methods: ['POST'])]
    public function __invoke(Request $request, ValidatorInterface $validator, string $id, EntityManagerInterface $entityManager): Response
    {

        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Aucun projet trouvé pour cet ID.');
        }
        $employees = $this->employeeRepository->findAll();

        $form = $this->createForm(ProjectType::class, $project, [
            'employees' => $employees,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            return $this->redirectToRoute('app_project_item', ['id' => $project->getId()]);
        }

        $formView = $form->createView();

        return $this->render('misc/project-item-edit.html.twig', [
            'project' => $project,
            'form' => $formView,
        ]);
    }
}