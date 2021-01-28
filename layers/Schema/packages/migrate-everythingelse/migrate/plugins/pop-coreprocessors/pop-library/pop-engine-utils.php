<?php
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManager;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\DataStructureFormatters\DBItemListDataStructureFormatter;
use PoP\ComponentModel\State\ApplicationState;

class PoPCore_ModuleManager_Utils
{
    public static function addJsonoutputResultsParams($url, $format = null)
    {

        // Retrieve the dataload-source that will produce the data. Add the params to the URL
        $vars = ApplicationState::getVars();
        $args = [
            \PoP\ComponentModel\Constants\Params::VERSION => $vars['version'],
            \PoP\ComponentModel\Constants\Params::OUTPUT => \PoP\ComponentModel\Constants\Outputs::JSON,
            ModuleFilterManager::URLPARAM_MODULEFILTER => \PoP\Engine\ModuleFilters\MainContentModule::NAME,
            \PoP\ComponentModel\Constants\Params::DATA_OUTPUT_ITEMS => [
                \PoP\ComponentModel\Constants\DataOutputItems::DATABASES,
            ],
            \PoP\ComponentModel\Constants\Params::TARGET => \PoP\ComponentModel\Constants\Targets::MAIN,
            \PoP\ComponentModel\Constants\Params::DATASTRUCTURE => DBItemListDataStructureFormatter::getName(),
        ];
        if ($format) {
            $args[\PoP\ComponentModel\Constants\Params::FORMAT] = $format;
        }
        return GeneralUtils::addQueryArgs($args, $url);
    }
}
