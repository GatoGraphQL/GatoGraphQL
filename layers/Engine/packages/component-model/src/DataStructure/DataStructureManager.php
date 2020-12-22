<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class DataStructureManager implements DataStructureManagerInterface
{
    /**
     * @var array<string, DataStructureFormatterInterface>
     */
    public array $formatters = [];

    public function add(DataStructureFormatterInterface $formatter): void
    {
        $this->formatters[$formatter::getName()] = $formatter;
    }

    public function getDataStructureFormatter(string $name = null): DataStructureFormatterInterface
    {
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
