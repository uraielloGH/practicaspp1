<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecommendationSurveyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('activityLevel', ChoiceType::class, [
                'label' => 'Nivel de actividad física',
                'choices' => [
                    'Baja (poco movimiento diario)' => 'baja',
                    'Media (camino, me muevo algo)' => 'media',
                    'Alta (entreno regularmente)' => 'alta',
                ],
                'placeholder' => 'Seleccioná una opción',
                'required' => true,
            ])
            ->add('mainGoal', ChoiceType::class, [
                'label' => 'Objetivo principal',
                'choices' => [
                    'Tener más energía' => 'energia',
                    'Cuidar o bajar de peso' => 'peso',
                    'Mejorar la digestión' => 'digestion',
                ],
                'placeholder' => 'Seleccioná una opción',
                'required' => true,
            ])
            ->add('flavorPreference', ChoiceType::class, [
                'label' => 'Preferencia de sabor',
                'choices' => [
                    'Dulce' => 'dulce',
                    'Salado' => 'salado',
                    'Mixto' => 'mixto',
                ],
                'placeholder' => 'Seleccioná una opción',
                'required' => true,
            ])
            ->add('restriction', ChoiceType::class, [
                'label' => 'Restricciones alimentarias',
                'choices' => [
                    'Sin restricciones' => 'ninguna',
                    'Vegetariano' => 'vegetariano',
                    'Vegano' => 'vegano',
                ],
                'placeholder' => 'Seleccioná una opción',
                'required' => true,
            ])
            ->add('breakfastImportance', ChoiceType::class, [
                'label' => 'Qué tan importante es el desayuno para vos',
                'choices' => [
                    'Poca (casi no desayuno)' => 'baja',
                    'Media (a veces sí, a veces no)' => 'media',
                    'Mucha (siempre desayuno)' => 'alta',
                ],
                'placeholder' => 'Seleccioná una opción',
                'required' => true,
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Comentarios adicionales (opcional)',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                    'placeholder' => 'Algo que quieras aclarar (horarios, gustos, etc.)',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
