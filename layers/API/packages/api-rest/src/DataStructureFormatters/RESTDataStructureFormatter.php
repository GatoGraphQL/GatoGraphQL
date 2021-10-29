<?php

declare(strict_types=1);

namespace PoP\RESTAPI\DataStructureFormatters;

use PoP\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use PoP\ComponentModel\Engine\EngineInterface;
use Symfony\Contracts\Service\Attribute\Required;

class RESTDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    protected ?EngineInterface $engine = null;

    #[Required]
    final public function autowireRESTDataStructureFormatter(
        EngineInterface $engine,
    ): void {
        $this->engine = $engine;
    }

    public function getName(): string
    {
        return 'rest';
    }

    protected function getFields()
    {
        // Get the fields from the entry module's module atts
        $entryModule = $this->getEngine()->getEntryModule();
        if ($moduleAtts = $entryModule[2] ?? null) {
            if ($fields = $moduleAtts['fields'] ?? null) {
                return $fields;
            }
        }

        return parent::getFields();
    }
}
