<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class AppExtension extends AbstractExtension
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('create_new_link', [$this, 'createNewLink'], ['is_safe' => ['html']]),
            new TwigFunction('edit_link', [$this, 'editLink'], ['is_safe' => ['html']]),
            new TwigFunction('show_link', [$this, 'showLink'], ['is_safe' => ['html']]),
            new TwigFunction('back_link', [$this, 'backLink'], ['is_safe' => ['html']]),
            new TwigFunction('badge_enabled', [$this, 'badgeEnabled'], ['is_safe' => ['html']]),
            new TwigFunction('badge_conceal', [$this, 'badgeConceal'], ['is_safe' => ['html']]),
        ];
    }

    private function renderLink(string $link, string $linkText, string $linkClass): string
    {
        return $this->twig->render('components/link.html.twig', [
            'link' => $link,
            'linkClass' => $linkClass,
            'linkText' => $linkText,
        ]);
    }

    public function createNewLink(string $link, string $linkText = 'Create new', string $linkClass = 'btn btn-outline-light'): string
    {
        return $this->renderLink($link, $linkText, $linkClass);
    }

    public function editLink(string $link, string $linkText = 'Edit', string $linkClass = 'btn btn-outline-light'): string
    {
        return $this->renderLink($link, $linkText, $linkClass);
    }

    public function showLink(string $link, string $linkText = 'Show', string $linkClass = 'btn btn-outline-light'): string
    {
        return $this->renderLink($link, $linkText, $linkClass);
    }
    public function backLink(string $link, string $linkText = 'back', string $linkClass = 'btn btn-outline-light'): string
    {
        return $this->renderLink($link, $linkText, $linkClass);
    }




    public function badgeEnabled(bool $enabled): string
    {
        return $this->twig->render('components/badge_bool.html.twig', [
            'enabled' => $enabled,
            'true_text' => 'Enabled',
            'false_text' => 'Disabled',

        ]);
    }
    public function badgeConceal(bool $conceal): string
    {
        return $this->twig->render('components/badge_bool.html.twig', [
            'enabled' => $conceal,
            'trueText' => 'Conceal',
            'falseText' => 'Reveal',
            'trueClass' => 'bg-secondary',
            'falseClass' => 'bg-success'
        ]);
    }
}
