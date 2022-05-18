<?php
use PoP\Engine\ComponentProcessors\ObjectIDFromURLParamComponentProcessorTrait;
use PoP\Root\App;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class PoPTheme_Wassup_AE_Module_Processor_ContentDataloads extends PoP_Module_Processor_DataloadsBase
{
    use ObjectIDFromURLParamComponentProcessorTrait;

    public final const MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST = 'dataload-automatedemails-singlepost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST => POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST:
                // Add the Sidebar on the top
                $pid = App::query(\PoPCMSSchema\Posts\Constants\InputNames::POST_ID);
                if ($layout = \PoP\Root\App::applyFilters(
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

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST:
                return $this->getObjectIDFromURLParam($component, $props, $data_properties);
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    protected function getObjectIDParamName(array $component, array &$props, array &$data_properties): ?string
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST:
                return \PoPCMSSchema\Posts\Constants\InputNames::POST_ID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST:
                // Decide on the typeResolver based on the type of the single element
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}



