<?php

namespace App\Service\Security;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\ParentUser;

/**
 * Une classe qui possède des méthodes liées à la sécurité des emails utilisateurs
 */
class UserEmailService
{
    
    private $mailer;
    private $mailerUser;
    private $urlGenerator;
    private $twig;
    
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, UrlGeneratorInterface $urlGenerator, string $mailerUser)
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
     * @return boolean
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendValidationEmail(ParentUser $user){
        if(!$user->getEmail()){
            
            return false;
        }
        
        $message = (new \Swift_Message('Validation email'))
            ->setFrom($this->mailerUser)
            ->setTo($user->getEmail())
            ->setBody(
            $this->twig->render(
                '/Security/Email/emailvalidation-email.html.twig', array('link' => $this->urlGenerator->generate('validateemail', ['emailToken' => $user->getEmailToken(), 'emailTemp' => $user->getEmail()], UrlGeneratorInterface::ABSOLUTE_URL))
            ), 'text/html'
            )
        ;
        
        return (bool) $this->mailer->send($message);
    }
}
