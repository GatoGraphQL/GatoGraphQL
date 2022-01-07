<?php

declare(strict_types=1);

namespace PoP\Root\Component;

interface ComponentAppStateInterface
{
    public function initialize(array &$state): mixed;
}
