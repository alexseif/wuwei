<?php

namespace App\Twig\Components\Link;

use App\Twig\Components\Link;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/Link.html.twig')]
final class Create extends Link
{

    public string $text = 'Create';
}
