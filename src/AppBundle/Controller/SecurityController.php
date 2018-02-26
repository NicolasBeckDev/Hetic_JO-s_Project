<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Service\UserServices;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends Controller
{

    private $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    /**
     * @Route("/connexion", name="login")
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $test = 'ok';
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('@Client/connection/connection.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/inscription", name="register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request)
    {
        $user = New User();

        $form = $this->createForm('AppBundle\Form\UserType', $user, [
            'type' => 'userNew'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getDoctrine()->getRepository('AppBundle:User')->findOneBy([ 'email' => $user->getEmail()])) {
                $message = 'Cette adresse email est déjà utilisée, veuillez en saisir une autre';
                $type = 'error';
            }else{
                $user = $this->userServices->prePersistRegister($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);

                $this->get('session')->set('_security_main', serialize($token));

                $event = new InteractiveLoginEvent($request, $token);
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

                return $this->redirectToRoute('homepage',[
                    'message' => "Vous êtes maintenant prêt à rejoindre les autres participants",
                    'type' => "success",
                    'title' => "Félicitations !"
                ]);
            }
        }

        return $this->render('@Client/registration/registration.html.twig', [
            'form' => $form->createView(),
            'message' => $message ?? false,
            'type' => $type ?? false,
        ]);
    }

    /**
     * @Route("/mot-de-passe-oublie", name="forgotten_password")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forgottenPasswordAction(Request $request, \Swift_Mailer $mailer)
    {
        $user = New User();

        $form = $this->createForm('AppBundle\Form\UserType', $user, [
            'type' => 'forgotten'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy([ 'email' => $user->getEmail()]);

            if ($user){

                $user = $this->userServices->prePersistForgottenPassword($user);

                $this->getDoctrine()->getManager()->flush();

                $routerContext = $this->container->get('router')->getContext();

                $url = 'http://' . $routerContext->getHost() . ':' . $routerContext->getHttpPort() . '/reinitialisation/' . $user->getToken();

                $mail = (new \Swift_Message('Tous Paris. : Réinitialisation de votre mot de passe'))
                    ->setFrom('tousparis2024@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        '<html>' .
                        '<head></head>' .
                        '<body>' .
                        'Bonjour'.$user->getFirstname().'<br><br>'.
                        'Nous avons reçu une demande de réinitialisation de votre mot de passe.<br>'.
                        '<a href="' . $url . ' ">Cliquez ici pour réinitialiser votre mot de passe.</a><br><br>'.
                        'Cordialement,<br>'.
                        "L'équipe de Tous Paris.".
                        ' </body>' .
                        '</html>',
                        'text/html'
                    );

                $mailer->send($mail);

                $title = 'Eh voilà !';
                $message = 'Nous venons de vous envoyer une demande de réinitialisation par e-mail, veuillez la compléter.';
                $type = 'success';
            }else{
                $title = "Oops...";
                $message = "L'adresse e-mail saisie n'est associée à aucun compte.";
                $type = 'error';
            }
        }


        return $this->render('@Client/forgottenPassword/forgottenPassword.html.twig', [
            'form' => $form->createView(),
            'message' => $message ?? false,
            'title' => $title ?? false,
            'type' => $type ?? false,
        ]);
    }

    /**
     * @Route("/reinitialisation/{token}", name="reinitialization")
     * @Method({"GET", "POST"})
     * @param $token
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function reinitializationAction($token, Request $request)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy([ 'token' => $token]);

        if ($user){

            $userForm = New User();

            $form = $this->createForm('AppBundle\Form\UserType', $userForm, [
                'type' => 'reinitialisation'
            ]);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $user = $this->userServices->prePersistReinitialization($user, $userForm);

                $this->getDoctrine()->getManager()->flush();

                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);

                $this->get('session')->set('_security_main', serialize($token));

                $event = new InteractiveLoginEvent($request, $token);
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

                return $this->redirectToRoute('homepage');
            }

            return $this->render('@Client/reinitialization/reinitalization.html.twig', [
                'form' => $form->createView()
            ]);

        }

        return $this->redirectToRoute('login');

    }

    /**
     * @Route("/deconnexion", name="logout")
     */
    public function logoutAction()
    {

    }
}
