<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Modules;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Services\BasicServiceTrait;

class ComponentHelpers implements ComponentHelpersInterface
{
    public final const SEPARATOR_PROCESSORCOMPONENTFULLNAME = "::";

    use BasicServiceTrait;

    private ?DefinitionManagerInterface $definitionManager = null;

    final public function setDefinitionManager(DefinitionManagerInterface $definitionManager): void
    {
        $this->definitionManager = $definitionManager;
    }
    final protected function getDefinitionManager(): DefinitionManagerInterface
    {
        return $this->definitionManager ??= $this->instanceManager->getInstance(DefinitionManagerInterface::class);
    }

    public function getComponentFullName(\PoP\ComponentModel\Component\Component $component): string
    {
        $componentFullName = $component[0] . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . $component[1];
        if ($component->atts !== []) {
            $componentFullName .= self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . serialize($component[2]);
        }

        return $componentFullName;
    }

    public function getComponentFromFullName(string $componentFullName): ?\PoP\ComponentModel\Component\Component
    {
        $parts = explode(self::SEPARATOR_PROCESSORCOMPONENTFULLNAME, $componentFullName);

        // There must be at least 2 parts: class and name
        if (count($parts) < 2) {
            return null;
        }

        // Does it have componentAtts? If so, unserialize them.
        return (count($parts) >= 3) ?
            [
                $parts[0], // processorClass
                $parts[1], // name
                unserialize(
                    // Just in case componentAtts contains the same SEPARATOR_PROCESSORCOMPONENTFULLNAME string inside of it, simply recalculate the whole componentAtts from the $componentFullName string
                    substr(
                        $componentFullName,
                        strlen(
                            $parts[0] . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . $parts[1] . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME
                        )
                    )
                )
            ] :
            // Otherwise, the parts are already the item
            $parts;
    }

    public function getComponentOutputName(\PoP\ComponentModel\Component\Component $component): string
    {
        return $this->getDefinitionManager()->getDefinition($this->getComponentFullName($component), DefinitionGroups::COMPONENTS);
    }

    public function getComponentFromOutputName(string $componentOutputName): ?\PoP\ComponentModel\Component\Component
    {
        return $this->getComponentFromFullName($this->getDefinitionManager()->getOriginalName($componentOutputName, DefinitionGroups::COMPONENTS));
    }
}
