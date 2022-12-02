<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use PoP\Root\App;
use PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues;
use PoP_Module_Processor_TextareaFormInputs;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPSitesWassup\CustomPostMutations\MutationResolverBridges\AbstractCreateOrUpdateCustomPostMutationResolverBridge;

abstract class AbstractCreateUpdateHighlightMutationResolverBridge extends AbstractCreateOrUpdateCustomPostMutationResolverBridge
{
    protected function supportsTitle(): bool
    {
        return false;
    }

    protected function moderate(): bool
    {
        return false;
    }

    /**
     * Watch out! This functions is called from nowhere!
     * Lost during the migration!
     * @todo: Restore calling this function
     */
    protected function getSuccessTitle($referenced = null): string
    {
        if ($referenced) {
            return sprintf(
                $this->__('Highlight from “%s”', 'poptheme-wassup'),
                $this->getCustomPostTypeAPI()->getTitle($referenced)
            );
        }

        return $this->__('Highlight', 'poptheme-wassup');
    }

    public function getSuccessString(string|int $result_id): ?string
    {
        $status = $this->getCustomPostTypeAPI()->getStatus($result_id);
        if ($status == CustomPostStatus::PUBLISH) {
            // Give a link to the referenced post to the stance, and force it to get it from the server again
            $highlighted = Utils::getCustomPostMeta($result_id, GD_METAKEY_POST_HIGHLIGHTEDPOST, true);
            $success_string = sprintf(
                $this->__('<a href="%s" %s>Click here to view it</a>.', 'poptheme-wassup'),
                $this->getCustomPostTypeAPI()->getPermalink($highlighted),
                getReloadurlLinkattrs()
            );

            return App::applyFilters('gd-createupdate-uniquereference:execute:successstring', $success_string, $result_id, $status);
        }

        return parent::getSuccessString($result_id);
    }

    /**
     * @return mixed[]
     */
    protected function getEditorInput(): array
    {
        return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_TEXTAREAEDITOR];
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        parent::addMutationDataForFieldDataAccessor($mutationData);

        $highlightedPost = $this->getComponentProcessorManager()->getComponentProcessor([PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST])->getValue([PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST]);
        $mutationData['highlightedpost'] = $highlightedPost;

        // Highlights have no title input by the user. Instead, produce the title from the referenced post
        $referenced = $this->getCustomPostTypeAPI()->getCustomPost($highlightedPost);
        $mutationData['title'] = $this->getCustomPostTypeAPI()->getTitle($referenced);
    }
}
