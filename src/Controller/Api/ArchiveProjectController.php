<?php

namespace App\Controller\Api;

use App\Entity\Project;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class ArchiveProjectController extends AbstractController
{

    public function __construct(private readonly Environment $twig, private readonly ProjectRepository $projectRepository, private readonly EntityManagerInterface $entityManager)
    {

    }
    #[Route('/project/{id}/archive', name: 'api_archive_project', methods: ['POST'])]
    public function __invoke(Request $request, ValidatorInterface $validator, string $id): Response
    {

        /** @var Project $project */
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Aucun projet trouvé pour cet ID.');
        }

        if($project->isArchived() === true){
            throw $this->createAccessDeniedException("Ce projet est déjà archivé");
        }

        $project->setIsArchived(true);

        $this->entityManager->persist($project);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_project_list');

    }
}