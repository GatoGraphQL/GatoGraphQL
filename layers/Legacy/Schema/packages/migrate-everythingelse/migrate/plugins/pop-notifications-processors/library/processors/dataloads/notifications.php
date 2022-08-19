<?php
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;

class AAL_PoPProcessors_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_LATESTNOTIFICATIONS = 'dataload-notifications-latestnotifications';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_LATESTNOTIFICATIONS,
        );
    }

    protected function addHeaddatasetcomponentDataProperties(&$ret, \PoP\ComponentModel\Component\Component $component, array &$props)
    {
        parent::addHeaddatasetcomponentDataProperties($ret, $component, $props);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LATESTNOTIFICATIONS:
                // If the user is not logged in, then do not load the data
                if (!PoP_UserState_Utils::currentRouteRequiresUserState() || !\PoP\Root\App::getState('is-user-logged-in')) {
                    $ret[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getStatusSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LATESTNOTIFICATIONS:
                return null;
        }

        return parent::getStatusSubcomponent($component);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LATESTNOTIFICATIONS:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }
        
        return parent::getRelationalTypeResolver($component);
    }

    public function getQueryInputOutputHandler(\PoP\ComponentModel\Component\Component $component): ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_LATESTNOTIFICATIONS:
                return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_LatestNotificationList::class);
        }
        
        return parent::getQueryInputOutputHandler($component);
    }
}



