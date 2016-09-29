<?php

namespace GameaffinityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Description of NoticiaType
 *
 * @author agomez
 */
class NoticiaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titulo', TextType::class)
                ->add('texto', TextareaType::class, array(
                    'attr' => array(
                        'resize' => 'vertical'//Not working the resize vertical
                    )
                ))
                ->add('imagen', FileType::class, array(
                    'attr' => array(
                        'accept' => 'image/*',                      
                        ),
                    'data_class' => null,//Esto nos permite recuperar el objeto al editar y que no salte error de que necesita que sea tipo file y ha encontrado string
                    'required' => false
                    )
                )
                
                ->add('save', SubmitType::class, array(
                    'label' => 'Publicar',
                    'attr' => array('class' => 'btn btn-primary')
                ))
        ;
    }

    //Indicamos a symfony la entity relacionada con este form
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'GameaffinityBundle\Entity\Noticia',
        ));
    }

}
