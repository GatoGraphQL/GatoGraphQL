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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_EDITOR],
            [self::class, self::MODULE_FORMINPUTGROUP_TEXTAREAEDITOR],
            [self::class, self::MODULE_FILTERINPUTGROUP_ORDERUSER],
            [self::class, self::MODULE_FILTERINPUTGROUP_ORDERPOST],
            [self::class, self::MODULE_FILTERINPUTGROUP_ORDERTAG],
            [self::class, self::MODULE_FILTERINPUTGROUP_ORDERCOMMENT],
            [self::class, self::MODULE_FILTERINPUTGROUP_SEARCH],
            [self::class, self::MODULE_FILTERINPUTGROUP_HASHTAGS],
            [self::class, self::MODULE_FILTERINPUTGROUP_NAME],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILS],
            [self::class, self::MODULE_FORMINPUTGROUP_SENDERNAME],
            [self::class, self::MODULE_FORMINPUTGROUP_ADDITIONALMESSAGE],
        );
    }

    public function getLabelClass(array $componentVariation)
    {
        $ret = parent::getLabelClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTGROUP_ORDERUSER:
            case self::MODULE_FILTERINPUTGROUP_ORDERPOST:
            case self::MODULE_FILTERINPUTGROUP_ORDERTAG:
            case self::MODULE_FILTERINPUTGROUP_ORDERCOMMENT:
            case self::MODULE_FILTERINPUTGROUP_SEARCH:
            case self::MODULE_FILTERINPUTGROUP_HASHTAGS:
            case self::MODULE_FILTERINPUTGROUP_NAME:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $componentVariation)
    {
        $ret = parent::getFormcontrolClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTGROUP_ORDERUSER:
            case self::MODULE_FILTERINPUTGROUP_ORDERPOST:
            case self::MODULE_FILTERINPUTGROUP_ORDERTAG:
            case self::MODULE_FILTERINPUTGROUP_ORDERCOMMENT:
            case self::MODULE_FILTERINPUTGROUP_SEARCH:
            case self::MODULE_FILTERINPUTGROUP_HASHTAGS:
            case self::MODULE_FILTERINPUTGROUP_NAME:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_EDITOR => [PoP_Module_Processor_EditorFormInputs::class, PoP_Module_Processor_EditorFormInputs::MODULE_FORMINPUT_EDITOR],
            self::MODULE_FORMINPUTGROUP_TEXTAREAEDITOR => [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_TEXTAREAEDITOR],
            self::MODULE_FILTERINPUTGROUP_ORDERUSER => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
            self::MODULE_FILTERINPUTGROUP_ORDERPOST => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            self::MODULE_FILTERINPUTGROUP_ORDERTAG => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERTAG],
            self::MODULE_FILTERINPUTGROUP_ORDERCOMMENT => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERCOMMENT],
            self::MODULE_FILTERINPUTGROUP_SEARCH => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
            self::MODULE_FILTERINPUTGROUP_HASHTAGS => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
            self::MODULE_FILTERINPUTGROUP_NAME => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
            self::MODULE_FORMINPUTGROUP_EMAILS => [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_EMAILS],
            self::MODULE_FORMINPUTGROUP_SENDERNAME => [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SENDERNAME],
            self::MODULE_FORMINPUTGROUP_ADDITIONALMESSAGE => [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_ADDITIONALMESSAGE],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_SENDERNAME:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_SENDERNAME:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_SENDERNAME:
                $this->appendProp($componentVariation, $props, 'class', 'visible-notloggedin');

                // If we don't use the loggedinuser-data, then show the inputs always
                if (!PoP_FormUtils::useLoggedinuserData()) {
                    $this->appendProp($componentVariation, $props, 'class', 'visible-always');
                }
                break;

            case self::MODULE_FORMINPUTGROUP_EMAILS:
                $placeholder = TranslationAPIFacade::getInstance()->__('Type emails here, separated by space or comma (" " or ","), or 1 email per line', 'pop-coreprocessors');
                $this->setProp($this->getComponentSubmodule($componentVariation), $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



