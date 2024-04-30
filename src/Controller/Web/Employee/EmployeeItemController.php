<?php

namespace App\Controller\Web\Employee;

use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class EmployeeItemController extends AbstractController
{

    public function __construct(private readonly Environment $twig,private readonly EmployeeRepository $employeeRepository)
    {

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/employees/{id}/edit', name: "app_employee_item")]
    public function homepage(Request $request, string $id): Response
    {

        $employee = $this->employeeRepository->find($id);

        $form = $this->createForm(EmployeeType::class, $employee);


        $html = $this->twig->render('misc/employee-item.html.twig',[
            "employee" => $employee,
            "form" => $form->createView()
        ]);

        return new Response($html);
    }
}