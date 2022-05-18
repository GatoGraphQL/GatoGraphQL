<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_FeaturedImageInnerFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_ContentCreation_TemplateResourceLoaderProcessor::class, PoP_ContentCreation_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_FEATUREDIMAGE_INNER];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'addDomainClass');
        $this->addJsmethod($ret, 'featuredImageSet', 'set');
        $this->addJsmethod($ret, 'featuredImageRemove', 'remove');
        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_TITLES] = array(
            'btn-add' => TranslationAPIFacade::getInstance()->__('Set Featured Image', 'pop-coreprocessors'),
            'btn-remove' => TranslationAPIFacade::getInstance()->__('Remove', 'pop-coreprocessors'),
            'usernotloggedin' => sprintf(
                TranslationAPIFacade::getInstance()->__('Please %s to set the featured image.', 'pop-coreprocessors'),
                gdGetLoginHtml()
            )
        );

        $ret['default-img'] = $this->getProp($componentVariation, $props, 'default-img');
        $ret[GD_JS_CLASSES]['img'] = $this->getProp($componentVariation, $props, 'img-class');
        $ret[GD_JS_CLASSES]['set-btn'] = $this->getProp($componentVariation, $props, 'setbtn-class');
        $ret[GD_JS_CLASSES]['remove-btn'] = $this->getProp($componentVariation, $props, 'removebtn-class');
        $ret[GD_JS_CLASSES]['options'] = $this->getProp($componentVariation, $props, 'options-class');

        $ret['fields'] = array(
            'featuredImageAttrs' => 'featuredImageAttrs',
        );
        $ret['subfields'] = array(
            'featuredImageAttrs' => array(
                'src' => array('src'),
                'width' => array('width'),
                'height' => array('height'),
            ),
        );

        return $ret;
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        return 'featuredImage';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);
        $ret[] = 'featuredImageAttrs';
        return $ret;
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'featuredimage-';

        // Send the dbObject structure to the webplatform, to recreate it with dynamic data
        $datum_placeholder = array(
            'featuredImage' => '{0}',
            'featuredImageAttrs' => array(
                'src' => '{1}',
                'width' => '{2}',
                'height' => '{3}',
            ),
        );
        return array_merge(
            $ret,
            array(
                'datum-placeholder' => json_encode($datum_placeholder),
            )
        );
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'img-class', 'img-responsive');
        $this->setProp($componentVariation, $props, 'setbtn-class', 'btn btn-sm btn-primary');
        $this->setProp($componentVariation, $props, 'removebtn-class', 'btn btn-sm btn-danger');
        $this->setProp($componentVariation, $props, 'options-class', 'options');
        parent::initModelProps($componentVariation, $props);
    }
}
