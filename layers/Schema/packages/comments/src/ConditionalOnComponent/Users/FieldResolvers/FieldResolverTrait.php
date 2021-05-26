<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\Users\FieldResolvers;

use PoPSchema\Comments\ComponentConfiguration;

trait FieldResolverTrait
{
    /**
     * Only use it when `mustUserBeLoggedInToAddComment`.
     * Check on runtime (not via container) since this option can be changed in WP.
     */
    public function isServiceEnabled(): bool
    {
        return ComponentConfiguration::mustUserBeLoggedInToAddComment();
    }
}
