<?php

abstract class PoP_Module_Processor_ButtonInnersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_BUTTONINNER];
    }

    // function getGlyphicon(array $component) {
    //     return null;
    // }
    public function getFontawesome(array $component, array &$props)
    {
        return null;
    }
    public function getTag(array $component)
    {
        return 'span';
    }
    public function getBtnTitle(array $component)
    {
        return null;
    }
    public function getTextField(array $component, array &$props)
    {
        return null;
    }
    public function getTextfieldOpen(array $component, array &$props)
    {
        return null;
    }
    public function getTextfieldClose(array $component, array &$props)
    {
        return null;
    }
    public function getBtntitleClass(array $component, array &$props)
    {
        return null;
    }
    public function getTextfieldClass(array $component, array &$props)
    {
        return null;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = array();
        if ($text_field = $this->getTextField($component, $props)) {
            $ret[] = $text_field;
        }
        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['tag'] = $this->getTag($component);

        if ($btn_title = $this->getBtnTitle($component)) {
            $ret[GD_JS_TITLES]['btn'] = $btn_title;
            if ($btntitle_class = $this->getBtntitleClass($component, $props)) {
                $ret[GD_JS_CLASSES]['btn-title'] = $btntitle_class;
            }
        }
        if ($text_field = $this->getTextField($component, $props)) {
            $ret['text-field'] = $text_field;
            if ($textfield_open = $this->getTextfieldOpen($component, $props)) {
                $ret['textfield-open'] = $textfield_open;
            }
            if ($textfield_close = $this->getTextfieldClose($component, $props)) {
                $ret['textfield-close'] = $textfield_close;
            }
            if ($classs = $this->getProp($component, $props, 'textfield-class')) {
                $ret[GD_JS_CLASSES]['text-field'] = $classs;
            }
        }

        // if ($glyphicon = $this->getGlyphicon($component)) {
        //     $ret['glyphicon'] = $glyphicon;
        // }
        if ($fontawesome = $this->getFontawesome($component, $props)) {
            $ret[GD_JS_FONTAWESOME] = $fontawesome;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'textfield-class', $this->getTextfieldClass($component, $props));
        parent::initModelProps($component, $props);
    }
}
