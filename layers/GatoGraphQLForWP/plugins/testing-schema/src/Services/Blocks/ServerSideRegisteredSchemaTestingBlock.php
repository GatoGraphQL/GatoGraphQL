<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Services\Blocks;

class ServerSideRegisteredSchemaTestingBlock extends AbstractServerSideRegisteredOrNotSchemaTestingBlock
{
    protected function getBlockName(): string
    {
        return 'server-side-registered-block-schema-testing';
    }
}
