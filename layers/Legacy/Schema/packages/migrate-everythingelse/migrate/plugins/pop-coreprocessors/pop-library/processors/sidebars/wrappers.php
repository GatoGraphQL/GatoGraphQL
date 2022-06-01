<?php

class PoP_Module_Processor_SidebarComponentWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_WIDGETWRAPPER_REFERENCES = 'widgetwrapper-references';
    public final const COMPONENT_WIDGETWRAPPER_REFERENCES_LINE = 'widgetwrapper-references-line';
    public final const COMPONENT_WIDGETWRAPPER_AUTHOR_CONTACT = 'widgetwrapper-author-contact';
    public final const COMPONENT_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT = 'layoutwrapper-comments-appendtoscript';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_WIDGETWRAPPER_REFERENCES,
            self::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE,
            self::COMPONENT_WIDGETWRAPPER_AUTHOR_CONTACT,
            self::COMPONENT_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
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

    public function getConditionFailedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionFailedSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsFramesLayouts::class, PoP_Module_Processor_CommentsFramesLayouts::COMPONENT_LAYOUT_COMMENTSEMPTY_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETWRAPPER_REFERENCES:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE:
                $this->appendProp($component, $props, 'class', 'references');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_WIDGETWRAPPER_REFERENCES:
            case self::COMPONENT_WIDGETWRAPPER_REFERENCES_LINE:
                return 'hasReferences';

            case self::COMPONENT_WIDGETWRAPPER_AUTHOR_CONTACT:
                return 'hasContact';
        }

        return null;
    }
}



