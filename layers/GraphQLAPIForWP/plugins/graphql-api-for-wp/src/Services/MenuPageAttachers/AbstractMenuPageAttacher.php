<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Services\Menus\MenuInterface;
use PoP\BasicService\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

/**
 * Admin menu class
 */
abstract class AbstractMenuPageAttacher extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

    abstract public function getMenu(): MenuInterface;

    protected function getMenuName(): string
    {
        return $this->getMenu()->getName();
    }

    /**
     * Priority to add the submenu page. It must be above "5",
     * which is the priority for the menu "GraphQL API" to be added
     */
    protected function getPriority(): int
    {
        return 30;
    }

    /**
     * Initialize the endpoints
     */
    public function initialize(): void
    {
        \add_action(
            'admin_menu',
            [$this, 'addMenuPages'],
            $this->getPriority()
        );
    }

    abstract public function addMenuPages(): void;
}
