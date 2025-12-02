<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'El nombre no puede estar vacío.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'El nombre debe tener al menos {{ limit }} caracteres.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'El correo electrónico no puede estar vacío.',
                    ]),
                    new Assert\Email([
                        'message' => 'Ingresá un correo electrónico válido.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,          // NO se guarda directo en la entidad User
                'required' => false,        // el usuario la puede dejar vacía
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'first_options'  => [
                    'label' => 'Nueva contraseña (opcional)',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'placeholder' => 'Dejar vacío si no querés cambiarla',
                    ],
                    'help' => 'Mínimo 8 caracteres, con mayúscula, minúscula, número y carácter especial.',
                ],
                'second_options' => [
                    'label' => 'Repetir nueva contraseña',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'placeholder' => 'Volvé a escribir la nueva contraseña',
                    ],
                ],
                'constraints' => [
                    // Permite vacío (no cambio) o contraseña fuerte (RN02)
                    new Assert\Regex([
                        'pattern' => '/^$|^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/',
                        'message' => 'La contraseña debe tener al menos 8 caracteres e incluir mayúscula, minúscula, número y un carácter especial.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
