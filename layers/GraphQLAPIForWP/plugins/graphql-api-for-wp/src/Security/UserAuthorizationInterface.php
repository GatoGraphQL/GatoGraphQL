<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security;

interface UserAuthorizationInterface
{
    /**
     * The different ways to grant access to the schema editor
     *
     * @return string[]
     */
    public function getAccessSchemes(): array;
    /**
     * The capability needed to access the schema editor (i.e. access clients GraphiQL/Voyager
     * against the admin endpoint /wp-admin/?page=graphql_api, and execute queries against it).
     * If access to admin only, then it is "manage_options". Otherwise, it is "edit_posts"
     */
    public function getSchemaEditorAccessCapability(): string;
    public function canAccessSchemaEditor(): bool;
}
