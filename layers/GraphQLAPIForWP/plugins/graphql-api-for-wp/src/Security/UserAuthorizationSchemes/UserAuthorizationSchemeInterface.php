<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

interface UserAuthorizationSchemeInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function getSchemaEditorAccessCapability(): string;
}
