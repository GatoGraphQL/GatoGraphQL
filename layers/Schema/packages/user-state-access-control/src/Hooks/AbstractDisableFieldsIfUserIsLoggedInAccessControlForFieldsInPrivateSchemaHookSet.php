<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\Hooks;

use PoP\ComponentModel\State\ApplicationState;
use PoP\AccessControl\Hooks\AbstractAccessControlForFieldsInPrivateSchemaHookSet;

abstract class AbstractDisableFieldsIfUserIsLoggedInAccessControlForFieldsInPrivateSchemaHookSet extends AbstractAccessControlForFieldsInPrivateSchemaHookSet
{
    protected function enabled(): bool
    {
        // If it is not a private schema, then already do not enable
        if (!parent::enabled()) {
            return false;
        }

        /**
         * If the user is logged in, then do not register field names
         */
        $vars = ApplicationState::getVars();
        return $vars['global-userstate']['is-user-logged-in'];
    }
}
