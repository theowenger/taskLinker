<?php

namespace App\Controller\Web\Project;

use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class ProjectListController extends AbstractController
{

    public function __construct(private readonly Environment $twig, private readonly ProjectRepository $projectRepository, private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/', name: "app_project_list")]
    #[IsGranted('ROLE_USER')]
    public function homepage(Request $request): Response
    {
        $user = $this->security->getUser();

        if(in_array('ROLE_ADMIN', $user->getRoles(), true)){
            $projects = $this->projectRepository->findBy(['isArchived' => false]);
        } else {
            $query = $this->entityManager->createQueryBuilder()
                ->select('p')
                ->from('App\Entity\Project', 'p')
                ->where(':user MEMBER OF p.employees')
                ->andWhere('p.isArchived = :isArchived')
                ->setParameter('user', $user)
                ->setParameter('isArchived', false)
                ->getQuery();

            $projects = $query->getResult();
        }

        $html = $this->twig->render('misc/project-list.html.twig', [
            "projects" => $projects
        ]);

        return new Response($html);
    }
}