<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

interface MainPluginInterface extends PluginInterface
{
    public function getPluginWebsiteURL(): string;
}
