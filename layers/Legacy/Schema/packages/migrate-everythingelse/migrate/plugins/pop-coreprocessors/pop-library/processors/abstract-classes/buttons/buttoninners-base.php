<?php

abstract class PoP_Module_Processor_ButtonInnersBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_BUTTONINNER];
    }

    // function getGlyphicon(array $module) {
    //     return null;
    // }
    public function getFontawesome(array $module, array &$props)
    {
        return null;
    }
    public function getTag(array $module)
    {
        return 'span';
    }
    public function getBtnTitle(array $module)
    {
        return null;
    }
    public function getTextField(array $module, array &$props)
    {
        return null;
    }
    public function getTextfieldOpen(array $module, array &$props)
    {
        return null;
    }
    public function getTextfieldClose(array $module, array &$props)
    {
        return null;
    }
    public function getBtntitleClass(array $module, array &$props)
    {
        return null;
    }
    public function getTextfieldClass(array $module, array &$props)
    {
        return null;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = array();
        if ($text_field = $this->getTextField($module, $props)) {
            $ret[] = $text_field;
        }
        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['tag'] = $this->getTag($module);

        if ($btn_title = $this->getBtnTitle($module)) {
            $ret[GD_JS_TITLES]['btn'] = $btn_title;
            if ($btntitle_class = $this->getBtntitleClass($module, $props)) {
                $ret[GD_JS_CLASSES]['btn-title'] = $btntitle_class;
            }
        }
        if ($text_field = $this->getTextField($module, $props)) {
            $ret['text-field'] = $text_field;
            if ($textfield_open = $this->getTextfieldOpen($module, $props)) {
                $ret['textfield-open'] = $textfield_open;
            }
            if ($textfield_close = $this->getTextfieldClose($module, $props)) {
                $ret['textfield-close'] = $textfield_close;
            }
            if ($classs = $this->getProp($module, $props, 'textfield-class')) {
                $ret[GD_JS_CLASSES]['text-field'] = $classs;
            }
        }

        // if ($glyphicon = $this->getGlyphicon($module)) {
        //     $ret['glyphicon'] = $glyphicon;
        // }
        if ($fontawesome = $this->getFontawesome($module, $props)) {
            $ret[GD_JS_FONTAWESOME] = $fontawesome;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'textfield-class', $this->getTextfieldClass($module, $props));
        parent::initModelProps($module, $props);
    }
}
