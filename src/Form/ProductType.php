<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
            'required' => true,])
            ->add('price', MoneyType::class, [
                'required' => true,
                'label' => 'Price',
                'currency' => 'CAD'])
            ->add('quantityInStock', NumberType::class, [
                'required' => true,
                'label' => 'Quantity in stock',])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Description',])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Image',
                'constraints' => [
                    new Assert\Length(max:1024),
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Téléverser une image valide.'
                    ])]
            ])
            ->add('mainCategory', EntityType::class, [
                'required' => true,
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'category',
            ])
            ->add('create', SubmitType::class, [
                'row_attr' => ['class' => 'form-button'],
                'attr' => ['class' => 'btnSite']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
