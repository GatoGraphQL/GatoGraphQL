<?php

declare(strict_types=1);

function convertRelativeToFullPath(?string $relativePath = null): string
{
    $monorepoDir = dirname(__DIR__, 4);
    $pluginDir = $monorepoDir . '/layers/GatoGraphQLForWP/plugins/gatographql';
    if ($relativePath === null) {
        return $pluginDir;
    }
    return $pluginDir . '/' . $relativePath;
}
