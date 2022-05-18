<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public final const COMPONENT_BLOCK_STANCE_UPDATE = 'block-stance-update';
    public final const COMPONENT_BLOCK_STANCE_CREATE = 'block-stance-create';
    public final const COMPONENT_BLOCK_STANCE_CREATEORUPDATE = 'block-stance-createorupdate';
    public final const COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE = 'block-singlepoststance-createorupdate';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_STANCE_UPDATE],
            [self::class, self::COMPONENT_BLOCK_STANCE_CREATE],
            [self::class, self::COMPONENT_BLOCK_STANCE_CREATEORUPDATE],
            [self::class, self::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::COMPONENT_BLOCK_STANCE_CREATE => POP_USERSTANCE_ROUTE_ADDSTANCE,
            self::COMPONENT_BLOCK_STANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::COMPONENT_BLOCK_STANCE_UPDATE => POP_USERSTANCE_ROUTE_EDITSTANCE,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_STANCE_CREATEORUPDATE:
            case self::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_EDITPOST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::COMPONENT_BLOCK_STANCE_UPDATE => [UserStance_Module_Processor_CreateUpdatePostDataloads::class, UserStance_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_STANCE_UPDATE],
            self::COMPONENT_BLOCK_STANCE_CREATE => [UserStance_Module_Processor_CreateUpdatePostDataloads::class, UserStance_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_STANCE_CREATE],
            self::COMPONENT_BLOCK_STANCE_CREATEORUPDATE => [UserStance_Module_Processor_CreateUpdatePostDataloads::class, UserStance_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_STANCE_CREATEORUPDATE],
            self::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE => [UserStance_Module_Processor_CreateUpdatePostDataloads::class, UserStance_Module_Processor_CreateUpdatePostDataloads::COMPONENT_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function isCreate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_STANCE_CREATE:
                return true;
        }

        return parent::isCreate($component);
    }
    protected function isUpdate(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_STANCE_UPDATE:
                return true;
        }

        return parent::isUpdate($component);
    }

    protected function getBlocksectionsClasses(array $component)
    {
        $ret = parent::getBlocksectionsClasses($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret['blocksection-inners'] = 'well';
                break;
        }

        return $ret;
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                // Allow Events to have a different title
                $title = sprintf(
                    TranslationAPIFacade::getInstance()->__('%s...', 'pop-userstance-processors'),
                    UserStance_Module_Processor_ButtonUtils::getSinglepostAddstanceTitle()
                );
                return sprintf(
                    '<small>%s</small>',
                    getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).$title
                );
        }

        return parent::getTitle($component, $props);
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_STANCE_CREATEORUPDATE:
            case self::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret['loadBlockContent']['loadcontent-showdisabledlayer'] = true;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_STANCE_CREATEORUPDATE:
            case self::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $this->appendProp($component, $props, 'class', 'pop-blockstance-createorupdate');
                break;
        }

        switch ($component[1]) {
         // Make it horizontal
            case self::COMPONENT_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                // Do not show in the quickview
                $this->appendProp($component, $props, 'class', 'pop-singlepoststance pop-hidden-quickview');
                $this->setProp([UserStance_Module_Processor_CreateUpdatePostForms::class, UserStance_Module_Processor_CreateUpdatePostForms::COMPONENT_FORM_STANCE], $props, 'horizontal', true);
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_STANCE_UPDATE:
            case self::COMPONENT_BLOCK_STANCE_CREATE:
                $this->appendProp($component, $props, 'class', 'addons-nocontrols');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



