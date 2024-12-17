<?php 
namespace App\Service;

use App\Entity\Jeton;

class JetonService
{
    private int $defaultDuree;

    public function __construct(int $defaultDuree)
    {
        $this->defaultDuree = $defaultDuree;
    }

    public function createJeton(): Jeton
    {
        return new Jeton($this->defaultDuree);
    }
}
