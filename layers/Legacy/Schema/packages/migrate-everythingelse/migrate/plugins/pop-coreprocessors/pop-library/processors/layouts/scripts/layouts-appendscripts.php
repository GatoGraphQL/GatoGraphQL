<?php

class PoP_Module_Processor_ScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const MODULE_SCRIPT_SINGLECOMMENT = 'script-singlecomment';
    public final const MODULE_SCRIPT_COMMENTS = 'script-comments';
    public final const MODULE_SCRIPT_COMMENTSEMPTY = 'script-commentsempty';
    public final const MODULE_SCRIPT_REFERENCES = 'script-references';
    public final const MODULE_SCRIPT_REFERENCESEMPTY = 'script-referencesempty';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCRIPT_SINGLECOMMENT],
            [self::class, self::MODULE_SCRIPT_COMMENTS],
            [self::class, self::MODULE_SCRIPT_COMMENTSEMPTY],
            [self::class, self::MODULE_SCRIPT_REFERENCES],
            [self::class, self::MODULE_SCRIPT_REFERENCESEMPTY],
        );
    }

    public function doAppend(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCRIPT_COMMENTSEMPTY:
            case self::MODULE_SCRIPT_REFERENCESEMPTY:
                return false;
        }
        
        return parent::doAppend($module);
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCRIPT_SINGLECOMMENT:
                return [PoP_Module_Processor_AppendCommentLayouts::class, PoP_Module_Processor_AppendCommentLayouts::MODULE_SCRIPT_APPENDCOMMENT];
        }

        return parent::getLayoutSubmodule($module);
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_SCRIPT_SINGLECOMMENT:
            case self::MODULE_SCRIPT_COMMENTS:
            case self::MODULE_SCRIPT_COMMENTSEMPTY:
            case self::MODULE_SCRIPT_REFERENCES:
            case self::MODULE_SCRIPT_REFERENCESEMPTY:
                $classes = array(
                    self::MODULE_SCRIPT_SINGLECOMMENT => 'comments',
                    self::MODULE_SCRIPT_COMMENTS => 'comments',
                    self::MODULE_SCRIPT_COMMENTSEMPTY => 'comments',
                    self::MODULE_SCRIPT_REFERENCES => 'references',
                    self::MODULE_SCRIPT_REFERENCESEMPTY => 'references',
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$module[1]];
                break;
        }
        
        return $ret;
    }
}



