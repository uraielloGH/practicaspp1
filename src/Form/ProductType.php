<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Categoría a la que pertenece el producto
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Seleccioná una categoría',
                'label' => 'Categoría',
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Seleccioná una categoría para el producto.',
                    ]),
                ],
            ])

            // Nombre comercial del producto
            ->add('name', TextType::class, [
                'label' => 'Nombre del producto',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'El nombre no puede estar vacío.',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'El nombre debe tener al menos {{ limit }} caracteres.',
                    ]),
                ],
            ])

            // Descripción breve para mostrar en el catálogo
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La descripción no puede estar vacía.',
                    ]),
                ],
                'attr' => [
                    'rows' => 4,
                ],
            ])

            // Precio del producto en ARS
            ->add('price', MoneyType::class, [
                'label' => 'Precio',
                'currency' => 'ARS',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'El precio es obligatorio.',
                    ]),
                    new Assert\Positive([
                        'message' => 'El precio debe ser un valor positivo.',
                    ]),
                ],
            ])

            // Nombre del archivo de imagen dentro de /public/images
            ->add('image', TextType::class, [
                'label' => 'Nombre de la imagen (opcional)',
                'required' => false,
                'help' => 'Archivo dentro de /public/images, por ejemplo: cafe1.jpg',
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^$|^.+\.(jpg|jpeg|png|webp)$/i',
                        'message' => 'La imagen debe terminar en .jpg, .jpeg, .png o .webp.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // El formulario se vincula directamente con la entidad Product
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
