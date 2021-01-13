<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaiementRepository::class)
 */
class Paiement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Inscription::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $inscription;

    /**
     * @ORM\ManyToOne(targetEntity=Frais::class)
     */
    private $frais;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant_paye;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant_reste;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $creted_by;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_active;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInscription(): ?Inscription
    {
        return $this->inscription;
    }

    public function setInscription(?Inscription $inscription): self
    {
        $this->inscription = $inscription;

        return $this;
    }

    public function getFrais(): ?Frais
    {
        return $this->frais;
    }

    public function setFrais(?Frais $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getMontantPaye(): ?float
    {
        return $this->montant_paye;
    }

    public function setMontantPaye(?float $montant_paye): self
    {
        $this->montant_paye = $montant_paye;

        return $this;
    }

    public function getMontantReste(): ?float
    {
        return $this->montant_reste;
    }

    public function setMontantReste(?float $montant_reste): self
    {
        $this->montant_reste = $montant_reste;

        return $this;
    }

    public function getCretedBy(): ?User
    {
        return $this->creted_by;
    }

    public function setCretedBy(?User $creted_by): self
    {
        $this->creted_by = $creted_by;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }



}
