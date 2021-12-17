<?php

declare(strict_types=1);

namespace PoPSchema\Users\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoPSchema\Users\Routing\RouteNatures;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            10,
            1
        );
    }

    public function augmentVarsProperties(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        $nature = $vars['nature'];
        $vars['routing-state']['is-user'] = $nature == RouteNatures::USER;
    }
}
