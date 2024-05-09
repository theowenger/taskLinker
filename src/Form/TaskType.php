<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class TaskType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        /** @var Task $task */
        $task = $options['data'] ?? null;

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Titre de la tache',
                'constraints' => [
                    new Assert\Length(min:3, max: 255)
                ],
                'data' => $task?->getName() ?? '',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'data' => $task?->getDescription() ?? '',
            ])
            ->add('deadline', DateType::class, [
                'required' => false,
                'label' => "Date",
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'data' => $task?->getDeadline(),
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'To Do' => 'To Do',
                    'Doing' => 'Doing',
                    'Done' => 'Done',
                ],
            ])
            ->add('employee', ChoiceType::class, [
                'choices' => $options['employees'],
                'choice_label' => function (?Employee $employee) {
                    return $employee ? $employee->getFullName() : '';
                },
                'required' => false,
                'placeholder' => 'Aucun employé sélectionné',
                'label' => 'Sélectionner un employé',
            ])

            ->add('save', SubmitType::class,  [
                'label' => 'Modifier',
                'attr' => [
                    'class' => 'button button-submit'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'employees' => [],
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'task_token',
        ]);
    }
}