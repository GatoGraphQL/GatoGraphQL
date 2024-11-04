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
}
