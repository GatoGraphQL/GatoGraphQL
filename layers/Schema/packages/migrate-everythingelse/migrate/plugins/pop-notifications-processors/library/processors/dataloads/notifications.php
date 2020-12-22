<?php
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoPSchema\Notifications\TypeResolvers\NotificationTypeResolver;
use PoP\ComponentModel\State\ApplicationState;

class AAL_PoPProcessors_Module_Processor_Dataloads extends PoP_Module_Processor_DataloadsBase
{
    public const MODULE_DATALOAD_LATESTNOTIFICATIONS = 'dataload-notifications-latestnotifications';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_LATESTNOTIFICATIONS],
        );
    }

    protected function addHeaddatasetmoduleDataProperties(&$ret, array $module, array &$props)
    {
        parent::addHeaddatasetmoduleDataProperties($ret, $module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                // If the user is not logged in, then do not load the data
                $vars = ApplicationState::getVars();
                if (!PoP_UserState_Utils::currentRouteRequiresUserState() || !$vars['global-userstate']['is-user-logged-in']) {
                    $ret[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getStatusSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                return null;
        }

        return parent::getStatusSubmodule($module);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                return NotificationTypeResolver::class;
        }
        
        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_LATESTNOTIFICATIONS:
                return GD_DataLoad_QueryInputOutputHandler_LatestNotificationList::class;
        }
        
        return parent::getQueryInputOutputHandlerClass($module);
    }
}



