<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Message;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class AccountPasswordModificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'required' => true,
                'label' => 'Current password',
            ])
            ->add('newPassword', RepeatedType::class, [
                'required' => true,
                'type' => PasswordType::class,
                'invalid_message' => "Both passwords must be identical.",
                'constraints' => [new Assert\Length(min: 6, minMessage: "The password must containt at least {{ limit }} characters.")],
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options' => ['label' => "New password"],
                'second_options' => ['label' => "New password confirmation"]
            ])
            ->add('create', SubmitType::class, [
                'label' => "Change your password",
                'row_attr' => ['class' => 'form-button'],
                'attr' => ['class' => 'btnSite']
            ]);
    }
}
