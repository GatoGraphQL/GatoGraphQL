<?php

declare(strict_types=1);

namespace PoP\RESTAPI\DataStructureFormatters;

use PoP\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use PoP\ComponentModel\Facades\Engine\EngineFacade;

class RESTDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    public const NAME = 'rest';

    public static function getName(): string
    {
        return self::NAME;
    }

    protected function getFields()
    {
        // Get the fields from the entry module's module atts
        $engine = EngineFacade::getInstance();
        $entryModule = $engine->getEntryModule();
        if ($moduleAtts = $entryModule[2] ?? null) {
            if ($fields = $moduleAtts['fields'] ?? null) {
                return $fields;
            }
        }

        return parent::getFields();
    }
}
