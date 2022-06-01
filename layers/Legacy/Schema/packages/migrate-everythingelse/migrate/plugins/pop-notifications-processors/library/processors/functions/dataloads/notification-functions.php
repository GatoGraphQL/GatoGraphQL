<?php
use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSitesWassup\NotificationMutations\MutationResolverBridges\MarkAllAsReadNotificationMutationResolverBridge;
use PoPSitesWassup\NotificationMutations\MutationResolverBridges\MarkAsReadNotificationMutationResolverBridge;
use PoPSitesWassup\NotificationMutations\MutationResolverBridges\MarkAsUnreadNotificationMutationResolverBridge;

class GD_AAL_Module_Processor_FunctionsDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD = 'dataload-markallnotificationsasread';
    public final const COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD = 'dataload-marknotificationasread';
    public final const COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD = 'dataload-marknotificationasunread';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD,
            self::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD,
            self::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD,
            self::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD,
            self::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function prepareDataPropertiesAfterMutationExecution(\PoP\ComponentModel\Component\Component $component, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($component, $props, $data_properties);

        switch ($component->name) {
            case self::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD:
                if ($hist_ids = App::getMutationResolutionStore()->getResult($this->getComponentMutationResolverBridge($component))) {
                    $data_properties[DataloadingConstants::QUERYARGS]['include'] = $hist_ids;
                } else {
                    $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $layouts = array(
            self::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD => [GD_AAL_Module_Processor_FunctionsContents::class, GD_AAL_Module_Processor_FunctionsContents::COMPONENT_CONTENT_MARKNOTIFICATIONASREAD],
            self::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD => [GD_AAL_Module_Processor_FunctionsContents::class, GD_AAL_Module_Processor_FunctionsContents::COMPONENT_CONTENT_MARKNOTIFICATIONASREAD],
            self::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD => [GD_AAL_Module_Processor_FunctionsContents::class, GD_AAL_Module_Processor_FunctionsContents::COMPONENT_CONTENT_MARKNOTIFICATIONASUNREAD],
        );
        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD:
                return $this->instanceManager->getInstance(NotificationObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD:
                $this->appendProp($component, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($component, $props);
    }

    // function getActionexecutionCheckpointConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     switch ($component->name) {

    //         case self::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD:
    //         case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD:
    //         case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD:

    //             // The actionexecuter is invoked directly through GET, no ?actionpath required
    //             return null;
    //     }

    //     parent::getActionexecutionCheckpointConfiguration($component, $props);
    // }

    public function shouldExecuteMutation(\PoP\ComponentModel\Component\Component $component, array &$props): bool
    {

        // The actionexecuter is invoked directly through GET, no ?actionpath required
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD:
            case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD:
            case self::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD:
                return true;
        }

        return parent::shouldExecuteMutation($component, $props);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        $executers = array(
            self::COMPONENT_DATALOAD_MARKALLNOTIFICATIONSASREAD => MarkAllAsReadNotificationMutationResolverBridge::class,
            self::COMPONENT_DATALOAD_MARKNOTIFICATIONASREAD => MarkAsReadNotificationMutationResolverBridge::class,
            self::COMPONENT_DATALOAD_MARKNOTIFICATIONASUNREAD => MarkAsUnreadNotificationMutationResolverBridge::class,
        );
        if ($executer = $executers[$component->name] ?? null) {
            return $executer;
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}



