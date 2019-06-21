<?php
namespace App\Controller;

use App\Service\Security\UserEmailService;
use App\Form\RegisterUserType;
use App\Form\ResetPasswordType;
use App\Entity\ParentUser;
use DateTime;
use Exception;
use RuntimeException;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SecurityController extends AbstractController
{

    const LOST_PASSWORD_VALIDITY_TIME = 1; // en heures

    /**
     * Use this function to authenticate a User "manually"
     * 
     * @param ParentUser $user
     */

    private function authenticateUser(ParentUser $user)
    {
        $token = new UsernamePasswordToken($user, null, 'private', $user->getRoles());

        $this->get('security.token_storage')->setToken($token);
    }

    /**
     * Permet d'enregistrer un nouvel utilisateur
     *
     * @Route("/register", name="register")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserEmailService $ues
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, UserEmailService $ues)
    {
        // 1) Construire le form User
        $user = new ParentUser();
        $registerForm = $this->createForm(RegisterUserType::class, $user);

        // 2) Hydrater l'objet User
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {

            // 3) Encoder le mdp
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
//
//            // 4) Faut-il valider l'email ? (boolean dans le fichier parameters.yml)
            $needEmailValidation = $this->getParameter('verify_email_after_registration');
            if ($needEmailValidation) {
                $emailToken = md5(uniqid());
                $user->setRoles(array('ROLE_USER_PENDING'))->setEmail($user->getEmail())->setEmailToken($emailToken);
                $isSend = $ues->sendValidationEmail($user);
            }

//            // 5) Sauvegarder l'utilisateur
            $em->persist($user);
            $em->flush();

//            $this->render(
//                'global/alert-modal.html.twig',
//                [
//                    'title' => "Vous êtes inscris !",
//                    'message' => "Veillez valider votre compte à l'aide de le-mail envoyé à l'adresse saisie.",
//                    'type' => "success"
//                ]
//            );

            $this->addFlash('success', "Un e-mail de confirmation vous a été envoyé à l'adresse indiquée");

            // Confirmer inscription + prévenir envoi mail
            return $this->redirectToRoute('home');
        }

        return $this->render(
                'security/register.html.twig',
                [
                    'registerForm' => $registerForm->createView(),
                ]
        );
    }

    /**
     * Permet de se connecter
     * 
     * @Route("/login", name="login")
     * 
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * 
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render(
            '/security/login.html.twig',
            [
            'last_username' => $lastUsername,
            'error' => $error,
            ]
        );
    }

    /**
     * Permet de se connecter au journal en tnt que child user
     *
     * @Route("/journal/ouvrir", name="ouvrir-journal")
     *
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     *
     * @return Response
     */
    public function childLogin(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render(
            '/security/childLogin.html.twig',
            [
            'last_username' => $lastUsername,
            'error' => $error,
            ]
        );
    }

    /**
     * Url de deconnexion
     * 
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * Affiche un formulaire pour redéfinir son MDP, et envoie un email de redéfinition du mdp
     *
     * @Route("/lostpassword", name="lostpassword")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserEmailService $ues
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendLostPasswordMailAction(Request $request, EntityManagerInterface $em, UserEmailService $ues)
    {
        $userMail = $request->get('email');
        $responseParams = [];
        if ($userMail) {
            $userRepository = $this->getDoctrine()->getRepository(ParentUser::class);
            /* @var $user ParentUser */
            $user = $userRepository->findOneByEmail($userMail);
            if ($user) {
                $resetToken = md5(uniqid());
                $user->setLostPasswordDate(new DateTime())->setLostPasswordToken($resetToken);
                $em->flush();
                $mailSent = $ues->sendLostPasswordMail($user);

                if ($mailSent) {
                    $this->addFlash(
                        "success", "Un e-mail pour réinitialiser votre mot de passe vous a été envoyé"
                    );
                    return $this->redirectToRoute("home");
                } else {
                    $this->addFlash(
                        "error", "Une erreur est survenue, merci d'essayer à nouveau"
                    );
                }
            } else {
                $this->addFlash(
                    "warning", "Adresse e-mail inconnue"
                );
            }
        }

        return $this->render('/security/lostpassword.html.twig', $responseParams);
    }

    /**
     * Redéfinir un mdp depuis le lien de redéfinition envoyé par mail
     *
     * @Route("/resetpassword/{lostPasswordToken}", name="resetpassword")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ParentUser $user
     *
     * @param UserEmailService $ues
     * @return Response
     * @throws Exception
     */
    public function resetPasswordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, ParentUser $user, UserEmailService $ues)
    {
        if ($user) {
            // Changer le MDP si form submitted
            $passwordForm = $this->createForm(ResetPasswordType::class, $user);
            $passwordForm->handleRequest($request);
            if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

                // Encode the password (you could also do this via Doctrine listener)
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                // Reset lostPassword token & date
                $user->setLostPasswordToken(null)->setLostPasswordDate(null);

                // On en profite pour valider l'email de l'utilisateur au cas où ce n'était pas déjà fait
                $user->removeRole('ROLE_USER_PENDING')->addRole('ROLE_USER')->setEmailToken(null);

                // save the User!
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->authenticateUser($user);

                $this->addFlash("success", "Mot de passe modifié avec succès");

                return $this->redirectToRoute('home');
            }

            $validity = $ues::LOST_PASSWORD_VALIDITY_TIME;
            if ($user->getLostPasswordDate()->modify("+$validity hour") >= new DateTime()) {

                return $this->render('/security/resetpassword.html.twig', array('passwordForm' => $passwordForm->createView()));
            } else {
                $this->addFlash("warning", "Le lien n'est plus valide");
            }
        }

        $this->addFlash('error', "Une erreur est survenue, veuillez ré-essayer plus tard");
        return $this->redirectToRoute('lostpassword');
    }

    /**
     * Valide un email depuis le lien de validation
     * 
     * @Route("/validate/{emailTemp}/{emailToken}", name="validateemail")
     * 
     * @param EntityManagerInterface $em
     * @param ParentUser $user
     * 
     * @return Response
     */
    public function validateEmail(EntityManagerInterface $em, ParentUser $user)
    {

        if ($user) {
            $user->setEmail($user->getEmail())
                ->setEmailToken(null)
                ->addRole('ROLE_USER')
                ->removeRole('ROLE_USER_PENDING');
            $em->flush();
            // On authentifie l'utilisateur au cas où ce ne soit pas déjà fait
            $this->authenticateUser($user);
            $this->addFlash("success", "Adresse e-mail validée avec succès");

            return $this->redirectToRoute('home');
        }

        $this->addFlash("warning", "Cette adresse e-mail n'existe pas, ou le lien est expiré");

        return $this->redirectToRoute('login');
    }

    /**
     * Afficher la page d'accès refusé (droits insufisants)
     *
     * @Route("/private/accessdenied", name="accessdenied")
     *
     * @param Request $request
     * @param UserEmailService $userEmailService
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function accessDenied(Request $request, UserEmailService $userEmailService)
    {

        if ($request->get('resendEmailValidation') == 1) {
            if ($userEmailService->sendValidationEmail($this->getUser())) {
                $this->addFlash(
                    "success", "E-mail envoyé avec succès"
                );
            } else {
                $this->addFlash(
                    "error", "Une erreur est survenue, merci d'essayer à nouveau"
                );
            }
        }

        return $this->render('/Security/AccessDenied/accessdenied.html.twig');
    }
}
