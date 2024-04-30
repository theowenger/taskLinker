<?php

namespace App\Controller\Web\Project;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProjectItemEditController extends AbstractController
{

    public function __construct(private readonly Environment $twig, private readonly ProjectRepository $projectRepository, private readonly EmployeeRepository $employeeRepository)
    {

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/projects/{id}/edit', name: "app_project_item_edit")]
    public function __invoke(string $id): Response
    {

        /** @var Project $project */
        $project = $this->projectRepository->find($id);

        $employees = $this->employeeRepository->findAll();
        dump($employees);

        $form = $this->createForm(ProjectType::class, $project, [
            'employees' => $employees,
        ]);

        $html = $this->twig->render('misc/project-item-edit.html.twig', [
            "project" => $project,
            "employees" => $employees,
            "form" => $form->createView()
        ]);

        return new Response($html);
    }

}