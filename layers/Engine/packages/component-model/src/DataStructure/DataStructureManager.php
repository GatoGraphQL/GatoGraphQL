<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;

class DataStructureManager implements DataStructureManagerInterface
{
    protected bool $initialized = false;
    /**
     * @var array<string, DataStructureFormatterInterface>
     */
    public array $formatters = [];
    /**
     * @var DataStructureFormatterInterface[]
     */
    public array $dataStructureFormatters = [];

    protected function maybeInitialize(): void
    {
        if (!$this->initialized) {
            $this->initialized = true;
            foreach ($this->dataStructureFormatters as $formatter) {
                $this->formatters[$formatter::getName()] = $formatter;
            }
        }
    }

    public function addDataStructureFormatter(DataStructureFormatterInterface $formatter): void
    {
        $this->dataStructureFormatters[] = $formatter;
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
