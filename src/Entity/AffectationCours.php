<?php

namespace App\Entity;

use App\Repository\AffectationCoursRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AffectationCoursRepository::class)
 */
class AffectationCours
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cours::class)
     */
    private $cours;

    /**
     * @ORM\ManyToOne(targetEntity=Professeur::class)
     */
    private $professeur;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class)
     */
    private $classe;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $charge_horaire;

    /**
     * @ORM\ManyToOne(targetEntity=AnneeScolaire::class)
     */
    private $annee_scolaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    public function getProfesseur(): ?Professeur
    {
        return $this->professeur;
    }

    public function setProfesseur(?Professeur $professeur): self
    {
        $this->professeur = $professeur;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getChargeHoraire(): ?int
    {
        return $this->charge_horaire;
    }

    public function setChargeHoraire(?int $charge_horaire): self
    {
        $this->charge_horaire = $charge_horaire;

        return $this;
    }

    public function getAnneeScolaire(): ?AnneeScolaire
    {
        return $this->annee_scolaire;
    }

    public function setAnneeScolaire(?AnneeScolaire $annee_scolaire): self
    {
        $this->annee_scolaire = $annee_scolaire;

        return $this;
    }
}
