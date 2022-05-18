<?php
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;

class AAL_PoPProcessors_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_LATESTNOTIFICATIONS = 'dataload-notifications-latestnotifications';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LATESTNOTIFICATIONS],
        );
    }

    protected function addHeaddatasetmoduleDataProperties(&$ret, array $componentVariation, array &$props)
    {
        parent::addHeaddatasetmoduleDataProperties($ret, $componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                // If the user is not logged in, then do not load the data
                if (!PoP_UserState_Utils::currentRouteRequiresUserState() || !\PoP\Root\App::getState('is-user-logged-in')) {
                    $ret[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getStatusSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                return null;
        }

        return parent::getStatusSubmodule($componentVariation);
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }
        
        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_LatestNotificationList::class);
        }
        
        return parent::getQueryInputOutputHandler($componentVariation);
    }
}



