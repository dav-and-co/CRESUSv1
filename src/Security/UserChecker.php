<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void

    {
        if (!$user instanceof User) {
            return;
        }
        if (!$user->getIsActif()) {
            throw new CustomUserMessageAccountStatusException('Votre compte est inactif. Veuillez contacter un administrateur.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // ajout possible d'autres vérifications après authentification
    }
}
