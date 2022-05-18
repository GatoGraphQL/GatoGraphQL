<?php

class PoP_Module_Processor_SidebarComponentWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_WIDGETWRAPPER_REFERENCES = 'widgetwrapper-references';
    public final const MODULE_WIDGETWRAPPER_REFERENCES_LINE = 'widgetwrapper-references-line';
    public final const MODULE_WIDGETWRAPPER_AUTHOR_CONTACT = 'widgetwrapper-author-contact';
    public final const MODULE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT = 'layoutwrapper-comments-appendtoscript';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCES],
            [self::class, self::MODULE_WIDGETWRAPPER_REFERENCES_LINE],
            [self::class, self::MODULE_WIDGETWRAPPER_AUTHOR_CONTACT],
            [self::class, self::MODULE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT],
        );
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionSucceededSubmodules($componentVariation);

        switch ($componentVariation[1]) {
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

    public function getConditionFailedSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionFailedSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsFramesLayouts::class, PoP_Module_Processor_CommentsFramesLayouts::MODULE_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETWRAPPER_REFERENCES:
            case self::MODULE_WIDGETWRAPPER_REFERENCES_LINE:
                $this->appendProp($componentVariation, $props, 'class', 'references');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getConditionField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETWRAPPER_REFERENCES:
            case self::MODULE_WIDGETWRAPPER_REFERENCES_LINE:
                return 'hasReferences';

            case self::MODULE_WIDGETWRAPPER_AUTHOR_CONTACT:
                return 'hasContact';
        }

        return null;
    }
}



