<?php

namespace App\Controller\Web\Employee;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class EmployeeListController extends AbstractController
{

    public function __construct(private readonly Environment $twig,private readonly EmployeeRepository $employeeRepository)
    {

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/employees', name: "app_employee_list")]
    public function homepage(Request $request): Response
    {

        $employees = $this->employeeRepository->findAll();


        $html = $this->twig->render('misc/employee-list.html.twig',[
            "employees" => $employees
        ]);

        return new Response($html);
    }
}