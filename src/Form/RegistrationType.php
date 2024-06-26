<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {

        $builder
            ->add('lastName', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'constraints' => [
                    new Assert\Length(min:3, max: 255)
                ],
                'label_attr' => [
                    'class' => 'text-white',
                ],
            ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => 'Prénom',
                'constraints' => [
                    new Assert\Length(min:3, max: 255)
                ],
                'label_attr' => [
                    'class' => 'text-white',
                ],
            ])
            ->add('mail', EmailType::class, [
                'required' => true,
                'label' => 'E-mail',
                'constraints' => [
                    new Assert\Length(min:3, max: 255)
                ],
                'label_attr' => [
                    'class' => 'text-white',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe', 'label_attr' => ['class' => 'text-white']],
                'second_options' => ['label' => 'Confirmation Mot de passe', 'label_attr' => ['class' => 'text-white']],
            ])
            ->add('save', SubmitType::class,  [
                'label' => 'Continuer',
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