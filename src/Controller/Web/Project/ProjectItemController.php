<?php

namespace App\Controller\Web\Project;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProjectItemController extends AbstractController
{

    public function __construct(private readonly Environment $twig, private readonly ProjectRepository $projectRepository, private readonly EntityManagerInterface $entityManager)
    {

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/projects/{id}', name: "app_project_item")]
    public function __invoke(string $id = null): Response
    {

        if($id === null) {
            $project = new Project();

            $project->setName('Nouveau Projet');

            $this->entityManager->persist($project);
            $this->entityManager->flush();

            $form = $this->createForm(ProjectType::class, $project, [
                'employees' => null,
            ]);


            $html = $this->twig->render('misc/project-item-edit.html.twig', [
                "project" => $project,
                "employees" => null,
                "form" => $form->createView()
            ]);

            return new Response($html);
        }

        /** @var Project $project */
        $project = $this->projectRepository->find($id);

        $project->getEmployees()->initialize();

        $employeesProject = $project->getEmployees();

        $html = $this->twig->render('misc/project-item.html.twig', [
            "project" => $project,
            "employees" => $employeesProject,
        ]);

        return new Response($html);
    }

}