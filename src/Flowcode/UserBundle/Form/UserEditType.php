<?php

namespace Flowcode\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserEditType extends AbstractType
{
    /**
     * @var string
     */
    private $userGroupService;
    private $userService;

    /**
     * @param string $userGroupService The User class name
     */
    public function __construct($userGroupService, $userService)
    {
        $this->userGroupService = $userGroupService;
        $this->userService = $userService;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('username')
                ->add('plainPassword', PasswordType::class, array('required'=> false))
                ->add('email')
                ->add('status', ChoiceType::class, array(
                        'choices'  => $this->userService->getPossibleStatuses(),
                        'choices_as_values' => true,
                    ))
                ->add('groups', EntityType::class, array(
                        'class' =>  $this->userGroupService->getClass(),
                        'choice_label' => 'name',
                        'multiple' => true,
                     ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowcode\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flowcode_userbundle_user';
    }
}
