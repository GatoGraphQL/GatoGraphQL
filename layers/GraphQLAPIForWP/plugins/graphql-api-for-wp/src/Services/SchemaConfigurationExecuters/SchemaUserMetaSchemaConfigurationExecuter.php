<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaUserMetaBlock;
use PoPCMSSchema\UserMeta\Environment as UserMetaEnvironment;
use PoPCMSSchema\UserMeta\Module as UserMetaModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaUserMetaSchemaConfigurationExecuter extends AbstractSchemaMetaSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaUserMetaBlock $schemaConfigUsersBlock = null;

    final public function setSchemaConfigSchemaUserMetaBlock(SchemaConfigSchemaUserMetaBlock $schemaConfigUsersBlock): void
    {
        $this->schemaConfigUsersBlock = $schemaConfigUsersBlock;
    }
    final protected function getSchemaConfigSchemaUserMetaBlock(): SchemaConfigSchemaUserMetaBlock
    {
        /** @var SchemaConfigSchemaUserMetaBlock */
        return $this->schemaConfigUsersBlock ??= $this->instanceManager->getInstance(SchemaConfigSchemaUserMetaBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_USER_META;
    }

    protected function getEntriesHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            UserMetaModule::class,
            UserMetaEnvironment::USER_META_ENTRIES
        );
    }

    protected function getBehaviorHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            UserMetaModule::class,
            UserMetaEnvironment::USER_META_BEHAVIOR
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaUserMetaBlock();
    }
}
