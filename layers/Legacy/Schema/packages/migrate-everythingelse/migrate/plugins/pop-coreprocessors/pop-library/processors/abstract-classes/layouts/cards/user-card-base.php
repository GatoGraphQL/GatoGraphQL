<?php
use PoP\ConfigurationComponentModel\Facades\TypeResolverHelperService\TypeResolverHelperServiceFacade;

abstract class PoP_Module_Processor_UserCardLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTUSER_CARD];
    }

    public function getAdditionalSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {

        // Allow URE to override adding their own templates to include Community members in the filter
        return \PoP\Root\App::applyFilters('PoP_Module_Processor_UserCardLayoutsBase:getAdditionalSubcomponents', array(), $component);
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
        if ($extra_templates = $this->getAdditionalSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $extra_templates
            );
        }
        return $ret;
    }
    
    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFieldNodes($component, $props);

        // Important: the TYPEAHEAD_COMPONENT should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
        // but it applies to Twitter Typeahead, which doesn't need these parameters, however these, here, upset the whole getDatasetComponentTreeSectionFlattenedDataProperties
        // To fix this, in self::COMPONENT_FORMINPUT_TYPEAHEAD data_properties we stop spreading down, so it never reaches below there to get further data-fields

        // Important: for Component the size is fixed! It can't be changed from 'avatar-40', because it is hardcoded
        // in layoutuser-typeahead-component.tmpl
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = $this->getAvatarSize($component, $props);
            $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);
        }

        /* FIX THIS: 'url' */
        // is-community needed for the Community filter (it will print a checkbox with msg 'include members?')
        return array_merge(
            $ret,
            $avatar_field ? array($avatar_field) : array(),
            array('displayName', 'url', 'isCommunity')
        );
    }
    
    public function getAvatarSize(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return GD_AVATAR_SIZE_40;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = $this->getAvatarSize($component, $props);
            $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

            $ret['avatar'] = array(
                'name' => TypeResolverHelperServiceFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                    $this->getProp($component, $props, 'succeeding-typeResolver'),
                    $avatar_field // @todo Fix: pass LeafField
                ),
                'size' => $avatar_size
            );
        }

        if ($extras = $this->getAdditionalSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['extras'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...), 
                $extras
            );
        }
        
        return $ret;
    }
}
