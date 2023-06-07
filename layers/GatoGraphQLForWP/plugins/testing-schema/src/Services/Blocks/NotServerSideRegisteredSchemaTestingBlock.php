<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Services\Blocks;

class NotServerSideRegisteredSchemaTestingBlock extends AbstractServerSideRegisteredOrNotSchemaTestingBlock
{
    protected function getBlockName(): string
    {
        return 'not-server-side-registered-block-schema-testing';
    }

    protected function registerBlockServerSide(): bool
    {
        return false;
    }
}
