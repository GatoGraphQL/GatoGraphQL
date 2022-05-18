<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_EDITOR = 'forminputeditorgroup';
    public final const MODULE_FORMINPUTGROUP_TEXTAREAEDITOR = 'forminput-textarea-editorgroup';
    public final const MODULE_FILTERINPUTGROUP_ORDERUSER = 'filterinputgroup-order-user';
    public final const MODULE_FILTERINPUTGROUP_ORDERPOST = 'filterinputgroup-order-post';
    public final const MODULE_FILTERINPUTGROUP_ORDERTAG = 'filterinputgroup-order-tag';
    public final const MODULE_FILTERINPUTGROUP_ORDERCOMMENT = 'filterinputgroup-order-comment';
    public final const MODULE_FILTERINPUTGROUP_SEARCH = 'filterinputgroup-searchfor';
    public final const MODULE_FILTERINPUTGROUP_HASHTAGS = 'filterinputgroup-hashtags';
    public final const MODULE_FILTERINPUTGROUP_NAME = 'filterinputgroup-nombre';
    public final const MODULE_FORMINPUTGROUP_EMAILS = 'ure-forminputgroup-emails';
    public final const MODULE_FORMINPUTGROUP_SENDERNAME = 'ure-forminputgroup-sendername';
    public final const MODULE_FORMINPUTGROUP_ADDITIONALMESSAGE = 'ure-forminputgroup-additionalmessage';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_EDITOR],
            [self::class, self::COMPONENT_FORMINPUTGROUP_TEXTAREAEDITOR],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_ORDERUSER],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_ORDERTAG],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_ORDERCOMMENT],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_SEARCH],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_HASHTAGS],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_NAME],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EMAILS],
            [self::class, self::COMPONENT_FORMINPUTGROUP_SENDERNAME],
            [self::class, self::COMPONENT_FORMINPUTGROUP_ADDITIONALMESSAGE],
        );
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_ORDERUSER:
            case self::COMPONENT_FILTERINPUTGROUP_ORDERPOST:
            case self::COMPONENT_FILTERINPUTGROUP_ORDERTAG:
            case self::COMPONENT_FILTERINPUTGROUP_ORDERCOMMENT:
            case self::COMPONENT_FILTERINPUTGROUP_SEARCH:
            case self::COMPONENT_FILTERINPUTGROUP_HASHTAGS:
            case self::COMPONENT_FILTERINPUTGROUP_NAME:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_ORDERUSER:
            case self::COMPONENT_FILTERINPUTGROUP_ORDERPOST:
            case self::COMPONENT_FILTERINPUTGROUP_ORDERTAG:
            case self::COMPONENT_FILTERINPUTGROUP_ORDERCOMMENT:
            case self::COMPONENT_FILTERINPUTGROUP_SEARCH:
            case self::COMPONENT_FILTERINPUTGROUP_HASHTAGS:
            case self::COMPONENT_FILTERINPUTGROUP_NAME:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_EDITOR => [PoP_Module_Processor_EditorFormInputs::class, PoP_Module_Processor_EditorFormInputs::COMPONENT_FORMINPUT_EDITOR],
            self::COMPONENT_FORMINPUTGROUP_TEXTAREAEDITOR => [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_TEXTAREAEDITOR],
            self::COMPONENT_FILTERINPUTGROUP_ORDERUSER => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERUSER],
            self::COMPONENT_FILTERINPUTGROUP_ORDERPOST => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            self::COMPONENT_FILTERINPUTGROUP_ORDERTAG => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERTAG],
            self::COMPONENT_FILTERINPUTGROUP_ORDERCOMMENT => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERCOMMENT],
            self::COMPONENT_FILTERINPUTGROUP_SEARCH => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
            self::COMPONENT_FILTERINPUTGROUP_HASHTAGS => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
            self::COMPONENT_FILTERINPUTGROUP_NAME => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_NAME],
            self::COMPONENT_FORMINPUTGROUP_EMAILS => [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_EMAILS],
            self::COMPONENT_FORMINPUTGROUP_SENDERNAME => [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_SENDERNAME],
            self::COMPONENT_FORMINPUTGROUP_ADDITIONALMESSAGE => [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_ADDITIONALMESSAGE],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_SENDERNAME:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_SENDERNAME:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_SENDERNAME:
                $this->appendProp($component, $props, 'class', 'visible-notloggedin');

                // If we don't use the loggedinuser-data, then show the inputs always
                if (!PoP_FormUtils::useLoggedinuserData()) {
                    $this->appendProp($component, $props, 'class', 'visible-always');
                }
                break;

            case self::COMPONENT_FORMINPUTGROUP_EMAILS:
                $placeholder = TranslationAPIFacade::getInstance()->__('Type emails here, separated by space or comma (" " or ","), or 1 email per line', 'pop-coreprocessors');
                $this->setProp($this->getComponentSubmodule($component), $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



