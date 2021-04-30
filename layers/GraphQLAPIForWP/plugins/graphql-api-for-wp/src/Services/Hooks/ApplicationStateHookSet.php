<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Hooks;

use PoP\Hooks\AbstractHookSet;

class ApplicationStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        // ----------------------------------------
        // Commented, since `is_admin` is added to
        // `CacheConfigurationManager.getNamespace()`
        // ----------------------------------------
        // $this->hooksAPI->addFilter(
        //     ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
        //     [$this, 'addComponent']
        // );
        // ----------------------------------------
    }
    /**
     * Admin and non-admin have different schemas
     *
     * @param string[] $components
     * @return string[]
     */
    public function addComponent(array $components): array
    {
        $components[] = $this->translationAPI->__('is admin:', 'graphql-api') . \is_admin();
        return $components;
    }
}
