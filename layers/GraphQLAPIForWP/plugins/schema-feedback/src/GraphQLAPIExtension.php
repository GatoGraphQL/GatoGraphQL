<?php

declare(strict_types=1);

namespace GraphQLAPI\SchemaFeedback;

use GraphQLAPI\PluginSkeleton\AbstractExtension;

class GraphQLAPIExtension extends AbstractExtension
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    /**
     * Plugin name
     */
    protected function getPluginName(): string
    {
        return \__('GraphQL API - Schema Feedback', 'graphql-api-schema-feedback');
    }

    /**
     * Add Component classes to be initialized
     *
     * @return string[] List of `Component` class to initialize
     */
    public function getComponentClassesToInitialize(): array
    {
        return [
            \PoP\SchemaFeedback\Component::class,
        ];
    }
}
