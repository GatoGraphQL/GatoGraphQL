<?php

declare(strict_types=1);

namespace PoP\Root\Services;

use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Instances\InstanceManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait WithInstanceManagerServiceTrait
{
    private ?InstanceManagerInterface $instanceManager = null;

    //#[Required]
    public function setInstanceManager(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }
    protected function getInstanceManager(): InstanceManagerInterface
    {
        return $this->instanceManager ??= InstanceManagerFacade::getInstance();
    }
}
