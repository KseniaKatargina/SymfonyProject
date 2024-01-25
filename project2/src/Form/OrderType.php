<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите город']),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'Город не должен превышать {{ limit }} символов',
                    ]),
                ],
            ])
            ->add('street', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите улицу']),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'Улица не должна превышать {{ limit }} символов',
                    ]),
                ],
            ])
            ->add('house', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите номер дома']),
                    new Assert\Type([
                        'type' => 'digit',
                        'message' => 'Номер дома должен быть числом',
                    ]),
                ],
            ])
            ->add('apartment', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите номер квартиры']),
                    new Assert\Type([
                        'type' => 'digit',
                        'message' => 'Номер квартиры должен быть числом',
                    ]),
                ],
            ])
            ->add('comment', TextareaType::class, ['required' => false])
            ->add('cardNumber', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите номер карты']),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{16}$/',
                        'message' => 'Номер карты должен состоять из 16 цифр',
                    ]),
                ],
            ])
            ->add('expirationDate', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'ММ/ГГ'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите срок действия']),
                    new Assert\Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/[0-9]{2}$/',
                        'message' => 'Неверный формат срока действия',
                    ]),
                ],
            ])
            ->add('cvc', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите CVC']),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{3}$/',
                        'message' => 'CVC должен состоять из 3 цифр',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}