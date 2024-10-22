<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\CustomPostTypes;

trait CustomPostTypeTrait
{
    protected function getCustomPostTypeNamespace(): string
    {
        return 'graphql';
    }
}