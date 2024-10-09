<?php

declare(strict_types=1);

namespace Nextstore\SyliusGiftCardPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AccountMenuListener
{
    public function addAccountMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $this->addGiftCardsMenu($menu);
    }

    private function addGiftCardsMenu(ItemInterface $menu): void
    {
        $menu->addChild('gift_cards', ['route' => 'nextstore_sylius_gift_card_shop_gift_card_index'])
            ->setLabel('nextstore_sylius_gift_card.ui.gift_cards')
            ->setLabelAttribute('icon', 'gift');
    }
}
