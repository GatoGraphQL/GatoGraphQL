<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_CreateUpdatePostBlocks extends PoP_Module_Processor_AddEditContentBlocksBase
{
    public const MODULE_BLOCK_STANCE_UPDATE = 'block-stance-update';
    public const MODULE_BLOCK_STANCE_CREATE = 'block-stance-create';
    public const MODULE_BLOCK_STANCE_CREATEORUPDATE = 'block-stance-createorupdate';
    public const MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE = 'block-singlepoststance-createorupdate';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_STANCE_UPDATE],
            [self::class, self::MODULE_BLOCK_STANCE_CREATE],
            [self::class, self::MODULE_BLOCK_STANCE_CREATEORUPDATE],
            [self::class, self::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::MODULE_BLOCK_STANCE_CREATE => POP_USERSTANCE_ROUTE_ADDSTANCE,
            self::MODULE_BLOCK_STANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            self::MODULE_BLOCK_STANCE_UPDATE => POP_USERSTANCE_ROUTE_EDITSTANCE,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_STANCE_CREATEORUPDATE:
            case self::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_EDITPOST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_STANCE_UPDATE => [UserStance_Module_Processor_CreateUpdatePostDataloads::class, UserStance_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_STANCE_UPDATE],
            self::MODULE_BLOCK_STANCE_CREATE => [UserStance_Module_Processor_CreateUpdatePostDataloads::class, UserStance_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_STANCE_CREATE],
            self::MODULE_BLOCK_STANCE_CREATEORUPDATE => [UserStance_Module_Processor_CreateUpdatePostDataloads::class, UserStance_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_STANCE_CREATEORUPDATE],
            self::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE => [UserStance_Module_Processor_CreateUpdatePostDataloads::class, UserStance_Module_Processor_CreateUpdatePostDataloads::MODULE_DATALOAD_SINGLEPOSTSTANCE_CREATEORUPDATE],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function isCreate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_STANCE_CREATE:
                return true;
        }

        return parent::isCreate($module);
    }
    protected function isUpdate(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_STANCE_UPDATE:
                return true;
        }

        return parent::isUpdate($module);
    }

    protected function getBlocksectionsClasses(array $module)
    {
        $ret = parent::getBlocksectionsClasses($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret['blocksection-inners'] = 'well';
                break;
        }

        return $ret;
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
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

        return parent::getTitle($module, $props);
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_BLOCK_STANCE_CREATEORUPDATE:
            case self::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $ret['loadBlockContent']['loadcontent-showdisabledlayer'] = true;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_STANCE_CREATEORUPDATE:
            case self::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                $this->appendProp($module, $props, 'class', 'pop-blockstance-createorupdate');
                break;
        }

        switch ($module[1]) {
         // Make it horizontal
            case self::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE:
                // Do not show in the quickview
                $this->appendProp($module, $props, 'class', 'pop-singlepoststance pop-hidden-quickview');
                $this->setProp([UserStance_Module_Processor_CreateUpdatePostForms::class, UserStance_Module_Processor_CreateUpdatePostForms::MODULE_FORM_STANCE], $props, 'horizontal', true);
                break;
        }

        switch ($module[1]) {
            case self::MODULE_BLOCK_STANCE_UPDATE:
            case self::MODULE_BLOCK_STANCE_CREATE:
                $this->appendProp($module, $props, 'class', 'addons-nocontrols');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



