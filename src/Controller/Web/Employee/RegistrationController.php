<?php

namespace App\Controller\Web\Employee;

use App\Entity\Employee;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'employee_registration')]
    public function __invoke(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {

        $employee = new Employee();

        $form = $this->createForm(RegistrationType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get("password")->getData();
            $password = $passwordHasher->hashPassword($employee, $password);

            $employee
                ->setRoles(['ROLE_USER'])
                ->setPassword($password)
                ->setStatus("CDI")
                ->setStartDate(new \DateTime())
                ->generateAvatar()
            ;
            $entityManager->persist($employee);
            $entityManager->flush();
            return $this->redirectToRoute('app_employee_list');
        }

        return $this->render('misc/registration-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}