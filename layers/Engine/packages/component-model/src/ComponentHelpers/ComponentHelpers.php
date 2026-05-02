<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentHelpers;

use PoP\ComponentModel\Component\Component;
use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Services\AbstractBasicService;
use WeakMap;

class ComponentHelpers extends AbstractBasicService implements ComponentHelpersInterface
{
    public final const SEPARATOR_PROCESSORCOMPONENTFULLNAME = "::";

    private ?DefinitionManagerInterface $definitionManager = null;

    /**
     * Memoize the (potentially expensive — `serialize($component->atts)`)
     * full-name string per Component instance. `Component` is `final
     * readonly`, so the same instance always yields the same name; and
     * the same instance is queried many times per request across schema
     * walking, prop propagation and dataloading paths.
     *
     * Use `WeakMap` (not an `spl_object_id`-keyed array) so cache entries
     * are released when the Component is garbage-collected. An int-keyed
     * cache would hit stale entries when PHP reuses object IDs across
     * tests / long-running processes.
     *
     * @var WeakMap<Component,string>|null
     */
    private ?WeakMap $componentFullNameCache = null;

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
        $cache = $this->componentFullNameCache ??= new WeakMap();
        if (isset($cache[$component])) {
            return $cache[$component];
        }
        $componentFullName = $component->processorClass . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . $component->name;
        if ($component->atts !== []) {
            $componentFullName .= self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . serialize($component->atts);
        }

        return $cache[$component] = $componentFullName;
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
