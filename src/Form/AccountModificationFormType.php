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

class AccountModificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'required' => true,
                'label' => 'Lastname'
            ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => 'Firstname'
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'label' => 'Address'
            ])
            ->add('town', TextType::class, [
                'required' => true,
                'label' => 'Town'
            ])
            ->add('postalCode', TextType::class, [
                'required' => true,
                'label' => 'Postal code'
            ])
            ->add('province', ChoiceType::class, [
                'required' => true,
                'label' => 'Province',
                'choices' => [
                    'Alberta' => 'AB',
                    'British Columbia' => 'BC',
                    'Manitoba' => 'MB',
                    'New Brunswick' => 'NB',
                    'Newfoundland and Labrador' => 'NL',
                    'Northwest Territories' => 'NT',
                    'Nova Scotia' => 'NS',
                    'Nunavut' => 'NU',
                    'Ontario' => 'ON',
                    'Prince Edward Island' => 'PE',
                    'Quebec' => 'QC',
                    'Saskatchewan' => 'SK',
                    'Yukon' => 'YT'
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => 'Phone number'
            ])
            ->add('create', SubmitType::class, [
                'label' => "Modify account informations",
                'row_attr' => ['class' => 'form-button'],
                'attr' => ['class' => 'btnSite']
            ]);

        $builder->get('phone')->addModelTransformer(new CallbackTransformer(
            function ($phoneFromDatabase) {
                //To view
                $newPhone = substr_replace($phoneFromDatabase, "-", 3, 0);
                $newPhone = substr_replace($phoneFromDatabase, "-", 7, 0);
                return $newPhone;
            },
            function ($phoneFromView) {
                //To databse
                return str_replace('-', '', $phoneFromView);
            }
        ));

        $builder->get('postalCode')->addModelTransformer(new CallbackTransformer(
            function ($postalCodeFromDatabase) {
                $newPostalCode = substr_replace($postalCodeFromDatabase, ' ', 3, 0);
                return $newPostalCode;
            },
            function ($postalCodeFromView) {
                return str_replace(' ', '', $postalCodeFromView);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
