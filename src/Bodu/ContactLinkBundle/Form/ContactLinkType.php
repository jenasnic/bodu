<?php

namespace Bodu\ContactLinkBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactLinkType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('required' => false))
            ->add('content', 'textarea', array('required' => false))
            ->add('link', 'text', array('required' => false))
            ->add('pictureUrl', 'hidden', array('required' => false))
            ->add('rank', 'hidden')
            ->add('pictureFile', 'file', array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bodu\ContactLinkBundle\Entity\ContactLink'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bodu_contactlinkbundle_contactlink';
    }
}
