<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*
            ->add('compteType',ChoiceType::class,array(
            'choices' => array(
                'Promoteur' => 'promoteur',
                'FieldForce' => 'fieldforce'
            )
            ))
            ->add('Statut',ChoiceType::class,array(
                'choices' => array(
                    'Valid' => 'Valid',
                    'Non Valid' => 'Non Valid'
                )
            ))
            */
            ->add('username')
            ->add('email')
            ->add('password')


        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
