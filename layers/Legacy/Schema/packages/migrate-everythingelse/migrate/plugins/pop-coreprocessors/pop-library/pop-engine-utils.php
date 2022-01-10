<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManager;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\DataStructureFormatters\DBItemListDataStructureFormatter;
use PoP\Engine\ModuleFilters\MainContentModule;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class PoPCore_ModuleManager_Utils
{
    public static function addJsonoutputResultsParams($url, $format = null)
    {

        // Retrieve the dataload-source that will produce the data. Add the params to the URL
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DBItemListDataStructureFormatter */
        $dbItemListDataStructureFormatter = $instanceManager->getInstance(DBItemListDataStructureFormatter::class);
        /** @var MainContentModule */
        $mainContentModule = $instanceManager->getInstance(MainContentModule::class);
        $args = [
            \PoP\ComponentModel\Constants\Params::VERSION => ApplicationInfoFacade::getInstance()->getVersion(),
            \PoP\ComponentModel\Constants\Params::OUTPUT => \PoP\ComponentModel\Constants\Outputs::JSON,
            ModuleFilterManager::URLPARAM_MODULEFILTER => $mainContentModule->getName(),
            \PoP\ComponentModel\Constants\Params::DATA_OUTPUT_ITEMS => [
                \PoP\ComponentModel\Constants\DataOutputItems::DATABASES,
            ],
            \PoP\ConfigurationComponentModel\Constants\Params::TARGET => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
            \PoP\ComponentModel\Constants\Params::DATASTRUCTURE => $dbItemListDataStructureFormatter->getName(),
        ];
        if ($format) {
            $args[\PoP\ConfigurationComponentModel\Constants\Params::FORMAT] = $format;
        }
        return GeneralUtils::addQueryArgs($args, $url);
    }
}
