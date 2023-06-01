<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

class DependedWordPressPlugin
{
    public function __construct(
        public readonly string $name,
        public readonly string $pluginSlug,
        public readonly ?string $versionConstraint = null,
        public readonly ?string $url = null,
    ) {
    }
}
