<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecommendationSurveyType extends AbstractType
{
    /**
     * Formulario de encuesta para generar recomendaciones de desayuno en Brekky
     *
     * Cada campo es una pregunta que después usamos en RecommendationController::generateRecommendations()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Pregunta sobre cuánto se mueve la persona en el día
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

            // Objetivo principal que la persona tiene con el desayuno
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

            // Qué tipo de sabor prefiere para el desayuno
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

            // Si tiene alguna restricción alimentaria
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

            // Qué lugar le da al desayuno en su rutina diaria
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

            // Campo libre por si quiere agregar algún comentario extra
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
        // Este formulario no está ligado a una entidad, usamos un array simple
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
