<?php
use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSitesWassup\NotificationMutations\MutationResolverBridges\MarkAllAsReadNotificationMutationResolverBridge;
use PoPSitesWassup\NotificationMutations\MutationResolverBridges\MarkAsReadNotificationMutationResolverBridge;
use PoPSitesWassup\NotificationMutations\MutationResolverBridges\MarkAsUnreadNotificationMutationResolverBridge;

class GD_AAL_Module_Processor_FunctionsDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD = 'dataload-markallnotificationsasread';
    public final const MODULE_DATALOAD_MARKNOTIFICATIONASREAD = 'dataload-marknotificationasread';
    public final const MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD = 'dataload-marknotificationasunread';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD],
            [self::class, self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD],
            [self::class, self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD,
            self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD,
            self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function prepareDataPropertiesAfterMutationExecution(array $component, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($component, $props, $data_properties);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:
                if ($hist_ids = App::getMutationResolutionStore()->getResult($this->getComponentMutationResolverBridge($component))) {
                    $data_properties[DataloadingConstants::QUERYARGS]['include'] = $hist_ids;
                } else {
                    $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $layouts = array(
            self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD => [GD_AAL_Module_Processor_FunctionsContents::class, GD_AAL_Module_Processor_FunctionsContents::MODULE_CONTENT_MARKNOTIFICATIONASREAD],
            self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD => [GD_AAL_Module_Processor_FunctionsContents::class, GD_AAL_Module_Processor_FunctionsContents::MODULE_CONTENT_MARKNOTIFICATIONASREAD],
            self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD => [GD_AAL_Module_Processor_FunctionsContents::class, GD_AAL_Module_Processor_FunctionsContents::MODULE_CONTENT_MARKNOTIFICATIONASUNREAD],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:
                $this->appendProp($component, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($component, $props);
    }

    // function getActionexecutionCheckpointConfiguration(array $component, array &$props) {

    //     switch ($component[1]) {

    //         case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
    //         case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
    //         case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:

    //             // The actionexecuter is invoked directly through GET, no ?actionpath required
    //             return null;
    //     }

    //     parent::getActionexecutionCheckpointConfiguration($component, $props);
    // }

    public function shouldExecuteMutation(array $component, array &$props): bool
    {

        // The actionexecuter is invoked directly through GET, no ?actionpath required
        switch ($component[1]) {
            case self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD:
                return true;
        }

        return parent::shouldExecuteMutation($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $executers = array(
            self::MODULE_DATALOAD_MARKALLNOTIFICATIONSASREAD => MarkAllAsReadNotificationMutationResolverBridge::class,
            self::MODULE_DATALOAD_MARKNOTIFICATIONASREAD => MarkAsReadNotificationMutationResolverBridge::class,
            self::MODULE_DATALOAD_MARKNOTIFICATIONASUNREAD => MarkAsUnreadNotificationMutationResolverBridge::class,
        );
        if ($executer = $executers[$component[1]] ?? null) {
            return $executer;
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}



