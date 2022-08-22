<?php

class PoP_Module_Processor_ScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const COMPONENT_SCRIPT_SINGLECOMMENT = 'script-singlecomment';
    public final const COMPONENT_SCRIPT_COMMENTS = 'script-comments';
    public final const COMPONENT_SCRIPT_COMMENTSEMPTY = 'script-commentsempty';
    public final const COMPONENT_SCRIPT_REFERENCES = 'script-references';
    public final const COMPONENT_SCRIPT_REFERENCESEMPTY = 'script-referencesempty';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCRIPT_SINGLECOMMENT,
            self::COMPONENT_SCRIPT_COMMENTS,
            self::COMPONENT_SCRIPT_COMMENTSEMPTY,
            self::COMPONENT_SCRIPT_REFERENCES,
            self::COMPONENT_SCRIPT_REFERENCESEMPTY,
        );
    }

    public function doAppend(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SCRIPT_COMMENTSEMPTY:
            case self::COMPONENT_SCRIPT_REFERENCESEMPTY:
                return false;
        }
        
        return parent::doAppend($component);
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SCRIPT_SINGLECOMMENT:
                return [PoP_Module_Processor_AppendCommentLayouts::class, PoP_Module_Processor_AppendCommentLayouts::COMPONENT_SCRIPT_APPENDCOMMENT];
        }

        return parent::getLayoutSubcomponent($component);
    }
    
    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_SCRIPT_SINGLECOMMENT:
            case self::COMPONENT_SCRIPT_COMMENTS:
            case self::COMPONENT_SCRIPT_COMMENTSEMPTY:
            case self::COMPONENT_SCRIPT_REFERENCES:
            case self::COMPONENT_SCRIPT_REFERENCESEMPTY:
                $classes = array(
                    self::COMPONENT_SCRIPT_SINGLECOMMENT => 'comments',
                    self::COMPONENT_SCRIPT_COMMENTS => 'comments',
                    self::COMPONENT_SCRIPT_COMMENTSEMPTY => 'comments',
                    self::COMPONENT_SCRIPT_REFERENCES => 'references',
                    self::COMPONENT_SCRIPT_REFERENCESEMPTY => 'references',
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$component->name];
                break;
        }
        
        return $ret;
    }
}



