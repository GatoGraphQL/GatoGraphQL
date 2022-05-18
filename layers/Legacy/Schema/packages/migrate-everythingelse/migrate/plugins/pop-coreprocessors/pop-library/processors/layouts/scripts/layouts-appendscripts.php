<?php

class PoP_Module_Processor_ScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const MODULE_SCRIPT_SINGLECOMMENT = 'script-singlecomment';
    public final const MODULE_SCRIPT_COMMENTS = 'script-comments';
    public final const MODULE_SCRIPT_COMMENTSEMPTY = 'script-commentsempty';
    public final const MODULE_SCRIPT_REFERENCES = 'script-references';
    public final const MODULE_SCRIPT_REFERENCESEMPTY = 'script-referencesempty';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCRIPT_SINGLECOMMENT],
            [self::class, self::COMPONENT_SCRIPT_COMMENTS],
            [self::class, self::COMPONENT_SCRIPT_COMMENTSEMPTY],
            [self::class, self::COMPONENT_SCRIPT_REFERENCES],
            [self::class, self::COMPONENT_SCRIPT_REFERENCESEMPTY],
        );
    }

    public function doAppend(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCRIPT_COMMENTSEMPTY:
            case self::COMPONENT_SCRIPT_REFERENCESEMPTY:
                return false;
        }
        
        return parent::doAppend($component);
    }

    public function getLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCRIPT_SINGLECOMMENT:
                return [PoP_Module_Processor_AppendCommentLayouts::class, PoP_Module_Processor_AppendCommentLayouts::COMPONENT_SCRIPT_APPENDCOMMENT];
        }

        return parent::getLayoutSubmodule($component);
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
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
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$component[1]];
                break;
        }
        
        return $ret;
    }
}



