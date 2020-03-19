<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @Route("/franchise", name="franchise_")
 */
class FranchiseController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods={"GET","POST"})
     */

    public function index(Request $request, MailerInterface $mailer): Response
    {
        $msg = new Message();
        $formFranchise = $this->createForm(MessageType::class, $msg);
        $formFranchise->handleRequest($request);

        if ($formFranchise->isSubmitted() && $formFranchise->isValid()) {
            $contenu = $formFranchise->getdata()->getContenu();
            $subject = $formFranchise->getdata()->getTitre();
            $auteur = $formFranchise->getdata()->getAuteur();
            $mailElite = 'pacifiqueviande4000@gmail.com';
            $mailClient = $formFranchise->getData()->getMail();
            $tel = $formFranchise->getData()->getTelephone();

            $envoisMail = (new TemplatedEmail())
                ->from($mailElite)
                ->to(new Address($mailElite))
                ->subject($subject)
                ->text($contenu)
                ->htmlTemplate('emails/franchise.html.twig')

                ->context([
                    'contenu' => $contenu,
                    'subject' => $subject,
                    'tel' => $tel,
                    'username' => $auteur,
                    'mailClient' => $mailClient,
                ])
            ;

            $mailer->send($envoisMail);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($msg);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande a été envoyé !');
            return $this->redirectToRoute('franchise_index');
        }

        return $this->render('franchise/index.html.twig', [
            'msg' => $msg,
            'ourFormFranchise' => $formFranchise->createView()
        ]);
    }
}
