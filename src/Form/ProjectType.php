<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $project = null;
        if (array_key_exists('data', $options)) {
            /** @var Project $project */
            $project = $options['data'];
        }

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Titre du projet',
                'constraints' => [
                    new Assert\Length(min:3, max: 255)
                ],
                'data' => $project?->getName() ?? '',
            ])
            ->add('employees', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'fullName',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => 'Inviter des membres',
                'choices' => $options['employees'],
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
            'data_class' => Project::class,
            'employees' => [],
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'project_token',
        ]);
    }
}