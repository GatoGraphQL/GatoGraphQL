<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPageAttachers;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Services\Menus\MenuInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;

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
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        return AppHelpers::isMainAppThread();
    }

    /**
     * Priority to add the submenu page. It must be above "5",
     * which is the priority for the menu "Gato GraphQL" to be added
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
            $this->addMenuPages(...),
            $this->getPriority()
        );
    }

    abstract public function addMenuPages(): void;
}
