<?php
use PoP\Root\App;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoPTheme_Wassup_AE_Module_Processor_ContentBlocks extends PoP_CommonAutomatedEmails_Module_Processor_ContentBlocksBase
{
    public final const COMPONENT_BLOCK_AUTOMATEDEMAILS_SINGLEPOST = 'block-automatedemails-singlepost';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_AUTOMATEDEMAILS_SINGLEPOST,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_AUTOMATEDEMAILS_SINGLEPOST => POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getDescription(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($component->name) {
            case self::COMPONENT_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:
                $pid = App::query(\PoPCMSSchema\Posts\Constants\InputNames::POST_ID);
                return sprintf(
                    '<p>%s</p><h1>%s</h1>',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('Here we send you this special %s:', 'pop-commonautomatedemails-processors'),
                        gdGetPostname($pid, 'lc')
                    ),
                    $customPostTypeAPI->getTitle($pid)
                );
        }

        return parent::getDescription($component, $props);
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_ContentDataloads::class, PoPTheme_Wassup_AE_Module_Processor_ContentDataloads::COMPONENT_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST];
                break;
        }

        return $ret;
    }
}



