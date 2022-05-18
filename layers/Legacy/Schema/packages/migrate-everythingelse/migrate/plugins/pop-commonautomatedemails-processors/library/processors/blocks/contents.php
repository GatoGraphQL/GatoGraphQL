<?php
use PoP\Root\App;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoPTheme_Wassup_AE_Module_Processor_ContentBlocks extends PoP_CommonAutomatedEmails_Module_Processor_ContentBlocksBase
{
    public final const MODULE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST = 'block-automatedemails-singlepost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST => POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getDescription(array $component, array &$props)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:
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

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_ContentDataloads::class, PoPTheme_Wassup_AE_Module_Processor_ContentDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST];
                break;
        }

        return $ret;
    }
}



