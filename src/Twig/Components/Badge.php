<?php
// src/Twig/Components/Badge.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Badge
{
    public string $text;
    public string $class;
}
