<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolution;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\Hooks\HooksAPIInterface;

class MutationResolutionManager implements MutationResolutionManagerInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $results = [];

    public function __construct(
        HooksAPIInterface $hooksAPI
    ) {
        $hooksAPI->addAction(
            'augmentVarsProperties',
            [$this, 'clearResults']
        );
    }

    public function clearResults(): void
    {
        $this->results = [];
    }

    /**
     * @param mixed $result
     */
    public function setResult(string $class, $result): void
    {
        $this->results[$class] = $result;
    }

    /**
     * @return mixed
     */
    public function getResult(string $class)
    {
        /**
         * Calling `setResult` uses get_called_class(), so if the class was overriden,
         * it uses that one, but `getResult` uses the original class, so they will mismatch!
         * To avoid this problem, get the actual implementation class for this class
         */
        $instanceManager = InstanceManagerFacade::getInstance();
        $class = $instanceManager->getImplementationClass($class);
        return $this->results[$class];
    }
}
