<?php

namespace App\Form;

use App\Entity\Dish;
use App\Controller\AdminController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class DishFormType extends AbstractType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('Calories', ChoiceType::class, [
                'choices' => $this->_availableCalories()
            ])
            ->add('price')
            ->add('Image', TextType::class , ['empty_data' => 'http://via.placeholder.com/360x225'])
            ->add('Description' )
            ->add('Sticky')
            ->add('Category', EntityType::class, array(
                'class' => 'App\Entity\Category',
                'choice_label' => 'name',

                'multiple' => false
            ))
            ->add('User', EntityType::class, array(
                'class' => 'App\Entity\User',
                'choice_label' => 'username',

                'multiple' => false
            ))
            ->add('allergen', EntityType::class, array(
                'class' => 'App\Entity\Allergen',
                'choice_label' => 'name',

                'multiple' => true
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dish::class,
        ]);
    }

    function _availableCalories()
    {
        $calories = array ();
        for ( $i = 10 ; $i <= 300 ; $i += 10 )
            $calories [ $i ]= $i ;
        return $calories ;
    } 
}
