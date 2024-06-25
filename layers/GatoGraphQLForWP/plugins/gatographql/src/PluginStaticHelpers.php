<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\ExternalDependencyWrappers\Composer\Semver\SemverWrapper;
use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginVersionHelpers;

use function get_file_data;
use function is_multisite;

class PluginStaticHelpers
{
    /**
     * @var string[]|null
     */
    private static ?array $activeWordPressPluginFiles = null;
    /**
     * @var string[]|null
     */
    private static ?array $activeWordPressPluginSlugs = null;

    private static function getGitHubRepoDocsRootURL(): string
    {
        return sprintf(
            'https://raw.githubusercontent.com/%s/%s',
            PluginMetadata::DOCS_GITHUB_REPO_OWNER,
            PluginMetadata::DOCS_GITHUB_REPO_NAME
        );
    }

    private static function getGitHubRepoDocsBranchOrTag(): string
    {
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();
        return PluginVersionHelpers::isDevelopmentVersion($mainPluginVersion)
            ? PluginMetadata::DOCS_GIT_BASE_BRANCH
            : $mainPluginVersion;
    }

    public static function getGitHubRepoDocsRootPathURL(): string
    {
        return self::getGitHubRepoDocsRootURL() . '/' . self::getGitHubRepoDocsBranchOrTag() . '/layers/GatoGraphQLForWP/plugins/gatographql';
    }

    /**
     * If param $pluginFileOrSlug ends with ".php", then it's the
     * plugin file, otherwise it's the plugin slug.
     */
    public static function isWordPressPluginActive(string $pluginFileOrSlug): bool
    {
        if (str_ends_with($pluginFileOrSlug, '.php')) {
            $pluginFile = $pluginFileOrSlug;
            return in_array($pluginFile, static::getActiveWordPressPluginFiles());
        }
        $pluginSlug = $pluginFileOrSlug;
        return in_array($pluginSlug, static::getActiveWordPressPluginSlugs());
    }

    /**
     * @return string[]
     */
    protected static function getActiveWordPressPluginFiles(): array
    {
        if (self::$activeWordPressPluginFiles === null) {
            self::$activeWordPressPluginFiles = get_option('active_plugins', []);
            if (is_multisite()) {
                /** @var string[] */
                $activeNetworkWordPressPluginFiles = array_keys(get_network_option(0, 'active_sitewide_plugins', []));
                self::$activeWordPressPluginFiles = array_values(array_unique(array_merge(
                    self::$activeWordPressPluginFiles,
                    $activeNetworkWordPressPluginFiles
                )));
            }
        }
        return self::$activeWordPressPluginFiles;
    }

    /**
     * @return string[]
     */
    protected static function getActiveWordPressPluginSlugs(): array
    {
        if (self::$activeWordPressPluginSlugs === null) {
            self::$activeWordPressPluginSlugs = array_map(
                function (string $pluginFile): string {
                    $pos = strpos($pluginFile, '/');
                    if ($pos === false) {
                        return $pluginFile;
                    }
                    return substr($pluginFile, 0, $pos);
                },
                static::getActiveWordPressPluginFiles()
            );
        }
        return self::$activeWordPressPluginSlugs;
    }

    public static function doesActivePluginSatisfyVersionConstraint(
        string $pluginFile,
        string $versionConstraint
    ): bool {
        // Use "*" to mean "any version" (so it's always allowed)
        if ($versionConstraint === '*') {
            return true;
        }

        $pluginDir = dirname(PluginApp::getMainPlugin()->getPluginDir());
        $pluginAbsolutePathFile = $pluginDir . '/' . $pluginFile;
        $pluginData = get_file_data($pluginAbsolutePathFile, array('Version'), 'plugin');
        $pluginVersion = $pluginData[0];
        if ($pluginVersion === '') {
            return false;
        }
        return SemverWrapper::satisfies($pluginVersion, $versionConstraint);
    }

    /**
     * Convert the response header entries, from:
     *
     *   string[] as: `{header name}: {header value}`
     *
     * To:
     *
     *   array<string,string> as: `header name` => `header value`
     *
     * @param string[] $entries `{header name}: {header value}`
     * @return array<string,string> `Header name` => `Header value`
     */
    public static function getResponseHeadersFromEntries(array $entries): array
    {
        $responseHeaders = [];
        foreach ($entries as $entry) {
            $entry = trim($entry);
            $separatorPos = strpos($entry, ':');
            if ($separatorPos === false) {
                $headerName = $entry;
                $headerValue = '';
            } else {
                $headerName = trim(substr($entry, 0, $separatorPos));
                $headerValue = trim(substr($entry, $separatorPos + strlen(':')));
            }
            if ($headerName === '') {
                continue;
            }
            $responseHeaders[$headerName] = $headerValue;
        }
        return $responseHeaders;
    }
}
