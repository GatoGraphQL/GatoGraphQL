<?php

declare(strict_types=1);

namespace PoP\Root\Services;

use PoP\Root\Instances\InstanceManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithInstanceManagerServiceTrait
{
    protected InstanceManagerInterface $instanceManager;

    #[Required]
    final public function setInstanceManager(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }
    final protected function getInstanceManager(): InstanceManagerInterface
    {
        return $this->instanceManager;
    }
}
