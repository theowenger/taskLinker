<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeeType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        /** @var Employee $employee */
        $employee = $options['data'];

        $builder
            ->add('lastName', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'constraints' => [
                    new Assert\Length(min:3, max: 255)
                ],
                'data' => $employee?->getLastName(),
            ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => 'Prénom',
                'constraints' => [
                    new Assert\Length(min:3, max: 255)
                ],
                'data' => $employee?->getFirstName(),
            ])
            ->add('mail', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'constraints' => [
                    new Assert\Length(min:3, max: 255)
                ],
                'data' => $employee?->getMail(),
            ])
            ->add('startDate', DateType::class, [
                'required' => true,
                'label' => "Date d'entrée",
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'data' => $employee?->getStartDate(),
            ])
            ->add('status', TextType::class, [
                'required' => true,
                'label' => 'Statut',
                'constraints' => [
                    new Assert\Length(min:3, max: 15)
                ],
                'data' => $employee?->getStatus(),
            ])
            ->add('save', SubmitType::class,  [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'button button-submit'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'employee_token',
        ]);
    }
}