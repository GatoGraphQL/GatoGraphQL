<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Modules\ModuleUtils;

abstract class PoP_Module_Processor_PreviewNotificationLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_AAL_Processors_TemplateResourceLoaderProcessor::class, PoP_AAL_Processors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWNOTIFICATION];
    }

    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        return [GD_AAL_Module_Processor_QuicklinkGroups::class, GD_AAL_Module_Processor_QuicklinkGroups::MODULE_AAL_QUICKLINKGROUP_NOTIFICATION];
    }
    public function getQuicklinkgroupBottomSubmodule(array $module)
    {
        return [PoP_Module_Processor_MultipleComponentLayouts::class, PoP_Module_Processor_MultipleComponentLayouts::MODULE_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM];
    }
    public function getLinkSubmodule(array $module)
    {
        return [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK];
    }

    // function addUrl(array $module, array &$props) {

    //     return true;
    // }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($link = $this->getLinkSubmodule($module)) {
            $ret[] = $link;
        }
        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($module)) {
            $ret[] = $quicklinkgroup_top;
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($module)) {
            $ret[] = $quicklinkgroup_bottom;
        }
        if ($bottom_submodules = $this->getBottomSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $bottom_submodules
            );
        }
        if ($post_thumb = $this->getPostThumbSubmodule($module)) {
            $ret[] = $post_thumb;
        }

        return $ret;
    }

    public function getDomainSwitchingSubmodules(array $module): array
    {
        $ret = parent::getDomainSwitchingSubmodules($module);

        $modules = array();

        // Show author avatar: only if no thumb module defined, and author avatar is defined
        if (!$this->getPostThumbSubmodule($module) && PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($user_avatar = $this->getUserAvatarModule($module)) {
                $modules[] = $user_avatar;
            }
        }

        if ($modules) {
            $ret['userID'] = $modules;
        }

        return $ret;
    }

    public function addUrlLink(array $module, array &$props)
    {
        return false;
    }

    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        // From the combination of object_type and action, we obtain the layout to use for the notification
        // $ret[] = 'objectType';
        // $ret[] = 'action';

        // $ret[] = 'url';
        $ret[] = 'status';
        $ret[] = 'message';

        if ($this->addUrlLink($module, $props)) {
            $ret[] = 'url';
            $ret[] = 'target';
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $module)
    {
        return null;
    }
    public function getUserAvatarModule(array $module)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR];
        }

        return null;
    }

    public function getConditionalOnDataFieldSubmodules(array $module): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($module);

        return array_merge_recursive(
            $ret,
            $this->getConditionalBottomSubmodules($module)
        );
    }

    public function getConditionalBottomSubmodules(array $module)
    {
        $ret = [];
        // Only fetch data if doing loadingLatest and is a comment notification
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $field = $fieldQueryInterpreter->getField(
            'and',
            [
                'values' => [
                    $fieldQueryInterpreter->createFieldArgValueAsFieldFromFieldName('isCommentNotification'),
                    $fieldQueryInterpreter->getField('var', ['name' => 'loading-latest']),
                ],
            ],
            'is-comment-notification-and-loading-latest'
        );
        $ret[$field] = [
            [PoP_Module_Processor_NotificationSubcomponentLayouts::class, PoP_Module_Processor_NotificationSubcomponentLayouts::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT],
        ];

        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_Module_Processor_PreviewNotificationLayoutsBase:getConditionalOnDataFieldSubmodules',
            $ret,
            $module
        );
    }

    public function getBottomSubmodules(array $module)
    {
        return array(
            [PoP_Module_Processor_NotificationActionIconLayouts::class, PoP_Module_Processor_NotificationActionIconLayouts::MODULE_LAYOUT_NOTIFICATIONICON],
            [PoP_Module_Processor_NotificationTimeLayouts::class, PoP_Module_Processor_NotificationTimeLayouts::MODULE_LAYOUT_NOTIFICATIONTIME],
        );
    }

    public function horizontalMediaLayout(array $module)
    {
        return true;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // if ($this->addUrl($module, $props)) {

        //     $ret['add-url'] = true;
        // }
        if ($this->addUrlLink($module, $props)) {
            $ret['add-url-link'] = true;
        }
        // Classes
        $ret[GD_JS_CLASSES] = array();
        if ($this->horizontalMediaLayout($module)) {
            $ret[GD_JS_CLASSES]['wrapper'] = 'media';// ' overflow-visible';
            $ret[GD_JS_CLASSES]['thumb-wrapper'] = 'media-left';
            $ret[GD_JS_CLASSES]['content-body'] = 'media-body clearfix';
        }

        if ($link = $this->getLinkSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['link'] = ModuleUtils::getModuleOutputName($link);
        }
        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-top'] = ModuleUtils::getModuleOutputName($quicklinkgroup_top);
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-bottom'] = ModuleUtils::getModuleOutputName($quicklinkgroup_bottom);
        }
        if ($this->getBottomSubmodules($module)) {
            $ret[GD_JS_CLASSES]['bottom'] = 'clearfix';
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'] = array_map(
                [ModuleUtils::class, 'getModuleOutputName'],
                $this->getBottomSubmodules($module)
            );
            foreach ($this->getConditionalBottomSubmodules($module) as $conditionDataField => $conditionSubmodules) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'] = array_merge(
                    $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'],
                    array_map(
                        [ModuleUtils::class, 'getModuleOutputName'],
                        $conditionSubmodules
                    )
                );
            }
        }

        if ($post_thumb = $this->getPostThumbSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['postthumb'] = ModuleUtils::getModuleOutputName($post_thumb);
        } elseif (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($user_avatar = $this->getUserAvatarModule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['user-avatar'] = ModuleUtils::getModuleOutputName($user_avatar);
            }
        }

        return $ret;
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        if (in_array(
            [GD_AAL_Module_Processor_QuicklinkGroups::class, GD_AAL_Module_Processor_QuicklinkGroups::MODULE_AAL_QUICKLINKGROUP_NOTIFICATION],
            $this->getSubmodules($module)
        )) {
            //-----------------------------------
            // Whenever clicking on the link on the notification, also "click" on the `Mark as read` button
            //-----------------------------------
            if ($link = $this->getLinkSubmodule($module)) {
                $this->mergeProp(
                    [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD],
                    $props,
                    'previousmodules-ids',
                    array(
                        'data-triggertarget' => $link
                    )
                );
                $this->mergeJsmethodsProp([AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD], $props, array('onActionThenClick'));
            }

            //-----------------------------------
            // Add class "read" to the notification layout to make it appear as read or not when clicking on the corresponding button
            //-----------------------------------
            $this->mergeProp(
                [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD],
                $props,
                'params',
                array(
                    'data-mode' => 'add',
                    'data-class' => AAL_POP_STATUS_READ,
                )
            );
            $this->mergeProp(
                [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD],
                $props,
                'previousmodules-ids',
                array(
                    'data-target' => $module,
                )
            );
            $this->mergeJsmethodsProp([AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD], $props, array('switchTargetClass'));

            //-----------------------------------
            // Remove class "read" to the notification layout to make it appear as read or not when clicking on the corresponding button
            //-----------------------------------
            $this->mergeProp(
                [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD],
                $props,
                'params',
                array(
                    'data-mode' => 'remove',
                    'data-class' => AAL_POP_STATUS_READ,
                )
            );
            $this->mergeProp(
                [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD],
                $props,
                'previousmodules-ids',
                array(
                    'data-target' => $module,
                )
            );
            $this->mergeJsmethodsProp([AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD], $props, array('switchTargetClass'));
        }

        parent::initWebPlatformModelProps($module, $props);
    }
}
