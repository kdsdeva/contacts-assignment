<?php

namespace App\Form;

use App\Entity\Contacts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',ChoiceType::class,[
                'choices'=>[
                    'Mr' => 'Mr',
                    'Mrs' => 'Mrs',
                    'Miss' => 'Miss'
                ]
            ])
            ->add('firstname',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Firstname',
                    ]),
                ]
            ])
            ->add('lastname',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Lastname',
                    ]),
                ]
            ])
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Email',
                    ]),
                ]
            ])
            ->add('mobileNumber',NumberType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Mobile Number',
                    ]),
                    new Length([
                        'max'=>15,
                        'maxMessage'=>'Maximum 15 numbers only allowed',
                        'min'=>10,
                        'minMessage'=>'Minimum 10 numbers is required',
                    ])
                ],
            ])
            ->add('address',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Address',
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contacts::class,
        ]);
    }
}
