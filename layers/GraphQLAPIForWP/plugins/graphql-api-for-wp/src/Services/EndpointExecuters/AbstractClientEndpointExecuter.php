<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractClientEndpointExecuter extends AbstractEndpointEndpointExecuter
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }
    
    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }

        // Check the expected ?view=... is requested
        $view = $_REQUEST[RequestParams::VIEW] ?? null;
        if ($view !== $this->getView()) {
            return false;
        }

        return true;
    }

    protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType;
    }

    abstract protected function getView(): string;
}
