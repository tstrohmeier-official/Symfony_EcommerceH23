<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', TypeTextType::class, [
            'label' => false,
            'required' => true,
            'empty_data' => "",
            'constraints' => [
            new Assert\NotBlank(message: 'Field cannot be empty.'),
            new Assert\Length(min: 3, minMessage: "The category must be {{ limit }} characters minimum."), 
            new Assert\Length(max: 30, maxMessage: "The category must be {{ limit }} characters maximum.")],
            'attr' => ['class' => 'input-field']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
