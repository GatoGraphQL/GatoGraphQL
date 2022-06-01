<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentHelpers;

use PoP\ComponentModel\Component\Component;
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

    public function getComponentOutputName(Component $component): string
    {
        return $this->getDefinitionManager()->getDefinition($this->getComponentFullName($component), DefinitionGroups::COMPONENTS);
    }

    public function getComponentFromOutputName(string $componentOutputName): ?Component
    {
        return $this->getComponentFromFullName($this->getDefinitionManager()->getOriginalName($componentOutputName, DefinitionGroups::COMPONENTS));
    }
}
