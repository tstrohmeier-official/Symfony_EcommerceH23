<?php

namespace App\Form;

use App\Entity\Constants;
use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('state', ChoiceType::class, [
                'required' => true,
                'label' => 'Available states: ',
                'empty_data' => Constants::STATE_TO_COME,
                'placeholder' => 'Selection',
                'choices' => [
                    Constants::STATE_DELIVERED => 'Delivered',
                    Constants::STATE_PREPARING => 'In preperation',
                    Constants::STATE_TRANSIT => 'In transit',
                    Constants::STATE_SENT => 'Sent'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
