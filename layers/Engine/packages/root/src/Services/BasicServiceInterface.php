<?php

declare(strict_types=1);

namespace PoP\Root\Services;

use PoP\Root\Instances\InstanceManagerInterface;

/**
 * Interface needed to inject the InstanceManager
 * via a CompilerPass
 */
interface BasicServiceInterface
{
    /**
     * This will be called by Symfony via the CompilerPass
     */
    public function setInstanceManager(InstanceManagerInterface $instanceManager): void;
}
