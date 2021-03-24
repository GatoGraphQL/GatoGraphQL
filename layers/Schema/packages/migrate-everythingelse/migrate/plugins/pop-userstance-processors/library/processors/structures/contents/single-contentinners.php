<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class UserStance_Module_Processor_SingleContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public const MODULE_CONTENTINNER_USERSTANCEPOSTINTERACTION = 'contentinner-userstancepostinteraction';
    public const MODULE_CONTENTINNER_STANCESINGLE = 'contentinner-stancesingle';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTINNER_USERSTANCEPOSTINTERACTION],
            [self::class, self::MODULE_CONTENTINNER_STANCESINGLE],
        );
    }

    protected function getCommentssingleLayouts(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTINNER_USERSTANCEPOSTINTERACTION:
                $layouts = array(
                    [UserStance_Module_Processor_CustomWrapperLayouts::class, UserStance_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION],
                    [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER],
                    [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS],
                );
                break;
        }

        return HooksAPIFacade::getInstance()->applyFilters('UserStance_Module_Processor_SingleContentInners:commentssingle_layouts', $layouts, $module);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CONTENTINNER_USERSTANCEPOSTINTERACTION:
                $ret = array_merge(
                    $ret,
                    $this->getCommentssingleLayouts($module)
                );
                break;

            case self::MODULE_CONTENTINNER_STANCESINGLE:
                $ret[] = [UserStance_Module_Processor_Layouts::class, UserStance_Module_Processor_Layouts::MODULE_LAYOUTSTANCE];
                $ret[] = [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTINNER_USERSTANCEPOSTINTERACTION:
                $this->appendProp($module, $props, 'class', 'userpostinteraction-single');
                break;

            case self::MODULE_CONTENTINNER_STANCESINGLE:
                $this->appendProp($module, $props, 'class', 'alert');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $taxonomyapi = \PoPSchema\Taxonomies\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_CONTENTINNER_STANCESINGLE:
                $vars = ApplicationState::getVars();
                $post_id = $vars['routing-state']['queried-object-id'];
                if (POP_USERSTANCE_TERM_STANCE_PRO && $taxonomyapi->hasTerm(POP_USERSTANCE_TERM_STANCE_PRO, POP_USERSTANCE_TAXONOMY_STANCE, $post_id)) {
                    $class = 'alert-success';
                } elseif (POP_USERSTANCE_TERM_STANCE_AGAINST && $taxonomyapi->hasTerm(POP_USERSTANCE_TERM_STANCE_AGAINST, POP_USERSTANCE_TAXONOMY_STANCE, $post_id)) {
                    $class = 'alert-danger';
                } elseif (POP_USERSTANCE_TERM_STANCE_NEUTRAL && $taxonomyapi->hasTerm(POP_USERSTANCE_TERM_STANCE_NEUTRAL, POP_USERSTANCE_TAXONOMY_STANCE, $post_id)) {
                    $class = 'alert-info';
                }
                if ($class) {
                    $this->appendProp($module, $props, 'runtime-class', $class);
                }
                break;
        }

        parent::initRequestProps($module, $props);
    }
}


