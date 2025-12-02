<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre completo',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'El nombre es obligatorio',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'El email es obligatorio',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'label' => 'Contraseña',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mínimo 8 caracteres, número y carácter especial',
                ],
                'help' => 'Debe tener al menos 8 caracteres, incluir un número y un carácter especial.',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La contraseña no puede estar vacía.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres.',
                    ]),
                    new Assert\Regex([
                        // al menos un dígito y al menos un carácter no alfanumérico
                        'pattern' => '/^(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/',
                        'message' => 'Debe incluir al menos un número y un carácter especial.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

