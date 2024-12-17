<?php
// src/Twig/Components/Link.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Link
{
    public string $link;
    public string $class = 'btn btn-outline-light';
    public string $text;
}
