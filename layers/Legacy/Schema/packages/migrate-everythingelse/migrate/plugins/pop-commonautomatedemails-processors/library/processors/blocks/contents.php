<?php
use PoP\Root\App;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoPTheme_Wassup_AE_Module_Processor_ContentBlocks extends PoP_CommonAutomatedEmails_Module_Processor_ContentBlocksBase
{
    public final const MODULE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST = 'block-automatedemails-singlepost';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST => POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getDescription(array $componentVariation, array &$props)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($componentVariation[1]) {
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

        return parent::getDescription($componentVariation, $props);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:
                $ret[] = [PoPTheme_Wassup_AE_Module_Processor_ContentDataloads::class, PoPTheme_Wassup_AE_Module_Processor_ContentDataloads::MODULE_DATALOAD_AUTOMATEDEMAILS_SINGLEPOST];
                break;
        }

        return $ret;
    }
}



