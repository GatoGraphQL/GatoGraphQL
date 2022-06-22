<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\DataStructureFormatters;

use PoP\ComponentModel\Engine\EngineInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;

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

    /**
     * If provided, get the fields from the entry component atts.
     *
     * @return FieldInterface[]
     */
    protected function getFields(): array
    {
        $entryComponent = $this->getEngine()->getEntryComponent();
        if ($fields = $entryComponent->atts['fields'] ?? null) {
            return $fields;
        }

        return parent::getFields();
    }
}
