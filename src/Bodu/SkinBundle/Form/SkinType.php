<?php

namespace Bodu\SkinBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SkinType extends AbstractType
{
    const SKIN_FOLDER_PATH = 'resources/css/skin';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => false))
            ->add('cssFile', 'choice', array(
                    'required' => false,
                    'empty_value' => '-- Aucun --',
                    'choices' => $this->getFileList()))
            ->add('activ', 'checkbox', array('required' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bodu\SkinBundle\Entity\Skin'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bodu_skinbundle_skin';
    }

    /**
     * Allows to get all files in skin folder (define throug constant SKIN_FOLDER_PATH), i.e. all skin files.
     * @return array
     */
    public function getFileList() {

        $fileList = scandir(self::SKIN_FOLDER_PATH);

        foreach ($fileList as $file) {

            if (!is_dir($file))
                $result[self::SKIN_FOLDER_PATH . '/' . $file] = $file;
        }

        return $result;
    }
}
