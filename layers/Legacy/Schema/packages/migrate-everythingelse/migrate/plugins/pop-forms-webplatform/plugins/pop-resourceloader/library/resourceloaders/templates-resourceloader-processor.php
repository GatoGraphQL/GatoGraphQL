<?php

class PoP_Forms_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_FORM = 'form';
    public final const RESOURCE_FORM_INNER = 'form_inner';
    public final const RESOURCE_FORMINPUT_BUTTONGROUP = 'forminput_buttongroup';
    public final const RESOURCE_FORMINPUT_CHECKBOX = 'forminput_checkbox';
    public final const RESOURCE_FORMINPUT_DATERANGE = 'forminput_daterange';
    public final const RESOURCE_FORMINPUT_EDITOR = 'forminput_editor';
    public final const RESOURCE_FORMINPUT_SELECT = 'forminput_select';
    public final const RESOURCE_FORMINPUT_TEXT = 'forminput_text';
    public final const RESOURCE_FORMINPUT_TEXTAREA = 'forminput_textarea';
    public final const RESOURCE_FORMCOMPONENT_INPUTGROUP = 'formcomponent_inputgroup';
    public final const RESOURCE_FORMCOMPONENT_SELECTABLETYPEAHEAD = 'formcomponent_selectabletypeahead';
    public final const RESOURCE_FORMCOMPONENTVALUE_TRIGGERLAYOUT = 'formcomponentvalue_triggerlayout';
    public final const RESOURCE_FORMGROUP = 'formgroup';
    public final const RESOURCE_EXTENSIONTYPEAHEADSUGGESTIONS = 'extensiontypeaheadsuggestions';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_FORM],
            [self::class, self::RESOURCE_FORM_INNER],
            [self::class, self::RESOURCE_FORMINPUT_BUTTONGROUP],
            [self::class, self::RESOURCE_FORMINPUT_CHECKBOX],
            [self::class, self::RESOURCE_FORMINPUT_DATERANGE],
            [self::class, self::RESOURCE_FORMINPUT_EDITOR],
            [self::class, self::RESOURCE_FORMINPUT_SELECT],
            [self::class, self::RESOURCE_FORMINPUT_TEXT],
            [self::class, self::RESOURCE_FORMINPUT_TEXTAREA],
            [self::class, self::RESOURCE_FORMCOMPONENT_INPUTGROUP],
            [self::class, self::RESOURCE_FORMCOMPONENT_SELECTABLETYPEAHEAD],
            [self::class, self::RESOURCE_FORMCOMPONENTVALUE_TRIGGERLAYOUT],
            [self::class, self::RESOURCE_FORMGROUP],
            [self::class, self::RESOURCE_EXTENSIONTYPEAHEADSUGGESTIONS],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_FORM => POP_TEMPLATE_FORM,
            self::RESOURCE_FORM_INNER => POP_TEMPLATE_FORM_INNER,
            self::RESOURCE_FORMINPUT_BUTTONGROUP => POP_TEMPLATE_FORMINPUT_BUTTONGROUP,
            self::RESOURCE_FORMINPUT_CHECKBOX => POP_TEMPLATE_FORMINPUT_CHECKBOX,
            self::RESOURCE_FORMINPUT_DATERANGE => POP_TEMPLATE_FORMINPUT_DATERANGE,
            self::RESOURCE_FORMINPUT_EDITOR => POP_TEMPLATE_FORMINPUT_EDITOR,
            self::RESOURCE_FORMINPUT_SELECT => POP_TEMPLATE_FORMINPUT_SELECT,
            self::RESOURCE_FORMINPUT_TEXT => POP_TEMPLATE_FORMINPUT_TEXT,
            self::RESOURCE_FORMINPUT_TEXTAREA => POP_TEMPLATE_FORMINPUT_TEXTAREA,
            self::RESOURCE_FORMCOMPONENT_INPUTGROUP => POP_TEMPLATE_FORMCOMPONENT_INPUTGROUP,
            self::RESOURCE_FORMCOMPONENT_SELECTABLETYPEAHEAD => POP_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD,
            self::RESOURCE_FORMCOMPONENTVALUE_TRIGGERLAYOUT => POP_TEMPLATE_FORMCOMPONENTVALUE_TRIGGERLAYOUT,
            self::RESOURCE_FORMGROUP => POP_TEMPLATE_FORMGROUP,
            self::RESOURCE_EXTENSIONTYPEAHEADSUGGESTIONS => POP_TEMPLATE_EXTENSIONTYPEAHEADSUGGESTIONS,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_FORMSWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_FORMSWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_FORMSWEBPLATFORM_DIR.'/js/dist/templates';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_FORMINPUT_BUTTONGROUP:
            case self::RESOURCE_FORMINPUT_SELECT:
            case self::RESOURCE_FORMINPUT_CHECKBOX:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_COMPARE];
                break;

            case self::RESOURCE_FORMCOMPONENT_SELECTABLETYPEAHEAD:
            case self::RESOURCE_FORMCOMPONENTVALUE_TRIGGERLAYOUT:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_ARRAYS];
                break;

            case self::RESOURCE_FORMINPUT_EDITOR:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_REPLACE];
                break;
        }

        switch ($resource[1]) {
            case self::RESOURCE_FORMINPUT_BUTTONGROUP:
            case self::RESOURCE_FORMINPUT_CHECKBOX:
            case self::RESOURCE_FORMINPUT_DATERANGE:
            case self::RESOURCE_FORMINPUT_SELECT:
                $dependencies[] = [PoP_Forms_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_Forms_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_FORMATVALUE];
                break;
        }

        switch ($resource[1]) {
            case self::RESOURCE_FORMCOMPONENTVALUE_TRIGGERLAYOUT:
            case self::RESOURCE_FORMINPUT_BUTTONGROUP:
            case self::RESOURCE_FORMINPUT_CHECKBOX:
            case self::RESOURCE_FORMINPUT_DATERANGE:
            case self::RESOURCE_FORMINPUT_EDITOR:
            case self::RESOURCE_FORMINPUT_SELECT:
            case self::RESOURCE_FORMINPUT_TEXT:
            case self::RESOURCE_FORMINPUT_TEXTAREA:
                $dependencies[] = [PoP_Forms_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_Forms_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_FORMCOMPONENTS];
                break;
        }

        return $dependencies;
    }
}


