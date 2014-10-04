<?php

namespace Bodu\SlideshowBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SlideshowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => false))
            ->add('description', 'textarea', array('required' => false))
            ->add('activBorder', 'checkbox', array('required' => false))
            ->add('borderColor', 'text', array('required' => false))
            ->add('thicknessValue', 'choice', array(
                    'required' => false,
                    'choices' => array('1' => '1px', '2' => '2px', '3' => '3px', '4' => '4px', '5' => '5px')
            ))
            ->add('thumbnailPosition', 'choice', array(
                    'required' => false,
                    'empty_value' => false,
                    'choices' => array('left' => 'Vertical gauche', 'top' => 'Horizontal haut'),
                    'expanded'  => true,
                    'multiple'  => false
            ))
            ->add('rank', 'hidden')
            ->add('section', 'entity', array(
                    'required' => false,
                    'empty_value' => '-- Aucune --',
                    'class' => 'BoduSectionBundle:Section',
                    'property' => 'name'
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bodu\SlideshowBundle\Entity\Slideshow'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bodu_slideshowbundle_slideshow';
    }
}
