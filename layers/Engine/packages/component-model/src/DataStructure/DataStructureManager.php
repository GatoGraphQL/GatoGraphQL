<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\DataStructure\DataStructureFormatterInterface;
use PoP\ComponentModel\State\ApplicationState;

class DataStructureManager implements DataStructureManagerInterface
{
    /**
     * @var array<string, DataStructureFormatterInterface>
     */
    public array $formatters = [];
    protected DataStructureFormatterInterface $defaultFormatter;

    function __construct(DataStructureFormatterInterface $defaultFormatter)
    {
        $this->defaultFormatter = $defaultFormatter;
    }

    public function addDataStructureFormatter(DataStructureFormatterInterface $formatter): void
    {
        $this->formatters[$formatter::getName()] = $formatter;
    }

    public function setDefaultDataStructureFormatter(DataStructureFormatterInterface $defaultFormatter): void
    {
        $this->defaultFormatter = $defaultFormatter;
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

        return $this->defaultFormatter;
    }
}
