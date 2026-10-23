<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

interface MainPluginInterface extends PluginInterface
{
    public function getPluginWebsiteURL(): string;
    public function getPluginDomainURL(): string;
    /**
     * A base64-encoded SVG using a data URI, which will be colored to match
     * the color scheme. This should begin with 'data:image/svg+xml;base64,'.
     *
     * @see `add_menu_page`
     */
    public function getPluginIconSVG(): string;

    /**
     * The stylesheet that overrides the CSS variables of the admin
     * screens (eg: the accent color), loaded after Gato GraphQL's own,
     * or `null` to keep these.
     *
     * @return array{plugin:PluginInterface,path:string}|null The plugin, and the stylesheet's path within its folder
     */
    public function getAdminUIThemeStylesheet(): ?array;

    /**
     * Install a feature's data, recording its version once done, and
     * logging and putting off the next attempt when it fails.
     */
    public function installFeature(FeatureInstallerInterface $featureInstaller): void;

    /**
     * Whether installing the feature failed recently, so that trying again
     * now would fail the same way.
     */
    public function isFeatureInstallationOnHold(FeatureInstallerInterface $featureInstaller): bool;
}
