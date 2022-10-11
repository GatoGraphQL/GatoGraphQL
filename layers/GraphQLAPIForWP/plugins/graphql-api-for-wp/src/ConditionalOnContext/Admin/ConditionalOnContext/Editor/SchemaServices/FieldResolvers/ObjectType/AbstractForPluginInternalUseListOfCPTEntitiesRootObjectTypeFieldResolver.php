<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use GraphQLAPI\GraphQLAPI\Checkpoints\PluginInternalUseCheckpoint;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * These fields must be accessed by the plugin only,
 * they are unavailable otherwise (even to the admin
 * user in the wp-admin GraphiQL client).
 */
abstract class AbstractForPluginInternalUseListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractListOfCPTEntitiesRootObjectTypeFieldResolver
{
    private ?PluginInternalUseCheckpoint $pluginInternalUseCheckpoint = null;

    final public function setPluginInternalUseCheckpoint(PluginInternalUseCheckpoint $pluginInternalUseCheckpoint): void
    {
        $this->pluginInternalUseCheckpoint = $pluginInternalUseCheckpoint;
    }
    final protected function getPluginInternalUseCheckpoint(): PluginInternalUseCheckpoint
    {
        /** @var PluginInternalUseCheckpoint */
        return $this->pluginInternalUseCheckpoint ??= $this->instanceManager->getInstance(PluginInternalUseCheckpoint::class);
    }

    /**
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): array {
        $validationCheckpoints = parent::getValidationCheckpoints(
            $objectTypeResolver,
            $fieldDataAccessor,
            $object,
        );
        $validationCheckpoints[] = $this->getPluginInternalUseCheckpoint();
        return $validationCheckpoints;
    }    
}
