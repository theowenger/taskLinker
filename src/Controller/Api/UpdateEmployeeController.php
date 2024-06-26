<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class UpdateEmployeeController extends AbstractController
{

    public function __construct(private readonly Environment $twig,private readonly EmployeeRepository $employeeRepository)
    {

    }
    #[Route('/employee/{id}/update', name: 'api_update_employee', methods: ['POST'])]
    public function __invoke(Request $request, ValidatorInterface $validator, string $id, EntityManagerInterface $entityManager): Response
    {

        /** @var Employee $employee */
        $employee = $this->employeeRepository->find($id);

        if (!$employee) {
            throw $this->createNotFoundException('Aucun employé trouvé pour cet ID.');
        }

        $form = $this->createForm(EmployeeType::class, $employee);

        $roles = $request->request->get('employee')['roles'];

        if (is_string($roles) && !empty($roles)) {
            $roles = [$roles];
        }

        $request->request->set('employee', array_merge((array)$request->request->get('employee'), ['roles' => $roles]));


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employee->generateAvatar();
            $entityManager->persist($employee);
            $entityManager->flush();
            return $this->redirectToRoute('app_employee_list');
        }

        // Si le formulaire n'est pas valide, retourne le formulaire avec les erreurs
        return $this->render('misc/employee-item.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee
        ]);
    }
}