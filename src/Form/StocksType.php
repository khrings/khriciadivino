<?php

namespace App\Form;

use App\Entity\Productss;
use App\Entity\Stocks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StocksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createAt', null, [
                'widget' => 'single_text'
            ])
            ->add('updateAt', null, [
                'widget' => 'single_text'
            ])
            ->add('stockChangeLog')
            ->add('productss', EntityType::class, [
                'class' => Productss::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stocks::class,
        ]);
    }
}
