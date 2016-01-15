<?php

namespace Flowcode\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('firstname')
                ->add('lastname')
                ->add('username')
                ->add('plainPassword','password')
                ->add('email')
                ->add('status', ChoiceType::class, array(
                        'choices'  => array(
                            'Inactive' => 0,
                            'Active' => 1
                        ),
                        'choices_as_values' => true,
                    ))
                ->add('groups', 'entity', array(
                        'class' => 'AmulenUserBundle:UserGroup',
                        'property' => 'name',
                        'multiple' => true,
                     ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Amulen\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'flowcode_userbundle_user';
    }

}
