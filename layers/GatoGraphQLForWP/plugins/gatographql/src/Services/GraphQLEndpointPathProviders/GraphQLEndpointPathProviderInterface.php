<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\GraphQLEndpointPathProviders;

interface GraphQLEndpointPathProviderInterface
{
    public function getPath(): string;

    public function isPublic(): bool;
}
