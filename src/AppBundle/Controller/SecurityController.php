<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ForgottenPasswordType;
use AppBundle\Form\RegisterType;
use AppBundle\Form\ReinitialisationPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('@Public/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = New User();

        $form = $this->createForm(RegisterType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_USER']);
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            $this->get('session')->set('_security_main', serialize($token));

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@Public/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/forgotten_password", name="forgotten_password")
     */
    public function forgottenPasswordAction(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ForgottenPasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy([ 'email' => $data['email']]);

            if ($user){
                $routerContext = $this->container->get('router')->getContext();

                $mail = (new \Swift_Message('Réinitialisation de votre mot de passe'))
                    ->setFrom('nicolasbeck.dev@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            '@Email/forgottenPassword.html.twig',
                            [
                                'name' => $user->getFirstname(),
                                'url' => $routerContext->getHost() . ":" . $routerContext->getHttpPort() . '/reinitialization/' . $user->getId()
                            ]
                        ),
                        'text/html'
                    )
                ;

                $mailer->send($mail);

                $message = 'Nous venons de vous envoyer une demande de réinitialisation par e-mail, veuillez la compléter.';
                $type = 'success';
            }else{
                $message = "L'adresse e-mail saisie n'est associée à aucun compte.";
                $type = 'danger';
            }
        }

        return $this->render('@Public/forgottenPassword.html.twig', [
            'form' => $form->createView(),
            'message' => $message ?? false,
            'type' => $type ?? false,
        ]);
    }

    /**
     * @Route("/reinitialization/{$id}", name="reinitialization")
     */
    public function reinitializationAction($id, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(ReinitialisationPasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();

            $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy([ 'id' => $id]);

            $user->setPassword($encoder->encodePassword($user, $data['password']));

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            $this->get('session')->set('_security_main', serialize($token));

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@Public/register.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }
}
