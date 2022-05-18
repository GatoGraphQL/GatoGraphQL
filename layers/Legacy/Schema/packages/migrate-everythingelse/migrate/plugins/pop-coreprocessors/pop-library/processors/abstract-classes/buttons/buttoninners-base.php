<?php

abstract class PoP_Module_Processor_ButtonInnersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_BUTTONINNER];
    }

    // function getGlyphicon(array $componentVariation) {
    //     return null;
    // }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getTag(array $componentVariation)
    {
        return 'span';
    }
    public function getBtnTitle(array $componentVariation)
    {
        return null;
    }
    public function getTextField(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getTextfieldOpen(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getTextfieldClose(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getBtntitleClass(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getTextfieldClass(array $componentVariation, array &$props)
    {
        return null;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = array();
        if ($text_field = $this->getTextField($componentVariation, $props)) {
            $ret[] = $text_field;
        }
        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['tag'] = $this->getTag($componentVariation);

        if ($btn_title = $this->getBtnTitle($componentVariation)) {
            $ret[GD_JS_TITLES]['btn'] = $btn_title;
            if ($btntitle_class = $this->getBtntitleClass($componentVariation, $props)) {
                $ret[GD_JS_CLASSES]['btn-title'] = $btntitle_class;
            }
        }
        if ($text_field = $this->getTextField($componentVariation, $props)) {
            $ret['text-field'] = $text_field;
            if ($textfield_open = $this->getTextfieldOpen($componentVariation, $props)) {
                $ret['textfield-open'] = $textfield_open;
            }
            if ($textfield_close = $this->getTextfieldClose($componentVariation, $props)) {
                $ret['textfield-close'] = $textfield_close;
            }
            if ($classs = $this->getProp($componentVariation, $props, 'textfield-class')) {
                $ret[GD_JS_CLASSES]['text-field'] = $classs;
            }
        }

        // if ($glyphicon = $this->getGlyphicon($componentVariation)) {
        //     $ret['glyphicon'] = $glyphicon;
        // }
        if ($fontawesome = $this->getFontawesome($componentVariation, $props)) {
            $ret[GD_JS_FONTAWESOME] = $fontawesome;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'textfield-class', $this->getTextfieldClass($componentVariation, $props));
        parent::initModelProps($componentVariation, $props);
    }
}
