<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\DataStructureFormatters;

use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use PoP\ComponentModel\Engine\EngineInterface;

class RESTDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    private ?EngineInterface $engine = null;

    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        return $this->engine ??= $this->instanceManager->getInstance(EngineInterface::class);
    }

    public function getName(): string
    {
        return 'rest';
    }

    protected function getFields()
    {
        // Get the fields from the entry component atts
        $entryComponent = $this->getEngine()->getEntryComponent();
        if ($fields = $entryComponent->atts['fields'] ?? null) {
            return $fields;
        }

        return parent::getFields();
    }
}
