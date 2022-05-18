<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_PreviewNotificationLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_AAL_Processors_TemplateResourceLoaderProcessor::class, PoP_AAL_Processors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWNOTIFICATION];
    }

    public function getQuicklinkgroupTopSubmodule(array $componentVariation)
    {
        return [GD_AAL_Module_Processor_QuicklinkGroups::class, GD_AAL_Module_Processor_QuicklinkGroups::MODULE_AAL_QUICKLINKGROUP_NOTIFICATION];
    }
    public function getQuicklinkgroupBottomSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_MultipleComponentLayouts::class, PoP_Module_Processor_MultipleComponentLayouts::MODULE_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM];
    }
    public function getLinkSubmodule(array $componentVariation)
    {
        return [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK];
    }

    // function addUrl(array $componentVariation, array &$props) {

    //     return true;
    // }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($link = $this->getLinkSubmodule($componentVariation)) {
            $ret[] = $link;
        }
        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($componentVariation)) {
            $ret[] = $quicklinkgroup_top;
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($componentVariation)) {
            $ret[] = $quicklinkgroup_bottom;
        }
        if ($bottom_submodules = $this->getBottomSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $bottom_submodules
            );
        }
        if ($post_thumb = $this->getPostThumbSubmodule($componentVariation)) {
            $ret[] = $post_thumb;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $componentVariation): array
    {
        $ret = parent::getRelationalSubmodules($componentVariation);

        $componentVariations = array();

        // Show author avatar: only if no thumb module defined, and author avatar is defined
        if (!$this->getPostThumbSubmodule($componentVariation) && PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($user_avatar = $this->getUserAvatarModule($componentVariation)) {
                $componentVariations[] = $user_avatar;
            }
        }

        if ($componentVariations) {
            $ret[] = new RelationalModuleField(
                'userID',
                $componentVariations
            );
        }

        return $ret;
    }

    public function addUrlLink(array $componentVariation, array &$props)
    {
        return false;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);

        // From the combination of object_type and action, we obtain the layout to use for the notification
        // $ret[] = 'objectType';
        // $ret[] = 'action';

        // $ret[] = 'url';
        $ret[] = 'status';
        $ret[] = 'message';

        if ($this->addUrlLink($componentVariation, $props)) {
            $ret[] = 'url';
            $ret[] = 'target';
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $componentVariation)
    {
        return null;
    }
    public function getUserAvatarModule(array $componentVariation)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR];
        }

        return null;
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalOnDataFieldSubmodules(array $componentVariation): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($componentVariation);

        return array_merge(
            $ret,
            $this->getConditionalBottomSubmodules($componentVariation)
        );
    }

    /**
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalBottomSubmodules(array $componentVariation): array
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
            $componentVariation
        );
    }

    public function getBottomSubmodules(array $componentVariation)
    {
        return array(
            [PoP_Module_Processor_NotificationActionIconLayouts::class, PoP_Module_Processor_NotificationActionIconLayouts::MODULE_LAYOUT_NOTIFICATIONICON],
            [PoP_Module_Processor_NotificationTimeLayouts::class, PoP_Module_Processor_NotificationTimeLayouts::MODULE_LAYOUT_NOTIFICATIONTIME],
        );
    }

    public function horizontalMediaLayout(array $componentVariation)
    {
        return true;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // if ($this->addUrl($componentVariation, $props)) {

        //     $ret['add-url'] = true;
        // }
        if ($this->addUrlLink($componentVariation, $props)) {
            $ret['add-url-link'] = true;
        }
        // Classes
        $ret[GD_JS_CLASSES] = array();
        if ($this->horizontalMediaLayout($componentVariation)) {
            $ret[GD_JS_CLASSES]['wrapper'] = 'media';// ' overflow-visible';
            $ret[GD_JS_CLASSES]['thumb-wrapper'] = 'media-left';
            $ret[GD_JS_CLASSES]['content-body'] = 'media-body clearfix';
        }

        if ($link = $this->getLinkSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['link'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($link);
        }
        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-top'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_top);
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup-bottom'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup_bottom);
        }
        if ($this->getBottomSubmodules($componentVariation)) {
            $ret[GD_JS_CLASSES]['bottom'] = 'clearfix';
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $this->getBottomSubmodules($componentVariation)
            );
            foreach ($this->getConditionalBottomSubmodules($componentVariation) as $conditionalLeafModuleField) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'] = array_merge(
                    $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'],
                    array_map(
                        [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                        $conditionalLeafModuleField->getConditionalNestedComponentVariations()
                    )
                );
            }
        }

        if ($post_thumb = $this->getPostThumbSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['postthumb'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($post_thumb);
        } elseif (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($user_avatar = $this->getUserAvatarModule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['user-avatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($user_avatar);
            }
        }

        return $ret;
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        if (in_array(
            [GD_AAL_Module_Processor_QuicklinkGroups::class, GD_AAL_Module_Processor_QuicklinkGroups::MODULE_AAL_QUICKLINKGROUP_NOTIFICATION],
            $this->getSubComponentVariations($componentVariation)
        )) {
            //-----------------------------------
            // Whenever clicking on the link on the notification, also "click" on the `Mark as read` button
            //-----------------------------------
            if ($link = $this->getLinkSubmodule($componentVariation)) {
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
                    'data-target' => $componentVariation,
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
                    'data-target' => $componentVariation,
                )
            );
            $this->mergeJsmethodsProp([AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD], $props, array('switchTargetClass'));
        }

        parent::initWebPlatformModelProps($componentVariation, $props);
    }
}
