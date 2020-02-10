<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
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
    private $code;

    /**
     * @ORM\Column(type="bigint")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comenvoi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comretrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comsys;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $frais;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dateenvoi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dateretrai;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Expediteur", inversedBy="transaction")
     */
    private $expediteur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Beneficiaire", inversedBy="transaction")
     */
    private $beneficiaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transaction")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getComenvoi(): ?string
    {
        return $this->comenvoi;
    }

    public function setComenvoi(string $comenvoi): self
    {
        $this->comenvoi = $comenvoi;

        return $this;
    }

    public function getComretrait(): ?string
    {
        return $this->comretrait;
    }

    public function setComretrait(string $comretrait): self
    {
        $this->comretrait = $comretrait;

        return $this;
    }

    public function getComsys(): ?string
    {
        return $this->comsys;
    }

    public function setComsys(string $comsys): self
    {
        $this->comsys = $comsys;

        return $this;
    }

    public function getFrais(): ?string
    {
        return $this->frais;
    }

    public function setFrais(string $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDateenvoi(): ?string
    {
        return $this->dateenvoi;
    }

    public function setDateenvoi(string $dateenvoi): self
    {
        $this->dateenvoi = $dateenvoi;

        return $this;
    }

    public function getDateretrai(): ?string
    {
        return $this->dateretrai;
    }

    public function setDateretrai(string $dateretrai): self
    {
        $this->dateretrai = $dateretrai;

        return $this;
    }

    public function getExpediteur(): ?Expediteur
    {
        return $this->expediteur;
    }

    public function setExpediteur(?Expediteur $expediteur): self
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getBeneficiaire(): ?Beneficiaire
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(?Beneficiaire $beneficiaire): self
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
