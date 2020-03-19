<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etatReservation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etatMail;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Table", inversedBy="reservations")
     */
    private $idTable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reservations")
     */
    private $idUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $personnes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    public function __construct()
    {
        $this->idTable = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getEtatReservation(): ?string
    {
        return $this->etatReservation;
    }

    public function setEtatReservation(string $etatReservation): self
    {
        $this->etatReservation = $etatReservation;

        return $this;
    }

    public function getEtatMail(): ?string
    {
        return $this->etatMail;
    }

    public function setEtatMail(?string $etatMail): self
    {
        $this->etatMail = $etatMail;

        return $this;
    }

    /**
     * @return Collection|Table[]
     */
    public function getIdTable(): Collection
    {
        return $this->idTable;
    }

    public function addIdTable(Table $idTable): self
    {
        if (!$this->idTable->contains($idTable)) {
            $this->idTable[] = $idTable;
        }

        return $this;
    }

    public function removeIdTable(Table $idTable): self
    {
        if ($this->idTable->contains($idTable)) {
            $this->idTable->removeElement($idTable);
        }

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPersonnes(): ?int
    {
        return $this->personnes;
    }

    public function setPersonnes(int $personnes): self
    {
        $this->personnes = $personnes;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
