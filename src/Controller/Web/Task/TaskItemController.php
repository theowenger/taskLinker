<?php

namespace App\Controller\Web\Task;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TaskItemController extends AbstractController
{

    public function __construct(
        private readonly Environment    $twig,
        private readonly TaskRepository $taskRepository,
        private readonly ProjectRepository $projectRepository,
    )
    {

    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Route('/project/{projectId}/tasks/{id}', name: 'app_task_item')]
    public function __invoke( Request $request, string $projectId, string $id = null): Response
    {
        if ($id === null) {
            $status = $request->query->get('status');
            $project = $this->projectRepository->find($projectId);
            $task = new Task();
            $task->setProject($project)
            ->setName("")
                ->setDescription('')
                ->setStatus($status)
                ->setDeadline(new \DateTime())
                ->setStartDate(new \DateTimeImmutable("now"))
            ;

        } else {
            $task = $this->taskRepository->find($id);
            $project = $task->getProject();

        }
        $employees = $project->getEmployees();


        $form = $this->createForm(TaskType::class, $task, [
            'employees' => $employees,
        ]);


        $html = $this->twig->render('misc/task-item.html.twig', [
            "task" => $task,
            "project" => $project,
            "form" => $form->createView()
        ]);

        return new Response($html);
    }

}