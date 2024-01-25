<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите название.']),
                    new Assert\Length(['max' => 50, 'maxMessage' => 'Название не должно превышать {{ limit }} символов.']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание',
            ])
            ->add('address', TextType::class, [
                'label' => 'Адрес',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите адрес.']),
                    new Assert\Length(['max' => 50, 'maxMessage' => 'Адрес не должен превышать {{ limit }} символов.']),
                ],
            ])
            ->add('rating', IntegerType::class, [
                'label' => 'Рейтинг',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите рейтинг.']),
                    new Assert\Range(['min' => 1, 'max' => 5, 'minMessage' => 'Рейтинг должен быть не менее {{ limit }}.', 'maxMessage' => 'Рейтинг должен быть не более {{ limit }}.']),
                ],
            ])
            ->add('imagePath', TextType::class, [
                'label' => 'Путь к изображению',
            ])
            ->add('cuisine', TextType::class, [
                'label' => 'Кухня',
                'constraints' => [
                    new Assert\Length(['max' => 50, 'maxMessage' => 'Кухня не должна превышать {{ limit }} символов.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
