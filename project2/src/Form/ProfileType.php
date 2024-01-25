<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите адрес электронной почты']),
                    new Assert\Email(['message' => 'Адрес электронной почты "{{ value }}" недействителен.']),
                ],
            ])
            ->add('username', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите имя пользователя']),
                    new Assert\Length([
                        'min' => 5,
                        'max' => 30,
                        'minMessage' => 'Ваше имя пользователя должно содержать не менее {{ limit }} символов',
                        'maxMessage' => 'Ваше имя пользователя должно содержать не более {{ limit }} символов',
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите номер телефона']),
                    new Assert\Regex([
                        'pattern' => '/^\d{11}$/',
                        'message' => 'Номер телефона должен состоять из 11 цифр',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Пожалуйста, введите пароль']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Ваш пароль должен содержать не менее {{ limit }} символов',
                        'max' => 4096,
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
