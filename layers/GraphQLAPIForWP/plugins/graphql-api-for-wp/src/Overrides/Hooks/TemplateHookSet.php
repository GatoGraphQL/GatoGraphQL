<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\Hooks;

use PoP\Root\App;
use PoP\EngineWP\Hooks\TemplateHookSet as UpstreamTemplateHookSet;

class TemplateHookSet extends UpstreamTemplateHookSet
{
    /**
     * Override as ->doingJSON can't be relied-upon anymore.
     */
    protected function useTemplate(): bool
    {
        return App::getState('executing-graphql') === true;
    }
}
