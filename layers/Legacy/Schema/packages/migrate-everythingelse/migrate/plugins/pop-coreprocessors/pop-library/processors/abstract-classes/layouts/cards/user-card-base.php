<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_UserCardLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTUSER_CARD];
    }

    public function getAdditionalSubmodules(array $component)
    {

        // Allow URE to override adding their own templates to include Community members in the filter
        return \PoP\Root\App::applyFilters('PoP_Module_Processor_UserCardLayoutsBase:getAdditionalSubmodules', array(), $component);
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
        if ($extra_templates = $this->getAdditionalSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $extra_templates
            );
        }
        return $ret;
    }
    
    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        // Important: the TYPEAHEAD_COMPONENT should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
        // but it applies to Twitter Typeahead, which doesn't need these parameters, however these, here, upset the whole getDatasetmoduletreeSectionFlattenedDataFields
        // To fix this, in self::MODULE_FORMINPUT_TYPEAHEAD data_properties we stop spreading down, so it never reaches below there to get further data-fields

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
    
    public function getAvatarSize(array $component, array &$props)
    {
        return GD_AVATAR_SIZE_40;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            $avatar_size = $this->getAvatarSize($component, $props);
            $avatar_field = PoP_AvatarFoundationManagerFactory::getInstance()->getAvatarField($avatar_size);

            $ret['avatar'] = array(
                'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                    $this->getProp($component, $props, 'succeeding-typeResolver'),
                    $avatar_field
                ),
                'size' => $avatar_size
            );
        }

        if ($extras = $this->getAdditionalSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['extras'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $extras
            );
        }
        
        return $ret;
    }
}
