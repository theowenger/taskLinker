<?php

namespace App\Controller\Api;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\ProjectType;
use App\Form\TaskType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTaskController extends AbstractController
{

    public function __construct(private readonly ProjectRepository $projectRepository, private readonly EmployeeRepository $employeeRepository)
    {

    }

    #[Route('/projects/{id}/task/create', name: 'api_create_task', methods: ['POST'])]
    public function __invoke(
        Request                $request,
        ValidatorInterface     $validator,
        EntityManagerInterface $entityManager,
        string $id
    ): Response
    {

        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Aucune tache trouvée pour cet ID.');
        }

        $employees = $project->getEmployees();

        $task = new Task();
        $task->setProject($project)
            ->setName('')
            ->setStartDate(new \DateTimeImmutable())
        ;

        $form = $this->createForm(TaskType::class, $task, [
            'employees' => $employees,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_item', ['id' => $project->getId()]);

        }

        return $this->redirectToRoute('app_project_item_edit');
    }
}