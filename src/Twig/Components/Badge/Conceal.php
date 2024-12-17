<?php

namespace App\Twig\Components\Badge;

use App\Twig\Components\Badge;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent(template: 'components/Badge.html.twig')]
final class Conceal extends Badge
{
    public bool $conceal;

    #[PostMount]
    public function postMount(): void
    {
        $this->text = ($this->conceal) ? 'Conceal' : 'Reveal';
        $this->class = ($this->conceal) ? 'bg-secondary' : 'bg-success';
    }
}
