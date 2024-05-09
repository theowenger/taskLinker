<?php

namespace App\Controller\Api;

use App\Form\ProjectType;
use App\Form\TaskType;
use App\Repository\EmployeeRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class UpdateTaskController extends AbstractController
{

    public function __construct(private readonly Environment $twig, private readonly TaskRepository $taskRepository, private readonly EmployeeRepository $employeeRepository)
    {

    }

    #[Route('/task/{id}/update', name: 'api_update_task', methods: ['POST'])]
    public function __invoke(
        Request                $request,
        ValidatorInterface     $validator,
        string                 $id,
        EntityManagerInterface $entityManager
    ): Response
    {

        $task = $this->taskRepository->find($id);
        $project = $task->getProject();

        if (!$task) {
            throw $this->createNotFoundException('Aucune tache trouvée pour cet ID.');
        }
        $employees = $project->getEmployees();

        $form = $this->createForm(TaskType::class, $task, [
            'employees' => $employees,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            return $this->redirectToRoute('app_project_item', [
                'id' => $project->getId()
            ]);
        }

        $formView = $form->createView();

        return $this->render('misc/task-item.html.twig', [
            'project' => $project,
            'task' => $task,
            'form' => $formView,
        ]);
    }
}