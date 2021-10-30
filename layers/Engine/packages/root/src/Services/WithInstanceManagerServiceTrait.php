<?php

declare(strict_types=1);

namespace PoP\Root\Services;

use PoP\Root\Instances\InstanceManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithInstanceManagerServiceTrait
{
    protected InstanceManagerInterface $instanceManager;

    #[Required]
    public function setInstanceManager(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }
}
