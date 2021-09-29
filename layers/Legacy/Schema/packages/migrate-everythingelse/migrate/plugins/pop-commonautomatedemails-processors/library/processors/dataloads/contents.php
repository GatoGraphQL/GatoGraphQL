<?php
use PoP\Engine\ModuleProcessors\ObjectIDFromURLParamModuleProcessorTrait;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class PoPTheme_Wassup_AE_Module_Processor_ContentDataloads extends PoP_Module_Processor_DataloadsBase
{
    use ObjectIDFromURLParamModuleProcessorTrait;

    public const MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST = 'dataload-automatedemails-singlepost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST => POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST:
                // Add the Sidebar on the top
                $pid = $_REQUEST[\PoPSchema\Posts\Constants\InputNames::POST_ID];
                if ($layout = HooksAPIFacade::getInstance()->applyFilters(
                    'PoPTheme_Wassup_AE_Module_Processor_ContentDataloads:singlepost:sidebar',
                    [PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebars::class, PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST],
                    $pid
                )
                ) {
                    $ret[] = $layout;
                }

                $ret[] = [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE];
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST:
                return $this->getObjectIDFromURLParam($module, $props, $data_properties);
        }
        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    protected function getObjectIDParamName(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST:
                return \PoPSchema\Posts\Constants\InputNames::POST_ID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST:
                // Decide on the typeResolver based on the type of the single element
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}



