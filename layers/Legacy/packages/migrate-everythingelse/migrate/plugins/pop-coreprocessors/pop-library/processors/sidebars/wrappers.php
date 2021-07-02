<?php

class PoP_Module_Processor_SidebarComponentWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public const MODULE_WIDGETWRAPPER_REFERENCES = 'widgetwrapper-references';
    public const MODULE_WIDGETWRAPPER_REFERENCES_LINE = 'widgetwrapper-references-line';
    public const MODULE_WIDGETWRAPPER_AUTHOR_CONTACT = 'widgetwrapper-author-contact';
    public const MODULE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT = 'layoutwrapper-comments-appendtoscript';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCES],
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCES_LINE],
            [self::class, self::MODULE_WIDGETWRAPPER_AUTHOR_CONTACT],
            [self::class, self::MODULE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_REFERENCES:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGET_REFERENCES];
                break;

            case self::MODULE_WIDGETWRAPPER_REFERENCES_LINE:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGET_REFERENCES_LINE];
                break;

            case self::MODULE_WIDGETWRAPPER_AUTHOR_CONTACT:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGET_AUTHOR_CONTACT];
                break;

            case self::MODULE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsFramesLayouts::class, PoP_Module_Processor_CommentsFramesLayouts::MODULE_LAYOUT_COMMENTS_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubmodules(array $module)
    {
        $ret = parent::getConditionFailedSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsFramesLayouts::class, PoP_Module_Processor_CommentsFramesLayouts::MODULE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_REFERENCES:
            case self::MODULE_WIDGETWRAPPER_REFERENCES_LINE:
                $this->appendProp($module, $props, 'class', 'references');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_REFERENCES:
            case self::MODULE_WIDGETWRAPPER_REFERENCES_LINE:
                return 'hasReferences';

            case self::MODULE_WIDGETWRAPPER_AUTHOR_CONTACT:
                return 'hasContact';
        }

        return null;
    }
}



