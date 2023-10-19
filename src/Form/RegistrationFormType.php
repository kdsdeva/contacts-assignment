<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Username',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Username'
                ]
            ])
            ->add('firstname',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Firstname',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Firstname'
                ]
            ])
            ->add('lastname',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a lastname',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Lastname'
                ]
            ])
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a email address.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('plainPassword', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'max' => 4096,
                        ]),
                    ],
                    'invalid_message' => 'The new password fields must match.',
                    'first_options' => [
                        'attr' => [
                            'placeholder' => 'Password'
                        ]
                    ],
                    'second_options' => [
                        'attr' => [
                            'placeholder' => 'Repeat Password'
                        ]
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
