<?php

namespace App\Form;

use App\Entity\Market;
use App\Entity\User;
use Doctrine\DBAL\Types\DateTimeType as TypesDateTimeType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarketFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Thème du marché',
                'required' => false
            ])
            ->add('addresse', TextType::class, [
                'label' => 'Adresse du marché',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code postale',
                'required' => false
            ])            
            ->add('createdAt', DateType::class, [
            // ->add('createdAt', DateTimeType::class, [
                'label' => 'Date de débute du marché',
                'required' => false
            ])
            ->add('updatedAt', DateType::class, [
            // ->add('updatedAt', DateTimeType::class, [
                'label' => 'Date la fin du marché',
                'required' => false
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,                
                'choice_label' => 'lastname',
                // 'choices' => $group->getUser(),
                'label' => 'Nom du commerçant',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Market::class,
        ]);
    }
}
