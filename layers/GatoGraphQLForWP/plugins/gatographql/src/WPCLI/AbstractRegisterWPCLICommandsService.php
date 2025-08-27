<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\WPCLI;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use WP_CLI;

abstract class AbstractRegisterWPCLICommandsService extends AbstractAutomaticallyInstantiatedService
{
    public function initialize(): void
    {
        // Only register WP-CLI commands if WP-CLI is available
        if (!defined('WP_CLI') || !constant('WP_CLI') || !class_exists('WP_CLI')) {
            return;
        }

        // Register the main command
        call_user_func(
            WP_CLI::add_command(...),
            $this->getCommandName(),
            $this->getCommandClass()
        );
    }

    abstract protected function getCommandName(): string;

    /**
     * @return class-string<AbstractWPCLICommand>
     */
    abstract protected function getCommandClass(): string;
}
