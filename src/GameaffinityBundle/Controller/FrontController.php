<?php

namespace GameaffinityBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use \GameaffinityBundle\Entity\Puntuacion;
use Symfony\Component\HttpFoundation\Request;
use GameaffinityBundle\Form\Type\UsuarioType;
use GameaffinityBundle\Entity\Usuario;

/**
 * Description of FrontController
 *
 * @author agomez
 */
class FrontController extends Controller {

    public function indexAction() {

        //Cargamos ultimas 3 noticias
        $em = $this->getDoctrine()->getManager();
        $ultimas_noticias = $em->getRepository('GameaffinityBundle:Noticia')->findUltimasNoticias($this->container->getParameter('ultimas_noticias_amount'));
        $ultimos_lanzamientos = $em->getRepository('GameaffinityBundle:Juego')->findUltimosLanzamientos($this->container->getParameter('ultimos_lanzamientos_days_amount'));


        return $this->render('GameaffinityBundle:Front:frontpage.html.twig', array(
                    'noticias' => $ultimas_noticias,
                    'lanzamientos' => $ultimos_lanzamientos
        ));
    }

    public function loginAction() {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
                        'GameaffinityBundle:Front:login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error' => $error,
                        )
        );
    }

    public function registerAction(Request $request) {
        $form = $this->createForm(UsuarioType::class, null, array(
            'action' => $this->generateUrl('register'),
            'method' => 'POST'
        ));

        //Indicamos al controller que el form se va a encargar de manejar el request
        $form->handleRequest($request);

        //Si se ha enviado el formulario:
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            //Creamos objeto usuario 
            $usuario = new Usuario();

            //Codificar password
            $plainPassword = $data->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($usuario, $plainPassword);

            //Seteamos usuario con datos formulario
            $usuario->setNombre($data->getNombre());
            $usuario->setEmail($data->getEmail());
            $usuario->setPassword($encoded);

            //Seteamos role
            $em = $this->getDoctrine()->getManager();
            $role = $em->getRepository('GameaffinityBundle:Role')->findOneBy(array(
                'nombre' => 'ROLE_USER'
            ));
            $usuario->setRole($role);

            //Persistimos usuario           
            $em->persist($usuario);
            $em->flush();

            //Redirigimos usuario a la pantalla de login tras registrarse
            return $this->redirect($this->generateUrl('login'));
        }
        //Si no se ha enviado el formulario, simplemente lo mostramos
        return $this->render('GameaffinityBundle:Front:registerForm.html.twig', array(
                    'formulario' => $form->createView(),
        ));
    }

    //Funcion que se utiliza para cargar datos de la base de datos y pasarlos al navbar antes
    //de renderizarlo
    public function navbarFillAction() {
        $em = $this->getDoctrine()->getManager();
        $plataformas = $em->getRepository('GameaffinityBundle:Plataforma')->findAll();
        $generos = $em->getRepository('GameaffinityBundle:Genero')->findAll();

        return $this->render('GameaffinityBundle:Front:frontNavbar.html.twig', array(
                    'plataformas' => $plataformas,
                    'generos' => $generos
        ));
    }

    public function profileAction() {
//        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
//            throw $this->createAccessDeniedException();
//        }
        return $this->render('GameaffinityBundle:Front:userProfile.html.twig');
    }

    public function showGameAction($id, $result = "null") {
        $em = $this->getDoctrine()->getManager();
        $juego = $em->getRepository('GameaffinityBundle:Juego')->find($id);

        if (!$juego) {
            throw $this->createNotFoundException('El juego solicitado no existe en la base de datos.');
        }

//        $usuario = $this->get('security.token_storage')->getToken()->getUser();
        $puntuado = false;

        //Comprobamos si hay usuario logueado
        if ($this->get('security.authorization_checker')->isGranted("ROLE_USER")) {
            //Comprobamos si el usuario ha puntuado el juego que vamos a mostrar
            //para no mostrarle de nuevo esa opcion en la vista (solo se pueden puntuar los juegos una vez)
            $puntuacion = $em->getRepository('GameaffinityBundle:Puntuacion')->findBy(array(
                'juego' => $id,
                'usuario' => $this->get('security.token_storage')->getToken()->getUser()->getId()
            ));
            //Comprobamos si el usuario ha puntuado previamente el juego en cuestion
            if (count($puntuacion) > 0) {
                $puntuado = true;
            }
        }

        return $this->render('GameaffinityBundle:Front:showGame.html.twig', array(
                    'juego' => $juego,
                    'result' => $result,
                    'puntuado' => $puntuado
        ));
    }

    public function noticiasAction() {
        $em = $this->getDoctrine()->getManager();
        $noticias = $em->getRepository('GameaffinityBundle:Noticia')->findAll();

        return $this->render('GameaffinityBundle:Front:noticias.html.twig', array(
                    'noticias' => $noticias
        ));
    }

    public function showPlataformaAction($slug) {
        $em = $this->getDoctrine()->getManager();
        $plataforma = $em->getRepository('GameaffinityBundle:Plataforma')->findOneBy(array('slug' => $slug));

        if (!$plataforma) {
            throw $this->createNotFoundException('No existe la plataforma en la base de datos.');
        }

        return $this->render('GameaffinityBundle:Front:showGamesGrid.html.twig', array(
                    'plataforma' => $plataforma,
        ));
    }

    public function showGeneroAction($slug) {
        $em = $this->getDoctrine()->getManager();
        $genero = $em->getRepository('GameaffinityBundle:Genero')->findOneBy(array('slug' => $slug));

        if (!$genero) {
            throw $this->createNotFoundException('No existe el género especificado.');
        }

        return $this->render('GameaffinityBundle:Front:showGamesGrid.html.twig', array(
                    'genero' => $genero,
        ));
    }

    //Parametro id = id del juego
    //Esta funcion maneja el añadir a favoritos, eliminar favoritos y puntuar juego
    public function favoriteAction($action, $id) {
        $result;

        $user = $this->get('security.token_storage')->getToken()->getUser();


        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('GameaffinityBundle:Juego')->find($id);

        if (!$game) {
            throw $this->createNotFoundException('El juego que intentas añadir a favoritos no existe.');
        }

        if ($action == "add") {

            /* Verificamos que el juego no esté ya en los favoritos del usuario
             * Posiblemente no seria necesario porque por código ya se controla previamente a 
             * pulsar el boton si es favorito o no favorito (se comprueba por twig, de modo que al 
             * usuario actual solo se le da la opción que corresponde (agregar o eliminar favorito)             */
            if (!in_array($game, $user->getJuegosFavoritos()->toArray())) {
                $user->addJuegoFavorito($game);
                $result = "added-favorite";
            }
        } else {
            if (in_array($game, $user->getJuegosFavoritos()->toArray())) {
                $user->removeJuegoFavorito($game);
                $result = "removed-favorite";
            }
        }
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('game_info', array(
                            'id' => $id,
                            'result' => $result
        )));
    }

    public function rateGameAction($id, Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();


        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('GameaffinityBundle:Juego')->find($id);

//        $puntuaciones = $em->getRepository('GameaffinityBundle:Puntuacion')

        if (!$game) {
            throw $this->createNotFoundException('El juego que intentas puntuar no existe.');
        }

        //Recogemos la puntuacion del formulario que nos llega a traves de la request
        $valoracion_usuario = $request->get('puntuacion');

        //Creamos la puntuacion de este usuario para este juego
        $puntuacion = new Puntuacion();
        $puntuacion->setUsuario($user);
        $puntuacion->setJuego($game);
        $puntuacion->setNota($valoracion_usuario);

        //Persistimos la puntuacion para no desvirtuar el cálculo de la puntuación media realizado a continuación
        $em->persist($puntuacion);
        $em->flush();

        //sacar media de puntuaciones del juego
        $media = $em->getRepository('GameaffinityBundle:Puntuacion')->findPuntuacionMedia($id);

        //Setear puntuacion del juego al valor de la media
        $game->setPuntuacion($media['promedio']);

        //Persistir juego
        $em->persist($game);
        $em->flush();

        return $this->render('GameaffinityBundle:Front:showGame.html.twig', array(
                    'result' => 'rated-ok',
                    'juego' => $game
        ));
    }

    //------------------PARA PROBAR LOGINS / PERMISOS-------------------
    public function privadoAction() {
        return $this->render('GameaffinityBundle:Front:zonaPrivada.html.twig');
    }

}
