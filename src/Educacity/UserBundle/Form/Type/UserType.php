<?php
namespace Educacity\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'repeated', array(
            'type' => 'email',
            'first_options' => array('attr' => array('placeholder' => 'Email')),
            'second_options' => array('attr' => array('placeholder' => 'Confirmar email')),
            'error_bubbling' => 'true'))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'first_options' => array('attr' => array('placeholder' => 'Password')),
                'second_options' => array('attr' => array('placeholder' => 'Confirmar password')),
                'error_bubbling' => 'true'));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Educacity\UserBundle\Entity\User',
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Educacity\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}