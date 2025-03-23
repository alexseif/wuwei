<?php

namespace App\Security\RememberMe;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentTokenInterface;
use Symfony\Component\Security\Core\Authentication\RememberMe\TokenProviderInterface;
use App\Entity\RememberMeToken;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

class CustomTokenProvider implements TokenProviderInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function loadTokenBySeries(string $series): PersistentTokenInterface
    {
        $token = $this->em->getRepository(RememberMeToken::class)->findOneBy(['series' => $series]);

        if (!$token) {
            throw new TokenNotFoundException(sprintf('No token found for series "%s".', $series));
        }

        return new PersistentToken(
            $token->getClass(),
            $token->getUsername(),
            $token->getSeries(),
            $token->getTokenValue(),
            $token->getLastUsed()
        );
    }

    public function deleteTokenBySeries(string $series): void
    {
        $token = $this->em->getRepository(RememberMeToken::class)->findOneBy(['series' => $series]);

        if ($token) {
            $this->em->remove($token);
            $this->em->flush();
        }
    }

    public function updateToken(string $series, string $tokenValue, \DateTime $lastUsed): void
    {
        $token = $this->em->getRepository(RememberMeToken::class)->findOneBy(['series' => $series]);

        if (!$token) {
            throw new TokenNotFoundException(sprintf('No token found for series "%s".', $series));
        }

        $token->setTokenValue($tokenValue);
        $token->setLastUsed($lastUsed);

        $this->em->persist($token);
        $this->em->flush();
    }

    public function createNewToken(PersistentTokenInterface $token): void
    {
        $rememberMeToken = new RememberMeToken();
        $rememberMeToken->setClass($token->getClass());
        $rememberMeToken->setUsername($token->getUserIdentifier()); // Updated
        $rememberMeToken->setSeries($token->getSeries());
        $rememberMeToken->setTokenValue($token->getTokenValue());
        $rememberMeToken->setLastUsed($token->getLastUsed());

        $this->em->persist($rememberMeToken);
        $this->em->flush();
    }
}
