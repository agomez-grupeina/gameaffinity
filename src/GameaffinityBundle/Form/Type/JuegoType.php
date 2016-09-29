<?php

namespace GameaffinityBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JuegoType
 *
 * @author agomez
 */
class JuegoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nombre', TextType::class)
                ->add('fechaLanzamiento', DateType::class, array(
                    'input' => 'datetime', //indicamos si le entra un datetime o un timestamp
                    'widget' => 'choice',
                    'years' => array(
                        '1980', '1981', '1982', '1983', '1984', '1985', '1986', '1987', '1988',
                        '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997',
                        '1998', '1999', '2000', '2001', '2002', '2003', '2004', '2005', '2006',
                        '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015',
                        '2016') //Por defecto solo salen 5 anteriores y posteriores
                ))
                ->add('plataformas', EntityType::class, array(
                    'class' => 'GameaffinityBundle:Plataforma',
                    'expanded' => true,
                    'multiple' => true,
                ))
                ->add('generos', EntityType::class, array(
                    'class' => 'GameaffinityBundle:Genero',
                    'expanded' => false,
                    'multiple' => true
                ))
                ->add('desarrolladora', EntityType::class, array(
                    'class' => 'GameaffinityBundle:Desarrolladora',
                    'expanded' => true,
                    'multiple' => false
                ))
                ->add('portada', FileType::class, array(
                    'attr' => array(
                        'accept' => 'image/*',
                    ),
                    'data_class' => null, //Esto nos permite recuperar el objeto al editar y que no salte error de que necesita que sea tipo file y ha encontrado string
                    'required' => false
                        )
                )
                ->add('save', SubmitType::class, array(
                    'label' => 'Guardar',
                    'attr' => array('class' => 'btn btn-primary')
                ))
        ;
    }

    //Indicamos a symfony la entity relacionada con este form
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'GameaffinityBundle\Entity\Juego',
        ));
    }

}
