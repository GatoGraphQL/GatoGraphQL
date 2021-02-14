<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Menus;

use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\AbstractMenuPage;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

/**
 * Admin menu class
 */
abstract class AbstractMenu extends AbstractAutomaticallyInstantiatedService
{
    abstract public static function getName(): string;

    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function initialize(): void
    {
        /**
         * Low priority to execute before adding the menus for the CPTs
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
