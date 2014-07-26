<?php
namespace Educacity\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new UserType())
            ->add('terms', 'checkbox', array('property_path' => 'termsAccepted'));
    }

    public function getName()
    {
        return 'registration';
    }
}