<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Hooks;

use PoP\Engine\FieldResolvers\ObjectType\OperatorGlobalObjectTypeFieldResolver;
use PoP\BasicService\AbstractHookSet;

class ApplicationStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
            OperatorGlobalObjectTypeFieldResolver::HOOK_SAFEVARS,
            [$this, 'setSafeVars'],
            10,
            1
        );
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function setSafeVars(array $vars_in_array): void
    {
        // Remove the queried object
        $safeVars = &$vars_in_array[0];
        unset($safeVars['routing-state']['queried-object']);
    }
}
