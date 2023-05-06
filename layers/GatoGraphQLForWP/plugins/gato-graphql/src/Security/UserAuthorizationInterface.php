<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Security;

interface UserAuthorizationInterface
{
    /**
     * The capability needed to access the schema editor (i.e. access clients GraphiQL/Voyager
     * against the admin endpoint /wp-admin/?page=gato_graphql, and execute queries against it).
     * If access to admin only, then it is "manage_options". Otherwise, it is "edit_posts"
     */
    public function getSchemaEditorAccessCapability(): string;
    public function canAccessSchemaEditor(): bool;
}
