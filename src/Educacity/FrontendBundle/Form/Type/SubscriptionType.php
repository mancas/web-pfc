<?php
namespace Educacity\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array('required' => true));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Educacity\UserBundle\Entity\Subscription',
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Educacity\UserBundle\Entity\Subscription'
        ));
    }

    public function getName()
    {
        return 'subscription';
    }
}