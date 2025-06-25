<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

final class DependedOnActiveWordPressTheme extends AbstractDependedOnWordPressTheme
{
    /**
     * @param string[] $alternativeSlugs
     */
    public function __construct(
        string $name,
        string $slug,
        public readonly ?string $versionConstraint = null,
        array $alternativeSlugs = [],
        ?string $url = null,
    ) {
        parent::__construct(
            $name,
            $slug,
            $alternativeSlugs,
            $url,
        );
    }
}
