<?php

namespace Flowcode\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserProfileType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('firstname')
                ->add('lastname')
                ->add('username')
                ->add('plainPassword','password', array('required'=> false))
                ->add('email')
                ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Flowcode\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'flowcode_userbundle_user_profile';
    }

}
