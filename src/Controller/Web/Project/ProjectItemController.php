<?php

namespace App\Controller\Web\Project;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
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
    #[IsGranted('ROLE_USER')]
    public function __invoke(string $id = null, Security $security): Response
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

        $user = $security->getUser();

        /** @var Project $project */
        $project = $this->projectRepository->find($id);

        $project->getEmployees()->initialize();
        $project->getTasks()->initialize();

        $employeesProject = $project->getEmployees();
        $tasksProject = $project->getTasks();
        dump($user->getRoles());

        if(!$employeesProject->contains($user) && !in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return $this->redirectToRoute('app_project_list');
        }

        $html = $this->twig->render('misc/project-item.html.twig', [
            "project" => $project,
            "employees" => $employeesProject,
            "tasks" => $tasksProject,
        ]);

        return new Response($html);
    }

}