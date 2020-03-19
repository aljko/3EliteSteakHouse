<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 *
 * @Route("/adminReservation")
 */
class AdminReservationController extends AbstractController
{
    /**
     * Require ROLE_ADMIN for only this controller method.
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @Route("/", name="adminReservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepo): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepo->findAll(),
        ]);
    }

    /**
     * Require ROLE_ADMIN for only this controller method.
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @Route("/accepter/{id}", name="reservation_accepter", methods={"GET"})
     */
    public function accepterResa(Request $request, MailerInterface $mailer, Reservation $reservation): Response
    {
        $reservation->setEtatMail('Envoyé');
        $reservation->setEtatReservation('Acceptée');
        $this->getDoctrine()->getManager()->flush();

        $mailElite = 'pacifiqueviande4000@gmail.com';
        $subject = 'Réservation acceptée';
        $emailUser = $reservation->getIdUser()->getEmail();
        $auteur = $reservation->getNom();
        $date = $reservation->getDate()->format('Y-m-d H:i:s');

        $envoisMailAccepter = (new TemplatedEmail())
            ->from($mailElite)
            ->to(new Address($emailUser))
            ->subject($subject)
            ->htmlTemplate('emails/reservationAccepter.html.twig')

            ->context([
                'date' => $date,
                'subject' => $subject,
                'username' => $auteur,
                'mailClient' => $emailUser,
            ])
        ;

        $mailer->send($envoisMailAccepter);

        return $this->redirectToRoute('adminReservation_index');
    }

    /**
     * Require ROLE_ADMIN for only this controller method.
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @Route("/refuser/{id}", name="reservation_refuser", methods={"GET"})
     */
    public function refuserResa(Request $request, MailerInterface $mailer, Reservation $reservation): Response
    {
        $reservation->setEtatMail('Envoyé');
        $reservation->setEtatReservation('Refusée');
        $this->getDoctrine()->getManager()->flush();

        $mailElite = 'pacifiqueviande4000@gmail.com';
        $subject = 'Réservation refusée';
        $emailUser = $reservation->getIdUser()->getEmail();
        $auteur = $reservation->getNom();
        $date = $reservation->getDate()->format('Y-m-d H:i:s');

        $envoisMailRefuser = (new TemplatedEmail())
            ->from($mailElite)
            ->to(new Address($emailUser))
            ->subject($subject)
            ->htmlTemplate('emails/reservationRefuser.html.twig')

            ->context([
                'date' => $date,
                'subject' => $subject,
                'username' => $auteur,
                'mailClient' => $emailUser,
            ])
        ;

        $mailer->send($envoisMailRefuser);

        return $this->redirectToRoute('adminReservation_index');
    }
}
