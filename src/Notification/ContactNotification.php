<?php

namespace App\Notification;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ContactNotification
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notify(Contact $contact)
    {
        // Create simple email
        // $email = (new Email())
        //     ->from($contact->getEmail())
        //     ->to('contact@immo78.fr')
        //     ->subject('Nouveau message à propos de : ' . strtoupper($contact->getProperty()->getTitle()))
        //     // ->text($contact->getMessage())
        //     ->html(nl2br($contact->getMessage()));

        // Create templated email for contact (email from user)
        $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('contact@immo78.fr')
            ->subject('Nouveau message à propos de : ' . strtoupper($contact->getProperty()->getTitle()))
            // path of the Twig template to render
            ->htmlTemplate('emails/contact.html.twig')
            // pass variables (name => value) to the template
            ->context([
                'contact' => $contact
            ]);
        // Send email
        $this->mailer->send($email);


        // Create templated email for aknowledgement (email to user)
        $email = (new TemplatedEmail())
            ->from('contact@immo78.fr')
            ->to(new Address($contact->getEmail()))
            ->subject('Accusé récéption de votre message')
            ->htmlTemplate('emails/aknowledgement.html.twig')
            ->context([
                'firstName' => $contact->getFirstName(),
                'lastName' => $contact->getLastName(),
                'property_title' => strtoupper($contact->getProperty()->getTitle()),
            ]);
        $this->mailer->send($email);
    }
}
