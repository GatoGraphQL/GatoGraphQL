<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

class DependedOnActiveWordPressPlugin extends AbstractDependedOnWordPressPlugin
{
    public function __construct(
        string $name,
        string $file,
        public readonly ?string $versionConstraint = null,
        ?string $url = null,
    ) {
        parent::__construct(
            $name,
            $file,
            $url,
        );
    }
}
