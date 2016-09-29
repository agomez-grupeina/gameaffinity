<?php

namespace GameaffinityBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GameaffinityBundle\Entity\Noticia;
use GameaffinityBundle\Entity\Usuario;
use GameaffinityBundle\Entity\Plataforma;
use GameaffinityBundle\Entity\Genero;
use GameaffinityBundle\Entity\Juego;
use GameaffinityBundle\Form\Type\NoticiaType;
use GameaffinityBundle\Form\Type\UsuarioType;
use GameaffinityBundle\Form\Type\JuegoType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\File\File;

class BackController extends Controller {

    //Mostrar la portada del backend de administracion
    public function adminIndexAction() {
        return $this->render('GameaffinityBundle:Back:backIndex.html.twig');

//        return new \Symfony\Component\HttpFoundation\Response($content = "this->container->getParameter('kernel.root_dir') --->  " . $this->container->getParameter('kernel.root_dir'));
    }

    public function noticiasAction() {

        $em = $this->getDoctrine()->getManager();
        $noticias = $em->getRepository('GameaffinityBundle:Noticia')->findAll();


        return $this->render('GameaffinityBundle:Back:backNoticias.html.twig', $data = array('noticias' => $noticias));
    }

    public function introducirNoticiaAction(Request $request) {
        $form = $this->createForm(NoticiaType::class, null, array(
            'action' => $this->generateUrl('form_noticias'),
            'method' => 'POST'
        ));

        //Indicamos al controller que el form se va a encargar de manejar el request
        $form->handleRequest($request);

        //Si se ha enviado el formulario:
        if ($form->isSubmitted() && $form->isValid()) {
            //accion a realizar cuando el form esta enviado y los datos son validos
            $data = $form->getData();

            $noticia = new Noticia();
            $noticia->setTitulo($data->getTitulo());
            $noticia->setAutor($this->get('security.token_storage')->getToken()->getUser());
            $noticia->setSlug();
            $noticia->setTexto($data->getTexto());

            //Comprobacion si imagen viene vacía
            $img_file = $data->getImagen();

            if ($img_file != null) {
                // Generate a unique name for the file before saving it
                $img_name = md5(\uniqid()) . '.' . $img_file->guessExtension();

                //Move file
                $targetDir = $this->container->getParameter('kernel.root_dir') . $this->container->getParameter('news_img_dir');
                $img_file->move($targetDir, $img_name);

                $noticia->setImagen($img_name);
            }


            //persistir noticia
            $em = $this->getDoctrine()->getManager();
            $em->persist($noticia);
            $em->flush();

            return $this->redirect($this->generateUrl('back_noticias'));
        }
        //Si no se ha enviado el formulario, simplemente lo mostramos
        return $this->render('GameaffinityBundle:Back:formularioNoticia.html.twig', array(
                    'formulario' => $form->createView(),
        ));
    }

    public function editarNoticiaAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $noticia = $em->getRepository('GameaffinityBundle:Noticia')->find($id);
        $old_img_name = $noticia->getImagen();


        //Intento fallido de repoblar el file input con la imagen actual, por lo que he leido es imposible
        ////(yo he intentado crear el file y setear antes de mostrar el form, pero no ha servido
//        $img_file = new File($this->getParameter('news_img_dir') . $noticia->getImagen());
//        $noticia->setImagen($img_file);

        $form = $this->createForm(NoticiaType::class, $noticia, array(
            'action' => $this->generateUrl('edit_noticias', array(
                'id' => $id
            )),
            'method' => 'POST'
        ));


        //Indicamos al controller que el form se va a encargar de manejar el request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $noticia->setTitulo($data->getTitulo());
            $noticia->setTexto($data->getTexto());

            //Si el usuario mete imagen, la sustituimos por la anterior
            if ($data->getImagen() != null) {
//                dump($data->getImagen());
//                die();
                //Eliminamos anterior
                unlink($this->container->getParameter('kernel.root_dir') . $this->container->getParameter('news_img_dir') . $old_img_name);
                //Reemplazamos con la nueva
                // Generate a unique name for the file before saving it
                $img_name = md5(\uniqid()) . '.' . $data->getImagen()->guessExtension();

                //Move file
                $targetDir = $this->container->getParameter('kernel.root_dir') . $this->container->getParameter('news_img_dir');
                $data->getImagen()->move($targetDir, $img_name);

                $noticia->setImagen($img_name);
            } else {
                //Si la imagen es null, dejamos la misma que había antes (debería quedarse sola 
                //automáticamente dado que no hacemos el setImagen() a menos que nos entre una 
                //imagen con el formulario. Sin embargo, es posible que el data_class => null 
                //de NoticiaType en el input de imagen haga que sea necesario este setImagen
                //En fin, el caso es que sin esta línea la imagen si no se modifica se queda vacía en BD
                $noticia->setImagen($old_img_name);
            }

            //Persist changes
            $em->persist($noticia);
            $em->flush();

            //Redirect
            return $this->redirect($this->generateUrl('back_noticias'));
        }
        //Render formulario
        return $this->render('GameaffinityBundle:Back:formularioNoticia.html.twig', array(
                    'formulario' => $form->createView(),
                    'edit' => true
        ));
    }

    public function usuariosAction() {
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('GameaffinityBundle:Usuario')->findAll();


        return $this->render('GameaffinityBundle:Back:backUsuarios.html.twig', $data = array('usuarios' => $usuarios));
    }

    public function introducirUsuarioAction(Request $request) {
        $form = $this->createForm(UsuarioType::class, null, array(
            'action' => $this->generateUrl('form_usuarios'),
            'method' => 'POST'
        ));

        //Indicamos al controller que el form se va a encargar de manejar el request
        $form->handleRequest($request);

        //Si se ha enviado el formulario:
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            //Construimos objeto usuario
            $usuario = new Usuario();
            $usuario->setNombre($data->getNombre());
            $usuario->setEmail($data->getEmail());

            //Codificar password
            $plainPassword = $data->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($usuario, $plainPassword);

            $usuario->setPassword($encoded);

            //En caso de que no nos entre role del form (significa que es nuevo usuario y por lo tanto
            //va con el role por defecto
            if ($data->getRole() === null) {
                //Seteamos role default
                $em = $this->getDoctrine()->getManager();
                $role = $em->getRepository('GameaffinityBundle:Role')->findOneBy(array(
                    'nombre' => 'ROLE_USER'
                ));
                $usuario->setRole($role);
            } else {
                //Seteamos role del formulario
                $usuario->setRole($data->getRole());
            }

            //Persistimos usuario
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            //redirect after form success
            return $this->redirect($this->generateUrl('back_usuarios'));
        }
        //render before form shown (not submitted yet)
        return $this->render('GameaffinityBundle:Front:registerForm.html.twig', array(
                    'formulario' => $form->createView()
        ));
    }

    public function editarUsuarioAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('GameaffinityBundle:Usuario')->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('No existe el usuario que intentas editar');
        }

        $form = $this->createForm(UsuarioType::class, $usuario, array(
            'action' => $this->generateUrl('edit_usuarios', array(
                'id' => $id
            )),
            'method' => 'POST'
        ));

        //Indicamos al controller que el form se va a encargar de manejar el request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            //Setear usuario con los campos del formulario (no muestro email al editar)
            $usuario->setNombre($data->getNombre());
            $usuario->setRole($data->getRole());

            //Persistir usuario
            $em->persist($usuario);
            $em->flush();

            //Redirect response
            return $this->redirect($this->generateUrl('back_usuarios'));
        }
        //Render response (form not sent yet)
        return $this->render('GameaffinityBundle:Front:registerForm.html.twig', array(
                    'formulario' => $form->createView()
        ));
    }

    //Mostramos el index de la pagina de admin desde donde se pueden administrar
    //generos y plataformas
    public function generoPlataformaAction() {
        $em = $this->getDoctrine()->getManager();
        $generos = $em->getRepository('GameaffinityBundle:Genero')->findAll();
        $plataformas = $em->getRepository('GameaffinityBundle:Plataforma')->findAll();

        return $this->render('GameaffinityBundle:Back:backGenerosPlataformas.html.twig', array(
                    'generos' => $generos,
                    'plataformas' => $plataformas
        ));
    }

    //Action del formulario insertar plataforma
    public function insertarPlataformaAction(Request $request) {
        //Recoger datos formulario
        $nombre = $request->get('nombre');
        $fecha_lanzamiento = $request->get('dia') . "-" . $request->get('mes') . "-" . $request->get('anyo');

        $em = $this->getDoctrine()->getManager();

        //Convertimos el string de fecha del formulario en un objeto datetime
        $date = new \DateTime($fecha_lanzamiento);

        $plataforma = new Plataforma();
        $plataforma->setNombre($nombre);
        $plataforma->setFechaLanzamiento($date);
        $plataforma->setSlug($nombre);

        $em->persist($plataforma);
        $em->flush();

        return $this->redirect($this->generateUrl('back_genero_plataformas'));
    }

    //Action del formulario insertar plataforma
    public function insertarGeneroAction(Request $request) {
        //Recoger datos formulario
        $nombre = $request->get('nombre');

        $em = $this->getDoctrine()->getManager();

        $genero = new Genero();
        $genero->setNombre($nombre);
        $genero->setSlug();

        $em->persist($genero);
        $em->flush();

        return $this->redirect($this->generateUrl('back_genero_plataformas'));
    }

    //Action para editar plataformas 
    public function editarPlataformaAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GameaffinityBundle:Plataforma')->find($id);


        //Si el formulario está enviado:
        if ($request->request->get('nombre') !== null) {

            //Comprobacion valores validos (en este caso, ya está todo controlado por html min, max values en los inputs de fecha
            //y requireds para que no puedan quedarse los campos vacíos
            //Persistir cambios
            $entity->setNombre($request->get('nombre'));
            $entity->setFechaLanzamiento(new \DateTime($request->get('dia') . "-" . $request->get('mes') . "-" . $request->get('anyo')));
            $entity->setSlug($entity->getNombre());

            $em->persist($entity);
            $em->flush();

            //Redirigir
            return $this->redirect($this->generateUrl('back_genero_plataformas'));
        }

        //Si no, mostramos el formulario
        return $this->render('GameaffinityBundle:Back:formularioEditarPlataformaGenero.html.twig', array(
                    'editando' => 'Plataforma',
                    'entity' => $entity
        ));
    }

    public function editarGeneroAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GameaffinityBundle:Genero')->find($id);

        //Si el formulario está enviado:
        if ($request->request->get('nombre') !== null) {

            //Seteamos con los campos del formulario (validados por html)
            $entity->setNombre($request->request->get('nombre'));
            $entity->setSlug();

            //Persistir cambios
            $em->persist($entity);
            $em->flush();

            //Redirigir
            return $this->redirect($this->generateUrl('back_genero_plataformas'));
        }

        //Si no, mostramos el formulario
        return $this->render('GameaffinityBundle:Back:formularioEditarPlataformaGenero.html.twig', array(
                    'editando' => 'Genero',
                    'entity' => $entity
        ));
    }

    //Action que muestra el listado de juegos en la tabla
    public function juegosAction() {
        $em = $this->getDoctrine()->getManager();
        $juegos = $em->getRepository('GameaffinityBundle:Juego')->findAll();

        return $this->render('GameaffinityBundle:Back:backJuegos.html.twig', array(
                    'juegos' => $juegos
        ));
    }

    //Action que gestiona el formulario para insertar juegos en la BD
    public function insertarJuegoAction(Request $request) {
        $form = $this->createForm(JuegoType::class, null, array(
            'action' => $this->generateUrl('back_juegos_insert'),
            'method' => 'POST'
        ));

        //Indicamos al controller que el form se va a encargar de manejar el request
        $form->handleRequest($request);

        //Si se ha enviado el formulario:
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $juego = new Juego();
            $juego->setNombre($data->getNombre());
            $juego->setSlug();
            $juego->setFechaLanzamiento($data->getFechaLanzamiento());
            $juego->setDesarrolladora($data->getDesarrolladora());

            foreach ($data->getPlataformas() as $plataforma) {
                $juego->addPlataforma($plataforma);
            }

            foreach ($data->getGeneros() as $genero) {
                $juego->addGenero($genero);
            }


            //Comprobacion si imagen viene vacía
            $portada = $data->getPortada();

            if ($portada != null) {
                // Generate a unique name for the file before saving it
                $img_name = md5(\uniqid()) . '.' . $portada->guessExtension();

                //Move file
                $targetDir = $this->container->getParameter('kernel.root_dir') . $this->container->getParameter('game_img_dir');
                $portada->move($targetDir, $img_name);

                $juego->setPortada($img_name);
            }

            //Persistimos 
            $em = $this->getDoctrine()->getManager();
            $em->persist($juego);
            $em->flush();

            //Redirect
            return $this->redirect($this->generateUrl('back_juegos'));
        }


        return $this->render('GameaffinityBundle:Back:formularioJuego.html.twig', array(
                    'formulario' => $form->createView()
        ));
    }

    public function editarJuegoAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $juego = $em->getRepository('GameaffinityBundle:Juego')->find($id);
        $old_portada = $juego->getPortada();




        $form = $this->createForm(JuegoType::class, $juego, array(
            'action' => $this->generateUrl('back_juegos_edit', array(
                'id' => $id
            )),
            'method' => 'POST'
        ));


        //Indicamos al controller que el form se va a encargar de manejar el request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $juego->setNombre($data->getNombre());
            $juego->setSlug();
            $juego->setFechaLanzamiento($data->getFechaLanzamiento());
            $juego->setDesarrolladora($data->getDesarrolladora());

            $plataformas = new ArrayCollection();
            $generos = new ArrayCollection();

            foreach ($data->getPlataformas() as $plataforma) {
                $plataformas->add($plataforma);
            }

            foreach ($data->getGeneros() as $genero) {
                $generos->add($genero);
            }

            $juego->setGeneros($generos);
            $juego->setPlataformas($plataformas);

            //Comprobacion si imagen viene vacía del formulario
            $portada = $data->getPortada();


//            $ruta = $this->container->getParameter('kernel.root_dir') . $this->container->getParameter('game_img_dir') . $old_portada;
//            echo $old_portada;
//            exit;
//            
            //Si nos entra portada en el formulario:
            if ($portada != null) {
                //Si el juego tenia portada anteriormente debemos eliminarla               
                $ruta_portada_anterior = $this->container->getParameter('kernel.root_dir') . $this->container->getParameter('game_img_dir') . $old_portada;
                //Eliminamos anterior
                if (file_exists($ruta_portada_anterior) && !is_dir($ruta_portada_anterior)) {
                    unlink($ruta_portada_anterior);
                }

                // Generate a unique name for the file before saving it
                $img_name = md5(\uniqid()) . '.' . $portada->guessExtension();

                //Move file
                $targetDir = $this->container->getParameter('kernel.root_dir') . $this->container->getParameter('game_img_dir');
                $portada->move($targetDir, $img_name);

                $juego->setPortada($img_name);
            }else{
                //Del mismo modo que con la noticia, si no seteo la portada del objeto juego
                //(aun habiendolo recuperado de BD) se queda en blanco para los envios de formulario
                //con el file input en blanco (se supone que dejarlo en blanco significa que no queremos 
                //modificar la imagen, no que queramos borrarla)
                $juego->setPortada($old_portada);
            }


            //Persistimos 
            $em->persist($juego);
            $em->flush();

            //Redirect
            return $this->redirect($this->generateUrl('back_juegos'));
        }

        return $this->render('GameaffinityBundle:Back:formularioJuego.html.twig', array(
                    'formulario' => $form->createView(),
                    'edit' => true
        ));
    }

}
