<?php

namespace GameaffinityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Description of NoticiaType
 *
 * @author agomez
 */
class UsuarioType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('nombre', TextType::class)


//                ->add('save', SubmitType::class, array(
//                    'label' => 'Registrar',
//                    'attr' => array('class' => 'btn btn-primary'),
//                ))

        ;
        //Añadimos un event listener para agregar el campo select de role si estamos editando
        //(accion que solo se podra realizar desde el backend mediante role_admin)
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $usuario = $event->getData();
            $form = $event->getForm();

            // Comprobamos si usuario es nuevo o si es recuperado de bd
            // If no data is passed to the form, the data is "null".
            // This should be considered a new "Usuario"
            if (!$usuario || null === $usuario->getId()) {
                //Si el usuario es nuevo, añadimos el campo de doble password y el de email
                $form->add('email', EmailType::class);
                $form->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repetir Password')
                ));
            } else {
                //Si el usuario no es nuevo, significa que estamos editando (accion que solo el admin 
                //puede realizar. Por lo tanto, añadimos los campos que solo el admin debería ver
                //$form->add('password', TextType::class);

                /* PENDIENTE Convertir UsuarioType en un service para permitirme ver los permisos del user que 
                 *  visualiza el formulario y así filtrar a continuación:
                 * 1- Si el user es admin, mostramos el select para modificar roles
                 * 2- Si es user, simplemente mostramos un repeatType de password por si quiere cambiar el pwd */
                        
                
                
                
                //Si el usuario que edita es un admin le permitimos modificar roles               
                $form->add('role', EntityType::class, array(
                    'class' => 'GameaffinityBundle:Role'
                ));
            }

            //Añadimos el submit (si lo añadimos arriba en el builder aparece
            //encima del campo que añadimos en vez de aparecer abajo del todo del
            //formulario
            $form->add('save', SubmitType::class, array(
                'label' => 'Enviar',
                'attr' => array('class' => 'btn btn-primary'),
            ));
        });
    }

    //Indicamos a symfony la entity relacionada con este form
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'GameaffinityBundle\Entity\Usuario',
        ));
    }

}
