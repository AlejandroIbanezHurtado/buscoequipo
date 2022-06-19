<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MandaEmailController extends AbstractController
{
    /**
     * @Route("/manda/email", name="app_manda_email")
     */
    public function CargaEmail(MailerInterface $mailer,$text,$to): Response
    {
        $email = (new Email())
            ->from("")
            ->to($to)
            ->subject('BuscoEquipo')
            ->text($text);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
           
           
        }
        return $this->render('index.html.twig', [
            'controller_name' => 'PaginaProyectosController',
        ]);


        
    }
}
