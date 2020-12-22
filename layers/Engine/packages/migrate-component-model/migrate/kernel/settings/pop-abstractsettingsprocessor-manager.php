<?php
namespace PoP\ComponentModel\Settings;

use PoP\ComponentModel\Misc\RequestUtils;

abstract class AbstractSettingsProcessorManager
{
    public $processors;
    public $default_processor;

    public function getProcessors()
    {

        // Needed for the Cache Generator
        return $this->processors;
    }

    /**
     * @return string[]
     */
    public function getRoutes(): array
    {

        // Filter out $page with no value, since the ID might've not been defined for that page
        return array_filter(array_keys($this->processors));
    }

    public function __construct()
    {
        $this->processors = array();
    }

    public function getProcessor($route)
    {
        if ($this->processors[$route] ?? null) {
            return $this->processors[$route];
        }

        if ($this->default_processor) {
            return $this->default_processor;
        }

        throw new \Exception(
            sprintf(
                'No Settings Processor for $route \'%s\' (%s)',
                $route,
                RequestUtils::getRequestedFullURL()
            )
        );
    }

    public function add($processor)
    {
        foreach ($processor->routesToProcess() as $route) {
            $this->processors[$route] = $processor;
        }
    }

    public function setDefault($processor)
    {
        $this->default_processor = $processor;
    }
}

