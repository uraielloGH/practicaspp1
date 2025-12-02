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

/**
 * Formulario de alta de usuario (CU001 - Registrarse).
 * Acá definimos los campos que completa el usuario en la pantalla de registro
 * y las validaciones básicas de cada uno.
 */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Campo de nombre completo que se guarda directamente en la entidad User
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre completo',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'El nombre es obligatorio',
                    ]),
                ],
            ])

            // Campo de email del usuario, también mapeado a la entidad User
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'El email es obligatorio',
                    ]),
                ],
            ])

            // Campo de contraseña solo para el formulario.
            // No se guarda directo: luego el controlador la toma, la hashea
            // y recién ahí se setea en el User.
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
                        // Validamos que tenga por lo menos un número y un símbolo
                        'pattern' => '/^(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/',
                        'message' => 'Debe incluir al menos un número y un carácter especial.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Indicamos que este formulario trabaja contra la entidad User
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
