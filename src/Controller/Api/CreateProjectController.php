<?php

namespace App\Controller\Api;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateProjectController extends AbstractController
{

    public function __construct(private readonly EmployeeRepository $employeeRepository)
    {

    }

    #[Route('/project/create', name: 'api_create_project', methods: ['POST'])]
    public function __invoke(
        Request                $request,
        ValidatorInterface     $validator,
        EntityManagerInterface $entityManager
    ): Response
    {

        $project = new Project();
        $project->setName("")
            ->setIsArchived(false)
            ->setStartDate(new \DateTimeImmutable());

        $employees = $this->employeeRepository->findAll();

        $form = $this->createForm(ProjectType::class, $project, [
            'employees' => $employees,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_item', ['id' => $project->getId()]);
        }

        //todo: afficher gestion des erreurs
        return $this->redirectToRoute('app_project_item_edit', ['errors' => $form->getErrors(true)]);
    }
}