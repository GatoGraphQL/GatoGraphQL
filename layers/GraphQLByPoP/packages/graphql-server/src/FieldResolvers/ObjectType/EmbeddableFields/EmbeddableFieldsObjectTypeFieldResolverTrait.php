<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\EmbeddableFields;

use PoP\Engine\App;
use PoP\API\Component as APIComponent;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;

trait EmbeddableFieldsObjectTypeFieldResolverTrait
{
    /**
     * Only use it when "embeddable fields" is enabled.
     *
     * Check on runtime (not via container) since this option can be
     * assigned to the Schema Configuration in the GraphQL API plugin.
     */
    public function isServiceEnabled(): bool
    {
        /** @var APIComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(APIComponent::class)->getConfiguration();
        return $componentConfiguration->enableEmbeddableFields();
    }
}
