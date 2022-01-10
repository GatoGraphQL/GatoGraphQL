<?php
use PoP\ComponentModel\App;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSitesWassup\NotificationMutations\MutationResolverBridges\MarkAllAsReadNotificationMutationResolverBridge;
use PoPSitesWassup\NotificationMutations\MutationResolverBridges\MarkAsReadNotificationMutationResolverBridge;
use PoPSitesWassup\NotificationMutations\MutationResolverBridges\MarkAsUnreadNotificationMutationResolverBridge;

class GD_AAL_Module_Processor_FunctionsDataloads extends PoP_Module_Processor_DataloadsBase
{
    public const MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD = 'dataload-markallnotificationsasread';
    public const MODULE_DATALOAD_MARKNOTIFICATIONASREAD = 'dataload-marknotificationasread';
    public const MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD = 'dataload-marknotificationasunread';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD],
            [self::class, self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD],
            [self::class, self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD,
            self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD,
            self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function prepareDataPropertiesAfterMutationExecution(array $module, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($module, $props, $data_properties);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:
                if ($hist_ids = App::getMutationResolutionStore()->getResult($this->getComponentMutationResolverBridge($module))) {
                    $data_properties[DataloadingConstants::QUERYARGS]['include'] = $hist_ids;
                } else {
                    $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $layouts = array(
            self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD => [GD_AAL_Module_Processor_FunctionsContents::class, GD_AAL_Module_Processor_FunctionsContents::MODULE_CONTENT_MARKNOTIFICATIONASREAD],
            self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD => [GD_AAL_Module_Processor_FunctionsContents::class, GD_AAL_Module_Processor_FunctionsContents::MODULE_CONTENT_MARKNOTIFICATIONASREAD],
            self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD => [GD_AAL_Module_Processor_FunctionsContents::class, GD_AAL_Module_Processor_FunctionsContents::MODULE_CONTENT_MARKNOTIFICATIONASUNREAD],
        );
        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:
                $this->appendProp($module, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($module, $props);
    }

    // function getActionexecutionCheckpointConfiguration(array $module, array &$props) {

    //     switch ($module[1]) {

    //         case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
    //         case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
    //         case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:

    //             // The actionexecuter is invoked directly through GET, no ?actionpath required
    //             return null;
    //     }

    //     parent::getActionexecutionCheckpointConfiguration($module, $props);
    // }

    public function shouldExecuteMutation(array $module, array &$props): bool
    {

        // The actionexecuter is invoked directly through GET, no ?actionpath required
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:
                return true;
        }

        return parent::shouldExecuteMutation($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $executers = array(
            self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD => MarkAllAsReadNotificationMutationResolverBridge::class,
            self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD => MarkAsReadNotificationMutationResolverBridge::class,
            self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD => MarkAsUnreadNotificationMutationResolverBridge::class,
        );
        if ($executer = $executers[$module[1]] ?? null) {
            return $executer;
        }

        return parent::getComponentMutationResolverBridge($module);
    }
}



