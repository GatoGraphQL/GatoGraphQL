<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Types\Status;
use PoPSitesWassup\CustomPostMutations\MutationResolverBridges\AbstractCreateUpdateCustomPostMutationResolverBridge;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class AbstractCreateUpdateHighlightMutationResolverBridge extends AbstractCreateUpdateCustomPostMutationResolverBridge
{
    protected function supportsTitle()
    {
        return false;
    }

    protected function moderate()
    {
        return false;
    }

    /**
     * Watch out! This functions is called from nowhere!
     * Lost during the migration!
     * @todo: Restore calling this function
     */
    protected function getSuccessTitle($referenced = null)
    {
        if ($referenced) {
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            return sprintf(
                TranslationAPIFacade::getInstance()->__('Highlight from “%s”', 'poptheme-wassup'),
                $customPostTypeAPI->getTitle($referenced)
            );
        }

        return TranslationAPIFacade::getInstance()->__('Highlight', 'poptheme-wassup');
    }

    public function getSuccessString($result_id): ?string
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $status = $customPostTypeAPI->getStatus($result_id);
        if ($status == Status::PUBLISHED) {
            // Give a link to the referenced post to the stance, and force it to get it from the server again
            $highlighted = \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($result_id, GD_METAKEY_POST_HIGHLIGHTEDPOST, true);
            $success_string = sprintf(
                TranslationAPIFacade::getInstance()->__('<a href="%s" %s>Click here to view it</a>.', 'poptheme-wassup'),
                $customPostTypeAPI->getPermalink($highlighted),
                getReloadurlLinkattrs()
            );

            return HooksAPIFacade::getInstance()->applyFilters('gd-createupdate-uniquereference:execute:successstring', $success_string, $result_id, $status);
        }

        return parent::getSuccessString($result_id);
    }

    protected function getEditorInput()
    {
        return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_TEXTAREAEDITOR];
    }

    public function getFormData(): array
    {
        $form_data = parent::getFormData();

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $form_data['highlightedpost'] = $moduleprocessor_manager->getProcessor([PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST])->getValue([PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST]);

        // Highlights have no title input by the user. Instead, produce the title from the referenced post
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $referenced = $customPostTypeAPI->getCustomPost($form_data['highlightedpost']);
        $form_data['title'] = $customPostTypeAPI->getTitle($referenced);

        return $form_data;
    }
}

