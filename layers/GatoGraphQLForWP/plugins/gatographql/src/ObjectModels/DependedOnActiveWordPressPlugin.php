<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

final class DependedOnActiveWordPressPlugin extends AbstractDependedOnWordPressPlugin
{
    /**
     * @param string[] $alternativeFiles
     */
    public function __construct(
        string $name,
        string $file,
        public readonly ?string $versionConstraint = null,
        ?string $url = null,
        array $alternativeFiles = [],
    ) {
        parent::__construct(
            $name,
            $file,
            $url,
            $alternativeFiles,
        );
    }
}
