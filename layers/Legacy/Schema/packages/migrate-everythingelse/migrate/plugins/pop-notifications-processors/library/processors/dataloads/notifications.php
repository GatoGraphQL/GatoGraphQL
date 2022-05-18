<?php
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;

class AAL_PoPProcessors_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_LATESTNOTIFICATIONS = 'dataload-notifications-latestnotifications';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LATESTNOTIFICATIONS],
        );
    }

    protected function addHeaddatasetmoduleDataProperties(&$ret, array $component, array &$props)
    {
        parent::addHeaddatasetmoduleDataProperties($ret, $component, $props);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                // If the user is not logged in, then do not load the data
                if (!PoP_UserState_Utils::currentRouteRequiresUserState() || !\PoP\Root\App::getState('is-user-logged-in')) {
                    $ret[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getStatusSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                return null;
        }

        return parent::getStatusSubmodule($component);
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }
        
        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_LatestNotificationList::class);
        }
        
        return parent::getQueryInputOutputHandler($component);
    }
}



