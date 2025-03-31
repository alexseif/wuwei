<?php

namespace App\User\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'remember_me_tokens')]
class RememberMeToken
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 88)]
    private string $series;

    #[ORM\Column(type: 'string', length: 88)]
    private string $tokenValue;

    #[ORM\Column(type: 'string', length: 200)]
    private string $username;

    #[ORM\Column(type: 'string', length: 200)]
    private string $class;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $lastUsed;

    public function getSeries(): string
    {
        return $this->series;
    }

    public function setSeries(string $series): void
    {
        $this->series = $series;
    }

    public function getTokenValue(): string
    {
        return $this->tokenValue;
    }

    public function setTokenValue(string $tokenValue): void
    {
        $this->tokenValue = $tokenValue;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    public function getLastUsed(): \DateTime
    {
        return $this->lastUsed;
    }

    public function setLastUsed(\DateTime $lastUsed): void
    {
        $this->lastUsed = $lastUsed;
    }
}
