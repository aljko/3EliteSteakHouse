<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
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
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Photo", inversedBy="articles")
     */
    private $idPhoto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeArticle", inversedBy="articles")
     */
    private $typeArticle;

    public function __construct()
    {
        $this->idPhoto = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getIdPhoto(): Collection
    {
        return $this->idPhoto;
    }

    public function addIdPhoto(Photo $idPhoto): self
    {
        if (!$this->idPhoto->contains($idPhoto)) {
            $this->idPhoto[] = $idPhoto;
        }

        return $this;
    }

    public function removeIdPhoto(Photo $idPhoto): self
    {
        if ($this->idPhoto->contains($idPhoto)) {
            $this->idPhoto->removeElement($idPhoto);
        }

        return $this;
    }

    public function getTypeArticle(): ?TypeArticle
    {
        return $this->typeArticle;
    }

    public function setTypeArticle(?TypeArticle $typeArticle): self
    {
        $this->typeArticle = $typeArticle;

        return $this;
    }
}
