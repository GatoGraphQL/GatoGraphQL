<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Registries\AbstractServiceDefinitionIDRegistry;

class DataStructureManager extends AbstractServiceDefinitionIDRegistry implements DataStructureManagerInterface
{
    protected bool $initialized = false;
    /**
     * @var array<string, DataStructureFormatterInterface>
     */
    public array $formatters = [];

    protected function maybeInitialize(): void
    {
        if (!$this->initialized) {
            $this->initialized = true;
            $instanceManager = InstanceManagerFacade::getInstance();
            foreach ($this->getServiceDefinitionIDs() as $serviceDefinitionID) {
                /** @var DataStructureFormatterInterface */
                $service = $instanceManager->getInstance($serviceDefinitionID);
                $this->add($service);
            }
        }
    }

    protected function add(DataStructureFormatterInterface $formatter): void
    {
        $this->formatters[$formatter::getName()] = $formatter;
    }

    public function getDataStructureFormatter(string $name = null): DataStructureFormatterInterface
    {
        $this->maybeInitialize();
        // Return the formatter if it exists
        if ($name && isset($this->formatters[$name])) {
            return $this->formatters[$name];
        };

        // Return the one saved in the vars
        $vars = ApplicationState::getVars();
        $name = $vars['datastructure'];
        if ($name && isset($this->formatters[$name])) {
            return $this->formatters[$name];
        };

        // Return the default one
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var DefaultDataStructureFormatter
         */
        $formatter = $instanceManager->getInstance(DefaultDataStructureFormatter::class);
        return $formatter;
    }
}
