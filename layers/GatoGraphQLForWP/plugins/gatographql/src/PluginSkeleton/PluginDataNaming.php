<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

/**
 * How the plugin's namespace is joined to the name of everything it stores.
 *
 * Two sides must agree on this, and they are far apart: the namespacer
 * services write the names when the plugin saves an option, a transient or a
 * meta key, and {@see PluginUninstaller} matches them again to remove them
 * when the plugin is deleted. Were the separator spelled out in both, changing
 * it would quietly orphan every row written under the old one.
 *
 * It is static, and not a service, because `uninstall.php` runs with
 * WordPress loaded but the plugin not bootstrapped: there is no container
 * there to resolve a service from, and this is the one thing it still needs.
 * A service would therefore be overridable on the side which writes the names
 * and not on the side which deletes them, and an override would orphan
 * precisely the rows it had itself created.
 *
 * Overriding how a name is built is done a level up instead, by replacing
 * {@see OptionNamespacerInterface} or {@see MetaNamespacerInterface} with
 * another implementation, which is a decision the whole site is built with
 * rather than one taken per request.
 *
 * Custom post type and taxonomy names are deliberately not built from here.
 * They carry a namespace of their own
 * {@see PluginInterface::getPluginNamespaceForEntityTypeNames()}, they are
 * persisted in `post_type` columns rather than matched by prefix, and the
 * uninstaller removes their entries by the names it recorded, never by shape.
 */
class PluginDataNaming
{
    /**
     * What separates the plugin's namespace from the rest of the name.
     */
    public final const NAMESPACE_SEPARATOR = '-';

    /**
     * WordPress hides a meta key from its UIs when it starts with this.
     */
    public final const PRIVATE_META_KEY_PREFIX = '_';

    /**
     * A transient is a row in the Options table like any other, under one of
     * the names WordPress gives it. The empty string is the option itself.
     *
     * @var string[]
     */
    public final const OPTION_NAME_PREFIXES = [
        '',
        '_transient_',
        '_transient_timeout_',
        '_site_transient_',
        '_site_transient_timeout_',
    ];

    public static function getOptionNamePrefix(string $pluginNamespace): string
    {
        return $pluginNamespace . self::NAMESPACE_SEPARATOR;
    }

    public static function namespaceOptionName(string $pluginNamespace, string $option): string
    {
        return self::getOptionNamePrefix($pluginNamespace) . $option;
    }

    /**
     * Every name under which the plugin's options and transients are stored.
     *
     * @return string[]
     */
    public static function getOptionNamePrefixes(string $pluginNamespace): array
    {
        return array_map(
            static fn (string $prefix): string => $prefix . self::getOptionNamePrefix($pluginNamespace),
            self::OPTION_NAME_PREFIXES
        );
    }

    public static function getMetaKeyPrefix(string $pluginNamespace, bool $prefixUnderscore = true): string
    {
        return ($prefixUnderscore ? self::PRIVATE_META_KEY_PREFIX : '')
            . self::getOptionNamePrefix($pluginNamespace);
    }

    public static function namespaceMetaKey(
        string $pluginNamespace,
        string $metaKey,
        bool $prefixUnderscore = true,
    ): string {
        return self::getMetaKeyPrefix($pluginNamespace, $prefixUnderscore) . $metaKey;
    }
}
