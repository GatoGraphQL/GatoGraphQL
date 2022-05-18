<?php
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManager;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\DataStructureFormatters\DBItemListDataStructureFormatter;
use PoP\Engine\ComponentFilters\MainContentModule;
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
            Params::VERSION => ApplicationInfoFacade::getInstance()->getVersion(),
            Params::OUTPUT => Outputs::JSON,
            Params::COMPONENTFILTER => $mainContentModule->getName(),
            Params::DATA_OUTPUT_ITEMS => [
                DataOutputItems::DATABASES,
            ],
            \PoP\ConfigurationComponentModel\Constants\Params::TARGET => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
            Params::DATASTRUCTURE => $dbItemListDataStructureFormatter->getName(),
        ];
        if ($format) {
            $args[\PoP\ConfigurationComponentModel\Constants\Params::FORMAT] = $format;
        }
        return GeneralUtils::addQueryArgs($args, $url);
    }
}
