<?php

class PoP_Module_Processor_SidebarComponentWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_WIDGETWRAPPER_REFERENCES = 'widgetwrapper-references';
    public final const COMPONENT_WIDGETWRAPPER_REFERENCES_LINE = 'widgetwrapper-references-line';
    public final const COMPONENT_WIDGETWRAPPER_AUTHOR_CONTACT = 'widgetwrapper-author-contact';
    public final const COMPONENT_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT = 'layoutwrapper-comments-appendtoscript';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGETWRAPPER_REFERENCES],
            [self::class, self::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE],
            [self::class, self::COMPONENT_WIDGETWRAPPER_AUTHOR_CONTACT],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT],
        );
    }

    public function getConditionSucceededSubcomponents(array $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGETWRAPPER_REFERENCES:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_REFERENCES];
                break;

            case self::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_REFERENCES_LINE];
                break;

            case self::COMPONENT_WIDGETWRAPPER_AUTHOR_CONTACT:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::COMPONENT_WIDGET_AUTHOR_CONTACT];
                break;

            case self::COMPONENT_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsFramesLayouts::class, PoP_Module_Processor_CommentsFramesLayouts::COMPONENT_LAYOUT_COMMENTS_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubcomponents(array $component)
    {
        $ret = parent::getConditionFailedSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsFramesLayouts::class, PoP_Module_Processor_CommentsFramesLayouts::COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETWRAPPER_REFERENCES:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE:
                $this->appendProp($component, $props, 'class', 'references');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETWRAPPER_REFERENCES:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE:
                return 'hasReferences';

            case self::COMPONENT_WIDGETWRAPPER_AUTHOR_CONTACT:
                return 'hasContact';
        }

        return null;
    }
}



