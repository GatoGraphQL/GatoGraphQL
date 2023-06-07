<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractBlock;

class NotServerSideRegisteredSchemaTestingBlock extends AbstractServerSideRegisteredOrNotSchemaTestingBlock
{
    protected function getBlockName(): string
    {
        return 'not-server-side-registered-schema-testing';
    }

    protected function registerBlockServerSide(): bool
    {
        return false;
    }
}
