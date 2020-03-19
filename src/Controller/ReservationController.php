<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepo): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepo->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request, MailerInterface $mailer)
    {
        $reservationMsg = new Reservation();
        $formReservation = $this->createForm(ReservationType::class, $reservationMsg);
        $formReservation->handleRequest($request);

        if ($formReservation->isSubmitted() && $formReservation->isValid()) {
            $mailElite = 'pacifiqueviande4000@gmail.com';
            $subject = 'Demande de réservation';
            $auteur = $formReservation->getdata()->getNom();
            $emailUser = $this->security->getUser()->getEmail();
            $date = $formReservation->getData()->getDate()->format('Y-m-d H:i:s');

            $envoisMailResa = (new TemplatedEmail())
                ->from($mailElite)
                ->to(new Address($mailElite))
                ->subject($subject)
                ->htmlTemplate('emails/reservation.html.twig')

                ->context([
                    'date' => $date,
                    'subject' => $subject,
                    'username' => $auteur,
                    'mailClient' => $emailUser,
                ])
            ;

            $mailer->send($envoisMailResa);

            try {
                $entityManager = $this->getDoctrine()->getManager();

                $idUser = $this->security->getUser();
                $reservationMsg->setIdUser($idUser);
                $reservationMsg->setEtatMail('Non envoyée');
                $reservationMsg->setEtatReservation('En attente de validation');

                $entityManager->persist($reservationMsg);
                $entityManager->flush();
                $this->addFlash('success', 'Votre demande de réservation a été envoyé !');
            } catch (Exception $e) {
                $this->addFlash('warning', $e->getMessage());
            }

            $id = $reservationMsg->getId();

            return $this->redirectToRoute('reservation_show', [
                'id' => $id
            ]);
        }

        return $this->render('reservation/index.html.twig', [
            'reservation' => $reservationMsg,
            'ourFormReservation' => $formReservation->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_show", methods={"GET"})
     */
    public function show(Request $request, Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reservation $reservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index');
    }
}
