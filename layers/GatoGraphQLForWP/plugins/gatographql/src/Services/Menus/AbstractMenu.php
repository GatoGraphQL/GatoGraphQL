<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Menus;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractMenu extends AbstractAutomaticallyInstantiatedService implements MenuInterface
{
    /**
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        return AppHelpers::isMainAppThread();
    }

    /**
     * Initialize the endpoints
     */
    public function initialize(): void
    {
        \add_action(
            'admin_menu',
            $this->addMenuPage(...),
            5
        );
    }
}
