<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

abstract class AbstractController extends SymfonyAbstractController
{
    public function getUser(): ?User
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            return $user;
        }

        return null;
    }
}
