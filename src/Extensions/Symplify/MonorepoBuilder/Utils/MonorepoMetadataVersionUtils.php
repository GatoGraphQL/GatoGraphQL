<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils;

use PoP\PoP\Monorepo\MonorepoMetadata;

/**
 * MonorepoMetadata::VERSION can have "-dev" (in the source code
 * for development) or not (in the converted code for production).
 *
 * This helper helps abstract these, and always access the required
 * DEV or PROD version.
 *
 * @deprecated Using the incoming version instead, so the same logic also works on a downstream monorepo
 */
final class MonorepoMetadataVersionUtils
{
    /**
     * @deprecated
     */
    public function getDevVersion(): string
    {
        if (str_ends_with(MonorepoMetadata::VERSION, '-dev')) {
            return MonorepoMetadata::VERSION;
        }
        return MonorepoMetadata::VERSION . '-dev';
    }

    /**
     * @deprecated
     */
    public function getProdVersion(): string
    {
        if (str_ends_with(MonorepoMetadata::VERSION, '-dev')) {
            return substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev'));
        }
        return MonorepoMetadata::VERSION;
    }
}
