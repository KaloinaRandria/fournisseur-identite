<?php

namespace App\Entity;

use App\Util\ExpirationUtil;
use App\Util\TokenGeneratorUtil;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JetonRepository")
 */
class Jeton
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "text")]
    private string $jeton;

    #[ORM\Embedded(class: ExpirationUtil::class)]
    private ExpirationUtil $expirationUtil;

    public function __construct(int $duree = 24)
    {
        $this->expirationUtil = (new ExpirationUtil())
            ->setDuree($duree)
            ->calculerDateExpiration(); // Automatiquement définir la date d'expiration

        $this->jeton = TokenGeneratorUtil::generateToken();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getJeton(): string
    {
        return $this->jeton;
    }

    public function setJeton(string $jeton): self
    {
        $this->jeton = $jeton;
        return $this;
    }

    public function getExpirationUtil(): ExpirationUtil
    {
        return $this->expirationUtil;
    }

    public function setExpirationUtil(ExpirationUtil $expirationUtil): self
    {
        $this->expirationUtil = $expirationUtil;
        return $this;
    }

    //méthodes
      /**
     * Vérifie si la date d'expiration du jeton est dépassée.
     */
    public function isExpired(Jeton $jeton): bool
    {
        return $jeton->getExpirationUtil()->getDateExpiration() < new \DateTime();
    }

}