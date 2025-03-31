<?php

namespace App\User\EventListener;

use App\User\Security\RememberMe\CustomTokenProvider;
use Symfony\Component\Security\Http\Event\LogoutEvent;


class LogoutEventListener
{
    private CustomTokenProvider $tokenProvider;

    public function __construct(CustomTokenProvider $tokenProvider)
    {
        $this->tokenProvider = $tokenProvider;
    }

    public function onLogout(LogoutEvent $event): void
    {
        $request = $event->getRequest();
        $rememberMeCookie = $request->cookies->get('REMEMBERME');

        if ($rememberMeCookie) {
            // Extract the "series" from the Remember Me cookie and delete the token
            $series = $this->extractSeriesFromCookie($rememberMeCookie);
            $this->tokenProvider->deleteTokenBySeries($series);
        }
    }

    private function extractSeriesFromCookie(string $cookie): string
    {
        // Extract the series from the cookie (logic depends on your token format)
        return explode('|', $cookie)[0];
    }
}
