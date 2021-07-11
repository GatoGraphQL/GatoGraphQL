<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Services\Menus\AbstractMenu;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

/**
 * Admin menu class
 */
abstract class AbstractMenuPageAttacher extends AbstractAutomaticallyInstantiatedService
{
    public function __construct(
        protected InstanceManagerInterface $instanceManager,
    ) {
    }

    abstract public function getMenuClass(): string;

    protected function getMenu(): AbstractMenu
    {
        $menuClass = $this->getMenuClass();
        /** @var AbstractMenu */
        return $this->instanceManager->getInstance($menuClass);
    }

    protected function getMenuName(): string
    {
        return $this->getMenu()->getName();
    }

    /**
     * Initialize the endpoints
     */
    public function initialize(): void
    {
        /**
         * Low priority to execute before adding the menus for the CPTs,
         * but still after adding the Menu (priority 5)
         */
        \add_action(
            'admin_menu',
            [$this, 'addMenuPagesTop'],
            9
        );
        /**
         * High priority to execute after adding the menus for the CPTs
         */
        \add_action(
            'admin_menu',
            [$this, 'addMenuPagesBottom'],
            20
        );
    }

    public function addMenuPagesTop(): void
    {
        // Initially empty
    }

    public function addMenuPagesBottom(): void
    {
        // Initially empty
    }
}
