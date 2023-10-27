<?php

namespace ExercisePromo\Auth;

use ExercisePromo\Entity\User;
use Odan\Session\SessionInterface;

class Auth
{
    public function __construct(
        protected SessionInterface $session,
    ) {}

    public function login(User $user): void
    {
        $this->session->set('user', $user);
    }

    public function getUser(): User
    {
        return $this->session->get('user');
    }

    public function isAuthorized(): bool
    {
        return (bool) $this->session->get('user');
    }

    public function logout()
    {
        $this->session->clear();
    }
}
