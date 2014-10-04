<?php

namespace Bodu\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
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
            ->add('date', 'datetime', array(
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'required' => false,
                    'invalid_message' => 'La date saisie n\'est pas valide'
            ))
            ->add('link', 'text', array('required' => false))
            ->add('size', 'choice', array(
                    'choices' => array('large' => 'Pleine largeur', 'small' => 'Taille réduite'),
                    'expanded'  => true,
                    'multiple'  => false,
                    'required' => false,
                    'empty_value' => false
            ))
            ->add('published', 'checkbox', array('required' => false))
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
            'data_class' => 'Bodu\NewsBundle\Entity\News'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bodu_newsbundle_news';
    }
}
