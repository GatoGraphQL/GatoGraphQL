<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

abstract class PoP_Module_Processor_PreviewNotificationLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_AAL_Processors_TemplateResourceLoaderProcessor::class, PoP_AAL_Processors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWNOTIFICATION];
    }

    public function getQuicklinkgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [GD_AAL_Module_Processor_QuicklinkGroups::class, GD_AAL_Module_Processor_QuicklinkGroups::COMPONENT_AAL_QUICKLINKGROUP_NOTIFICATION];
    }
    public function getQuicklinkgroupBottomSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MultipleComponentLayouts::class, PoP_Module_Processor_MultipleComponentLayouts::COMPONENT_AAL_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM];
    }
    public function getLinkSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATIONPREVIEWLINK];
    }

    // function addUrl(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     return true;
    // }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($link = $this->getLinkSubcomponent($component)) {
            $ret[] = $link;
        }
        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubcomponent($component)) {
            $ret[] = $quicklinkgroup_top;
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubcomponent($component)) {
            $ret[] = $quicklinkgroup_bottom;
        }
        if ($bottom_subcomponents = $this->getBottomSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $bottom_subcomponents
            );
        }
        if ($post_thumb = $this->getPostThumbSubcomponent($component)) {
            $ret[] = $post_thumb;
        }

        return $ret;
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getRelationalComponentFieldNodes($component);

        $components = array();

        // Show author avatar: only if no thumb module defined, and author avatar is defined
        if (!$this->getPostThumbSubcomponent($component) && PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($user_avatar = $this->getUserAvatarComponent($component)) {
                $components[] = $user_avatar;
            }
        }

        if ($components) {
            $ret[] = new RelationalComponentFieldNode(
                new LeafField(
                    'userID',
                    null,
                    [],
                    [],
                    LocationHelper::getNonSpecificLocation()
                ),
                $components
            );
        }

        return $ret;
    }

    public function addUrlLink(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFieldNodes($component, $props);

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

    public function getPostThumbSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getUserAvatarComponent(\PoP\ComponentModel\Component\Component $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR];
        }

        return null;
    }

    /**
     * @return ConditionalLeafComponentFieldNode[]
     */
    public function getConditionalLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getConditionalLeafComponentFieldNodes($component);

        return array_merge(
            $ret,
            $this->getConditionalBottomSubcomponents($component)
        );
    }

    /**
     * @return ConditionalLeafComponentFieldNode[]
     */
    public function getConditionalBottomSubcomponents(\PoP\ComponentModel\Component\Component $component): array
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
        $ret[] = new ConditionalLeafComponentFieldNode(
            new LeafField(
                $field,
                null,
                [],
                [],
                LocationHelper::getNonSpecificLocation()
            ),
            [
                [PoP_Module_Processor_NotificationSubcomponentLayouts::class, PoP_Module_Processor_NotificationSubcomponentLayouts::COMPONENT_SUBCOMPONENT_NOTIFICATIONCOMMENT],
            ]
        );

        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_PreviewNotificationLayoutsBase:getConditionalBottomSubcomponents',
            $ret,
            $component
        );
    }

    public function getBottomSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array(
            [PoP_Module_Processor_NotificationActionIconLayouts::class, PoP_Module_Processor_NotificationActionIconLayouts::COMPONENT_LAYOUT_NOTIFICATIONICON],
            [PoP_Module_Processor_NotificationTimeLayouts::class, PoP_Module_Processor_NotificationTimeLayouts::COMPONENT_LAYOUT_NOTIFICATIONTIME],
        );
    }

    public function horizontalMediaLayout(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

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

        if ($link = $this->getLinkSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['link'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($link);
        }
        if ($quicklinkgroup_top = $this->getQuicklinkgroupTopSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['quicklinkgroup-top'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($quicklinkgroup_top);
        }
        if ($quicklinkgroup_bottom = $this->getQuicklinkgroupBottomSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['quicklinkgroup-bottom'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($quicklinkgroup_bottom);
        }
        if ($this->getBottomSubcomponents($component)) {
            $ret[GD_JS_CLASSES]['bottom'] = 'clearfix';
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['bottom'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $this->getBottomSubcomponents($component)
            );
            foreach ($this->getConditionalBottomSubcomponents($component) as $conditionalLeafComponentFieldNode) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['bottom'] = array_merge(
                    $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['bottom'],
                    array_map(
                        \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                        $conditionalLeafComponentFieldNode->getConditionalNestedComponents()
                    )
                );
            }
        }

        if ($post_thumb = $this->getPostThumbSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['postthumb'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($post_thumb);
        } elseif (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($user_avatar = $this->getUserAvatarComponent($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['user-avatar'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($user_avatar);
            }
        }

        return $ret;
    }

    public function initWebPlatformModelProps(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if (in_array(
            [GD_AAL_Module_Processor_QuicklinkGroups::class, GD_AAL_Module_Processor_QuicklinkGroups::COMPONENT_AAL_QUICKLINKGROUP_NOTIFICATION],
            $this->getSubcomponents($component)
        )) {
            //-----------------------------------
            // Whenever clicking on the link on the notification, also "click" on the `Mark as read` button
            //-----------------------------------
            if ($link = $this->getLinkSubcomponent($component)) {
                $this->mergeProp(
                    [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD],
                    $props,
                    'previouscomponents-ids',
                    array(
                        'data-triggertarget' => $link
                    )
                );
                $this->mergeJsmethodsProp([AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD], $props, array('onActionThenClick'));
            }

            //-----------------------------------
            // Add class "read" to the notification layout to make it appear as read or not when clicking on the corresponding button
            //-----------------------------------
            $this->mergeProp(
                [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD],
                $props,
                'params',
                array(
                    'data-mode' => 'add',
                    'data-class' => AAL_POP_STATUS_READ,
                )
            );
            $this->mergeProp(
                [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD],
                $props,
                'previouscomponents-ids',
                array(
                    'data-target' => $component,
                )
            );
            $this->mergeJsmethodsProp([AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD], $props, array('switchTargetClass'));

            //-----------------------------------
            // Remove class "read" to the notification layout to make it appear as read or not when clicking on the corresponding button
            //-----------------------------------
            $this->mergeProp(
                [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD],
                $props,
                'params',
                array(
                    'data-mode' => 'remove',
                    'data-class' => AAL_POP_STATUS_READ,
                )
            );
            $this->mergeProp(
                [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD],
                $props,
                'previouscomponents-ids',
                array(
                    'data-target' => $component,
                )
            );
            $this->mergeJsmethodsProp([AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD], $props, array('switchTargetClass'));
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}
