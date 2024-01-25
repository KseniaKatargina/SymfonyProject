<?php

namespace App\Form;

use App\Entity\MenuItem;
use App\Entity\Restaurant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MenuItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите имя.']),
                    new Assert\Length(['max' => 255, 'maxMessage' => 'Имя не должно превышать {{ limit }} символов.']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите описание.']),
                    new Assert\Length(['max' => 1000, 'maxMessage' => 'Описание не должно превышать {{ limit }} символов.']),
                ],
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Цена',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите цену.']),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Цена должна быть числом.']),
                ],
            ])
            ->add('imagePath', TextType::class, [
                'label' => 'ImagePath',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите путь к изображению.']),
                    new Assert\Length(['max' => 255, 'maxMessage' => 'Путь к изображению не должен превышать {{ limit }} символов.']),
                ],
            ])
            ->add('restaurant', EntityType::class, [
                'class' => Restaurant::class,
                'choice_label' => 'name',
                'label' => 'Ресторан',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, выберите ресторан.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MenuItem::class,
        ]);
    }
}