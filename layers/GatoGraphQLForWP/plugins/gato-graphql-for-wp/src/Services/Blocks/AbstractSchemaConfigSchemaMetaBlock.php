<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

abstract class AbstractSchemaConfigSchemaMetaBlock extends AbstractSchemaConfigSchemaAllowAccessToEntriesBlock
{
    protected function getRenderBlockLabel(): string
    {
        return $this->__('Meta keys', 'gato-graphql');
    }
}
