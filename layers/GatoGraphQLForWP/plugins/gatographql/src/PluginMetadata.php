<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

final class PluginMetadata
{
    final public const DOCS_GIT_BASE_BRANCH = 'master';
    final public const DOCS_GITHUB_REPO_OWNER = 'GatoGraphQL';
    final public const DOCS_GITHUB_REPO_NAME = 'GatoGraphQL';

    final public const PLUGIN_NAMESPACE = 'gatographql';
    final public const PLUGIN_NAMESPACE_FOR_ENTITY_TYPE_NAMES = 'graphql';
    /**
     * @deprecated 19.3.0 Use PLUGIN_NAMESPACE_FOR_ENTITY_TYPE_NAMES
     */
    final public const PLUGIN_NAMESPACE_FOR_DB = self::PLUGIN_NAMESPACE_FOR_ENTITY_TYPE_NAMES;
    final public const PLUGIN_NAMESPACE_FOR_CLASS = 'GatoGraphQL';
}
