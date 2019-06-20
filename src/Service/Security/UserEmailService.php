<?php

namespace App\Service\Security;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\ParentUser;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig_Environment;

/**
 * Une classe qui possède des méthodes liées à la sécurité des emails utilisateurs
 */
class UserEmailService
{
    
    private $mailer;
    private $mailerUser;
    private $urlGenerator;
    private $twig;

    // Durée de validité du token de reset password
    public const LOST_PASSWORD_VALIDITY_TIME = 1; // en heures
    
    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig, UrlGeneratorInterface $urlGenerator, string $mailerUser = 'lucas.barneoudarnaud@gmail.com')
    {
        $this->mailer = $mailer;
        $this->mailerUser = $mailerUser;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
    }

    /**
     * This method is used to send an email to verify that the user email is correct
     *
     * @param ParentUser $user
     *
     * @return bool True si message(s) envoyé(s) avec succès, False sinon
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendValidationEmail(ParentUser $user){
        if(!$user->getEmail()){
            
            return false;
        }
        
        $message = (new Swift_Message('SITE - Validez votre e-mail'))
            ->setFrom($this->mailerUser)
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    '/security/validation-email.html.twig', array('link' => $this->urlGenerator->generate('validateemail', ['emailToken' => $user->getEmailToken(), 'emailTemp' => $user->getEmail()], UrlGeneratorInterface::ABSOLUTE_URL))
                ), 'text/html'
            )
        ;
        return (bool) $this->mailer->send($message);
    }

    /**
     * @param ParentUser $user
     *
     * @return bool True si message(s) envoyé(s) avec succès, False sinon
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendLostPasswordMail(ParentUser $user){
        if(!$user->getEmail()){

            return false;
        }

        $message = (new Swift_Message('SITE - Mot de passe perdu'))
            ->setFrom($this->mailerUser)
            ->setTo($user->getEmail())
            ->setBody(
            $this->twig->render(
                    '/security/lostpassword-email.html.twig', array('link' => $this->urlGenerator->generate('resetpassword', ['lostPasswordToken' => $user->getLostPasswordToken()], UrlGeneratorInterface::ABSOLUTE_URL), 'validity' => self::LOST_PASSWORD_VALIDITY_TIME)
                ), 'text/html'
            )
        ;
        return (bool) $this->mailer->send($message);
    }
}
