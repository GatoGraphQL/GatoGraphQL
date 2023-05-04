<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

use WP_User;

interface UserAuthorizationSchemeInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function getPriority(): int;
    public function canAccessSchemaEditor(WP_User $user): bool;
    public function getSchemaEditorAccessMinimumRequiredCapability(): string;
}
