<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Services\Menus\AbstractMenu;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

/**
 * Admin menu class
 */
abstract class AbstractMenuPageAttacher extends AbstractAutomaticallyInstantiatedService
{
    protected InstanceManagerInterface $instanceManager;

    #[Required]
    public function autowireAbstractMenuPageAttacher(InstanceManagerInterface $instanceManager)
    {
        $this->instanceManager = $instanceManager;
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
