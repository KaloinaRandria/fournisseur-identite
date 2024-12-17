<?php

namespace App\Util;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class ExpirationUtil
{
    #[ORM\Column(type: "datetime", name: "date_insertion", nullable: true)]
    private ?\DateTimeInterface $dateInsertion = null;

    #[ORM\Column(type: "datetime", name: "date_expiration")]
    private \DateTimeInterface $dateExpiration;

    #[ORM\Column(type: "integer", name: "duree", nullable: false)]
    private int $duree; // Durée en heures

    /**
     * Constructeur pour initialiser ExpirationUtil avec une durée en heures.
     *
     * @param int $duree Durée en heures
     */
    public function __construct(int $duree)
    {
        $this->duree = $duree;
        $this->dateInsertion = new \DateTime(); // Date et heure actuelles
        $this->dateExpiration = $this->calculerDateExpiration(); // Calculer la date d'expiration
    }

    public function getDateInsertion(): ?\DateTimeInterface
    {
        return $this->dateInsertion;
    }

    public function setDateInsertion(?\DateTimeInterface $dateInsertion): self
    {
        $this->dateInsertion = $dateInsertion;
        return $this;
    }

    public function getDateExpiration(): \DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(): self
    {
        // Appeler la méthode calculerDateExpiration pour définir la date d'expiration
        $this->dateExpiration = $this->calculerDateExpiration();
        return $this;
    }

    /**
     * Calcule la date d'expiration en fonction de la durée en heures
     *
     * @return \DateTimeInterface
     */
    public function calculerDateExpiration(): \DateTimeInterface
    {
        if ($this->dateInsertion === null) {
            $this->dateInsertion = new \DateTime(); // Date actuelle si non définie
        }

        // Cloner la date d'insertion pour ne pas la modifier directement
        $expirationDate = clone $this->dateInsertion;
        
        // Ajouter la durée en heures à la date d'insertion
        $expirationDate->add(new \DateInterval('PT' . $this->duree . 'H')); 

        return $expirationDate;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }
}
