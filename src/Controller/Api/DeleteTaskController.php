<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTaskController extends AbstractController
{

    public function __construct(private readonly  TaskRepository $taskRepository)
    {

    }
    #[Route('/tasks/{id}/delete', name: 'api_delete_task', methods: ['POST'])]
    public function __invoke(Request $request, string $id, EntityManagerInterface $entityManager): Response
    {

        /** @var Task $task */
        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Aucune tache trouvée pour cet ID.');
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return new Response(200);
    }
}