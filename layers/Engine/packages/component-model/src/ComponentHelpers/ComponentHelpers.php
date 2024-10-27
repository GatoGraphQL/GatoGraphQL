<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentHelpers;

use PoP\ComponentModel\Component\Component;
use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Services\BasicServiceTrait;

class ComponentHelpers implements ComponentHelpersInterface
{
    use BasicServiceTrait;

    public final const SEPARATOR_PROCESSORCOMPONENTFULLNAME = "::";

    private ?DefinitionManagerInterface $definitionManager = null;

    final protected function getDefinitionManager(): DefinitionManagerInterface
    {
        if ($this->definitionManager === null) {
            /** @var DefinitionManagerInterface */
            $definitionManager = $this->instanceManager->getInstance(DefinitionManagerInterface::class);
            $this->definitionManager = $definitionManager;
        }
        return $this->definitionManager;
    }

    public function getComponentFullName(Component $component): string
    {
        $componentFullName = $component->processorClass . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . $component->name;
        if ($component->atts !== []) {
            $componentFullName .= self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . serialize($component->atts);
        }

        return $componentFullName;
    }

    public function getComponentFromFullName(string $componentFullName): ?Component
    {
        $parts = explode(self::SEPARATOR_PROCESSORCOMPONENTFULLNAME, $componentFullName);

        // There must be at least 2 parts: class and name
        if (count($parts) < 2) {
            return null;
        }

        $processorClass = (string)$parts[0];
        $name = (string)$parts[1];
        $atts = [];

        // Does it have componentAtts? If so, unserialize them.
        if (isset($parts[2])) {
            $atts = (array)unserialize(
                // Just in case componentAtts contains the same SEPARATOR_PROCESSORCOMPONENTFULLNAME string inside of it, simply recalculate the whole componentAtts from the $componentFullName string
                substr(
                    $componentFullName,
                    strlen(
                        $processorClass . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . $name . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME
                    )
                )
            );
        }

        return new Component(
            $processorClass,
            $name,
            $atts
        );
    }

    public function getComponentOutputName(Component $component): string
    {
        return $this->getDefinitionManager()->getDefinition($this->getComponentFullName($component), DefinitionGroups::COMPONENTS);
    }

    public function getComponentFromOutputName(string $componentOutputName): ?Component
    {
        return $this->getComponentFromFullName($this->getDefinitionManager()->getOriginalName($componentOutputName, DefinitionGroups::COMPONENTS));
    }
}
