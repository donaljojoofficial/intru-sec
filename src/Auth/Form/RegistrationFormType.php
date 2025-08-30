<?php
// src/Auth/Form/RegistrationFormType.php
namespace App\Auth\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => 'First Name',
                'attr' => ['placeholder' => 'John'],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'label' => 'Last Name',
                'attr' => ['placeholder' => 'Doe'],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email Address',
                'attr' => ['placeholder' => 'you@example.com'],
            ])
            ->add('plainPassword', PasswordType::class, [
                'required' => true,
                'label' => 'Password',
                'attr' => ['placeholder' => 'Create a strong password'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a password']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'required' => true,
                'label' => 'Confirm Password',
                'mapped' => false,
                'attr' => ['placeholder' => 'Confirm your password'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to the terms.',
                    ]),
                ],
                'label' => 'I agree to the Terms of Service and Privacy Policy',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // since you use an array as form data
        ]);
    }
}

