<?php

namespace App\Twig\Components\Badge;

use App\Twig\Components\Badge;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent(template: 'components/Badge.html.twig')]
final class Enabled extends Badge
{
    public bool $enabled;

    #[PostMount]
    public function postMount(): void
    {
        $this->text = ($this->enabled) ? 'Enabled' : 'Disabled';
        $this->class = ($this->enabled) ? 'bg-success' : 'bg-secondary';
    }
}
