<?php

namespace App\Twig;

use App\Service\MenuManager;
use App\Service\MenuItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function __construct(private readonly MenuManager $menuManager, private readonly UrlGeneratorInterface $router, private readonly RequestStack $requestStack)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_menu', $this->renderMenu(...), ['is_safe' => ['html']]),
        ];
    }

    public function renderMenu(): string
    {
        $menuItems = $this->menuManager->getMenuItems();
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');

        return $this->renderMenuItems($menuItems, $currentRoute);
    }

    private function renderMenuItems(array $menuItems, string $currentRoute): string
    {
        $html = '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';
        foreach ($menuItems as $item) {
            $isActive = $this->isActive($item, $currentRoute);
            if ($item->isDropdown()) {
                $html .= '<li class="nav-item dropdown">';
                $html .= sprintf(
                    '<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><span class="%s"></span> %s</a>',
                    $item->getIcon(),
                    $item->getLabel()
                );
                $html .= '<ul class="dropdown-menu">';
                foreach ($item->getItems() as $subItem) {
                    if ($subItem->isDivider()) {
                        $html .= '<li class="dropdown-divider"></li>';
                    } else {
                        $url = $this->router->generate($subItem->getPath());
                        $subIsActive = $this->isActive($subItem, $currentRoute);
                        $html .= sprintf(
                            '<li><a class="dropdown-item %s" href="%s"><span class="%s"></span> %s</a></li>',
                            $subIsActive ? 'active' : '',
                            $url,
                            $subItem->getIcon(),
                            $subItem->getLabel()
                        );
                    }
                }
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                $url = $this->router->generate($item->getPath());
                $html .= sprintf(
                    '<li class="nav-item"><a class="nav-link %s" href="%s"><span class="%s"></span> %s</a></li>',
                    $isActive ? 'active' : '',
                    $url,
                    $item->getIcon(),
                    $item->getLabel()
                );
            }
        }
        $html .= '</ul>';

        return $html;
    }

    private function isActive(MenuItem $item, string $currentRoute): bool
    {
        if ($item->getPath() === $currentRoute) {
            return true;
        }

        foreach ($item->getItems() as $subItem) {
            if ($this->isActive($subItem, $currentRoute)) {
                return true;
            }
        }

        return false;
    }
}
