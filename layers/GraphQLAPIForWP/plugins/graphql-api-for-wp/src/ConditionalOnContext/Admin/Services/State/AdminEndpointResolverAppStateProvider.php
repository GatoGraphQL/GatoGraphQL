<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\State;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointResolvers\AdminEndpointResolver;
use GraphQLAPI\GraphQLAPI\Services\EndpointResolvers\EndpointResolverInterface;
use GraphQLAPI\GraphQLAPI\State\AbstractEndpointResolverAppStateProvider;

class AdminEndpointResolverAppStateProvider extends AbstractEndpointResolverAppStateProvider
{
    private ?AdminEndpointResolver $adminEndpointResolver = null;

    final public function setAdminEndpointResolver(AdminEndpointResolver $adminEndpointResolver): void
    {
        $this->adminEndpointResolver = $adminEndpointResolver;
    }
    final protected function getAdminEndpointResolver(): AdminEndpointResolver
    {
        return $this->adminEndpointResolver ??= $this->instanceManager->getInstance(AdminEndpointResolver::class);
    }

    protected function getEndpointResolver(): EndpointResolverInterface
    {
        return $this->getAdminEndpointResolver();
    }
}
