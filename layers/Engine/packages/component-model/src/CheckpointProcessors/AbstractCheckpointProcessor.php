<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Services\WithInstanceManagerServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCheckpointProcessor implements CheckpointProcessorInterface
{
    use WithInstanceManagerServiceTrait;
    
    protected ?HooksAPIInterface $hooksAPI = null;

    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->getInstanceManager()->getInstance(HooksAPIInterface::class);
    }

    //#[Required]
    final public function autowireAbstractCheckpointProcessor(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }

    /**
     * By default there's no problem
     */
    public function validateCheckpoint(array $checkpoint): ?Error
    {
        return null;
    }
}
