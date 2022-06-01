<?php
use PoP\Application\QueryInputOutputHandlers\ParamConstants;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class AAL_PoPProcessors_Module_Processor_NotificationBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS = 'block-notifications-scroll-details';
    public final const COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST = 'block-notifications-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
            self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubcomponents($component);

        $inner_components = array(
            self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS => [AAL_PoPProcessors_Module_Processor_NotificationDataloads::class, AAL_PoPProcessors_Module_Processor_NotificationDataloads::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST => [AAL_PoPProcessors_Module_Processor_NotificationDataloads::class, AAL_PoPProcessors_Module_Processor_NotificationDataloads::COMPONENT_DATALOAD_NOTIFICATIONS_SCROLL_LIST],
        );

        if ($inner = $inner_components[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDescription(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST:
                // Ask the user to log-in to see the personal notifications
                return sprintf(
                    '<div class="visible-notloggedin-anydomain alert alert-sm alert-warning">%s</div>',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('These are the general notifications. Please %s to see your personal notifications.', 'pop-notifications-processors'),
                        gdGetLoginHtml()
                    )
                );
            break;
        }

        return parent::getDescription($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST:
                // Fetch latest notifications every 30 seconds
                $this->addJsmethod($ret, 'timeoutLoadLatestBlock');

                // User logs in/out, scroll the block to the top
                $this->addJsmethod($ret, 'scrollTopOnUserLoggedInOut');
                break;
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST:
                // Needed to set the notifications count on the top bar, bell button
                // if ($target = $this->getProp($component, $props, 'datasetcount-target')) {
                if ($this->getProp($component, $props, 'set-datasetcount')) {
                    $ret['timeoutLoadLatestBlock']['datasetcount-target'] = '#'.AAL_PoPProcessors_NotificationUtils::getNotificationcountId();//$target;
                    $ret['timeoutLoadLatestBlock']['datasetcount-updatetitle'] = true;
                }

                // Only fetch time and again if the user is logged in
                $ret['timeoutLoadLatestBlock']['only-loggedin'] = true;

                // Params to delete for function deleteBlockFeedbackValueOnUserLoggedInOut
                // Array of arrays: many params to delete, multiple levels down for each
                $ret['deleteBlockFeedbackValueOnUserLoggedInOut']['user:loggedinout-deletefeedbackvalue'] = array(
                    array(
                        ParamConstants::PARAMS, 'hist_time'
                    )
                );
                break;
        }

        return $ret;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST:
                return [AAL_PoPProcessors_Module_Processor_ControlGroups::class, AAL_PoPProcessors_Module_Processor_ControlGroups::COMPONENT_AAL_CONTROLGROUP_NOTIFICATIONLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_NOTIFICATIONS_SCROLL_LIST:
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'block-notifications');
                $this->appendProp($component, $props, 'class', 'block-notifications');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



