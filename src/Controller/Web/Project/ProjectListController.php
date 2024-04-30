<?php

namespace App\Controller\Web\Project;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class ProjectListController extends AbstractController
{

    public function __construct(private readonly Environment $twig, private readonly ProjectRepository $projectRepository)
    {

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/', name: "app_project_list")]
    public function homepage(Request $request): Response
    {

        $projects = $this->projectRepository->findBy(['isArchived' => false]);

        //todo: pour la creation/edition de projet, mettre l'id en optionnel

        $html = $this->twig->render('misc/project-list.html.twig', [
            "projects" => $projects
        ]);

        return new Response($html);
    }
}