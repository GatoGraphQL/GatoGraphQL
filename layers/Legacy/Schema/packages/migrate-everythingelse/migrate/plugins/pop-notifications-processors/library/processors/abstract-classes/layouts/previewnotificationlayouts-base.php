<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_PreviewNotificationLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_AAL_Processors_TemplateResourceLoaderProcessor::class, PoP_AAL_Processors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWNOTIFICATION];
    }

    public function getQuicklinkgroupTopSubmodule(array $component)
    {
        return [GD_AAL_Module_Processor_QuicklinkGroups::class, GD_AAL_Module_Processor_QuicklinkGroups::MODULE_AAL_QUICKLINKGROUP_NOTIFICATION];
    }
    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        return [PoP_Module_Processor_MultipleComponentLayouts::class, PoP_Module_Processor_MultipleComponentLayouts::MODULE_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM];
    }
    public function getLinkSubmodule(array $component)
    {
        return [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK];
    }

    // function addUrl(array $component, array &$props) {

    //     return true;
    // }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($link = $this->getLinkSubmodule($component)) {
            $ret[] = $link;
        }
        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($component)) {
            $ret[] = $quicklinkgroup_top;
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($component)) {
            $ret[] = $quicklinkgroup_bottom;
        }
        if ($bottom_submodules = $this->getBottomSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $bottom_submodules
            );
        }
        if ($post_thumb = $this->getPostThumbSubmodule($component)) {
            $ret[] = $post_thumb;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $component): array
    {
        $ret = parent::getRelationalSubmodules($component);

        $components = array();

        // Show author avatar: only if no thumb module defined, and author avatar is defined
        if (!$this->getPostThumbSubmodule($component) && PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($user_avatar = $this->getUserAvatarModule($component)) {
                $components[] = $user_avatar;
            }
        }

        if ($components) {
            $ret[] = new RelationalModuleField(
                'userID',
                $components
            );
        }

        return $ret;
    }

    public function addUrlLink(array $component, array &$props)
    {
        return false;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        // From the combination of object_type and action, we obtain the layout to use for the notification
        // $ret[] = 'objectType';
        // $ret[] = 'action';

        // $ret[] = 'url';
        $ret[] = 'status';
        $ret[] = 'message';

        if ($this->addUrlLink($component, $props)) {
            $ret[] = 'url';
            $ret[] = 'target';
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $component)
    {
        return null;
    }
    public function getUserAvatarModule(array $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR];
        }

        return null;
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $component): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($component);

        return array_merge(
            $ret,
            $this->getConditionalBottomSubmodules($component)
        );
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalBottomSubmodules(array $component): array
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
        $ret[] = new ConditionalLeafModuleField(
            $field,
            [
                [PoP_Module_Processor_NotificationSubcomponentLayouts::class, PoP_Module_Processor_NotificationSubcomponentLayouts::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT],
            ]
        );

        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_PreviewNotificationLayoutsBase:getConditionalBottomSubmodules',
            $ret,
            $component
        );
    }

    public function getBottomSubmodules(array $component)
    {
        return array(
            [PoP_Module_Processor_NotificationActionIconLayouts::class, PoP_Module_Processor_NotificationActionIconLayouts::MODULE_LAYOUT_NOTIFICATIONICON],
            [PoP_Module_Processor_NotificationTimeLayouts::class, PoP_Module_Processor_NotificationTimeLayouts::MODULE_LAYOUT_NOTIFICATIONTIME],
        );
    }

    public function horizontalMediaLayout(array $component)
    {
        return true;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // if ($this->addUrl($component, $props)) {

        //     $ret['add-url'] = true;
        // }
        if ($this->addUrlLink($component, $props)) {
            $ret['add-url-link'] = true;
        }
        // Classes
        $ret[GD_JS_CLASSES] = array();
        if ($this->horizontalMediaLayout($component)) {
            $ret[GD_JS_CLASSES]['wrapper'] = 'media';// ' overflow-visible';
            $ret[GD_JS_CLASSES]['thumb-wrapper'] = 'media-left';
            $ret[GD_JS_CLASSES]['content-body'] = 'media-body clearfix';
        }

        if ($link = $this->getLinkSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['link'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($link);
        }
        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-top'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_top);
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-bottom'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_bottom);
        }
        if ($this->getBottomSubmodules($component)) {
            $ret[GD_JS_CLASSES]['bottom'] = 'clearfix';
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $this->getBottomSubmodules($component)
            );
            foreach ($this->getConditionalBottomSubmodules($component) as $conditionalLeafModuleField) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'] = array_merge(
                    $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'],
                    array_map(
                        [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                        $conditionalLeafModuleField->getConditionalNestedComponents()
                    )
                );
            }
        }

        if ($post_thumb = $this->getPostThumbSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['postthumb'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($post_thumb);
        } elseif (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($user_avatar = $this->getUserAvatarModule($component)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['user-avatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($user_avatar);
            }
        }

        return $ret;
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        if (in_array(
            [GD_AAL_Module_Processor_QuicklinkGroups::class, GD_AAL_Module_Processor_QuicklinkGroups::MODULE_AAL_QUICKLINKGROUP_NOTIFICATION],
            $this->getSubComponents($component)
        )) {
            //-----------------------------------
            // Whenever clicking on the link on the notification, also "click" on the `Mark as read` button
            //-----------------------------------
            if ($link = $this->getLinkSubmodule($component)) {
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
                    'data-target' => $component,
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
                    'data-target' => $component,
                )
            );
            $this->mergeJsmethodsProp([AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD], $props, array('switchTargetClass'));
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}
