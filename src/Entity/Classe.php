<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 */
class Classe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $designation;

    /**
     * @ORM\ManyToOne(targetEntity=Option::class)
     */
    private $options;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_active;

    /**
     * @ORM\ManyToOne(targetEntity=Professeur::class)
     */
    private $titulaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getOptions(): ?Option
    {
        return $this->options;
    }

    public function setOptions(?Option $options): self
    {
        $this->options = $options;

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

    public function getTitulaire(): ?Professeur
    {
        return $this->titulaire;
    }

    public function setTitulaire(?Professeur $titulaire): self
    {
        $this->titulaire = $titulaire;

        return $this;
    }
}
