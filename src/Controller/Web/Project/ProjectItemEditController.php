<?php

namespace App\Controller\Web\Project;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/projects/{id}/edit', name: "app_project_item_edit", requirements: ["id" => "[^/]*"], defaults: ["id" => null])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(string $id = null, Request $request): Response
    {

        $errors = $request->query->get('errors');

        $employees = $this->employeeRepository->findAll();

        if($id === null) {
            $project = new Project();
        } else {
            $project = $this->projectRepository->find($id);
        }


        $form = $this->createForm(ProjectType::class, $project, [
            'employees' => $employees,
        ]);


        $html = $this->twig->render('misc/project-item-edit.html.twig', [
            "project" => $project,
            "employees" => $employees,
            "form" => $form->createView(),
            "errors" => $errors,
        ]);

        return new Response($html);
    }

}