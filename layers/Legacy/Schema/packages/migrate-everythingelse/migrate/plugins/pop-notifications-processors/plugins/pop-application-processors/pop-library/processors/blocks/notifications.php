<?php
use PoP\Application\QueryInputOutputHandlers\ParamConstants;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class AAL_PoPProcessors_Module_Processor_NotificationBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS = 'block-notifications-scroll-details';
    public final const MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST = 'block-notifications-scroll-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS],
            [self::class, self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
            self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST => POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubmodules($componentVariation);

        $inner_modules = array(
            self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS => [AAL_PoPProcessors_Module_Processor_NotificationDataloads::class, AAL_PoPProcessors_Module_Processor_NotificationDataloads::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_DETAILS],
            self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST => [AAL_PoPProcessors_Module_Processor_NotificationDataloads::class, AAL_PoPProcessors_Module_Processor_NotificationDataloads::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST],
        );

        if ($inner = $inner_modules[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDescription(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST:
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

        return parent::getDescription($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST:
                // Fetch latest notifications every 30 seconds
                $this->addJsmethod($ret, 'timeoutLoadLatestBlock');

                // User logs in/out, scroll the block to the top
                $this->addJsmethod($ret, 'scrollTopOnUserLoggedInOut');
                break;
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST:
                // Needed to set the notifications count on the top bar, bell button
                // if ($target = $this->getProp($componentVariation, $props, 'datasetcount-target')) {
                if ($this->getProp($componentVariation, $props, 'set-datasetcount')) {
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

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST:
                return [AAL_PoPProcessors_Module_Processor_ControlGroups::class, AAL_PoPProcessors_Module_Processor_ControlGroups::MODULE_AAL_CONTROLGROUP_NOTIFICATIONLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_DETAILS:
            case self::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($componentVariation, $props, 'resourceloader', 'block-notifications');
                $this->appendProp($componentVariation, $props, 'class', 'block-notifications');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



