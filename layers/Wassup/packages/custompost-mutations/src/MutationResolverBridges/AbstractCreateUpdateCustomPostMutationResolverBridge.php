<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostMutations\MutationResolverBridges;

use PoP\EditPosts\HelperAPIFactory;
use PoPSchema\Posts\Constants\InputNames;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSitesWassup\CustomPostMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties as CustomPostMediaMutationInputProperties;
use PoP\ComponentModel\MutationResolverBridges\AbstractCRUDComponentMutationResolverBridge;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;

abstract class AbstractCreateUpdateCustomPostMutationResolverBridge extends AbstractCRUDComponentMutationResolverBridge
{
    public const HOOK_FORM_DATA_CREATE_OR_UPDATE = __CLASS__ . ':form-data-create-or-update';

    protected function modifyDataProperties(array &$data_properties, string | int $result_id): void
    {
        parent::modifyDataProperties($data_properties, $result_id);

        $data_properties[DataloadingConstants::QUERYARGS]['status'] = [
            Status::PUBLISHED,
            Status::PENDING,
            Status::DRAFT,
        ];
    }

    /**
     * The ID comes directly as a parameter in the request, it's not a form field
     */
    protected function getUpdateCustomPostID(): string | int | null
    {
        return $_REQUEST[InputNames::POST_ID] ?? null;
    }

    abstract protected function isUpdate(): bool;

    public function getFormData(): array
    {
        $form_data = [];
        if ($this->isUpdate()) {
            $form_data[MutationInputProperties::ID] = $this->getUpdateCustomPostID();
        }

        if ($this->supportsTitle()) {
            $form_data[MutationInputProperties::TITLE] = trim(strip_tags($this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, \PoP_Module_Processor_CreateUpdatePostTextFormInputs::MODULE_FORMINPUT_CUP_TITLE])->getValue([\PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, \PoP_Module_Processor_CreateUpdatePostTextFormInputs::MODULE_FORMINPUT_CUP_TITLE])));
        }

        $editor = $this->getEditorInput();
        if ($editor !== null) {
            $cmseditpostshelpers = HelperAPIFactory::getInstance();
            $form_data[MutationInputProperties::CONTENT] = trim($cmseditpostshelpers->kses(stripslashes($this->moduleProcessorManager->getProcessor($editor)->getValue($editor))));
        }

        if ($this->showCategories()) {
            $form_data[MutationInputProperties::CATEGORIES] = $this->getCategories();
        }

        // Status: 2 possibilities:
        // - Moderate: then using the Draft/Pending/Publish Select, user cannot choose 'Publish' when creating a post
        // - No moderation: using the 'Keep as Draft' checkbox, completely omitting value 'Pending', post is either 'draft' or 'publish'
        if ($this->moderate()) {
            $form_data[MutationInputProperties::STATUS] = $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, \PoP_Module_Processor_CreateUpdatePostSelectFormInputs::MODULE_FORMINPUT_CUP_STATUS])->getValue([\PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, \PoP_Module_Processor_CreateUpdatePostSelectFormInputs::MODULE_FORMINPUT_CUP_STATUS]);
        } else {
            $keepasdraft = $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, \PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::MODULE_FORMINPUT_CUP_KEEPASDRAFT])->getValue([\PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, \PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::MODULE_FORMINPUT_CUP_KEEPASDRAFT]);
            $form_data[MutationInputProperties::STATUS] = $keepasdraft ? Status::DRAFT : Status::PUBLISHED;
        }

        if ($featuredimage = $this->getFeaturedimageModule()) {
            $form_data[CustomPostMediaMutationInputProperties::FEATUREDIMAGE_ID] = $this->moduleProcessorManager->getProcessor($featuredimage)->getValue($featuredimage);
        }

        if ($this->addReferences()) {
            $references = $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, \PoP_Module_Processor_PostSelectableTypeaheadFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES])->getValue([\PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, \PoP_Module_Processor_PostSelectableTypeaheadFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES]);
            $form_data[MutationInputProperties::REFERENCES] = $references ?? array();
        }

        if (\PoP_ApplicationProcessors_Utils::addCategories()) {
            $topics = $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, \PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_CATEGORIES])->getValue([\PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, \PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_CATEGORIES]);
            $form_data[MutationInputProperties::TOPICS] = $topics ?? array();
        }

        // Only if the Volunteering is enabled
        if (defined('POP_VOLUNTEERING_INITIALIZED')) {
            if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                if ($this->volunteer()) {
                    $form_data[MutationInputProperties::VOLUNTEERSNEEDED] = $this->moduleProcessorManager->getProcessor([\GD_Custom_Module_Processor_SelectFormInputs::class, \GD_Custom_Module_Processor_SelectFormInputs::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT])->getValue([\GD_Custom_Module_Processor_SelectFormInputs::class, GD_Custom_Module_Processor_SelectFormInputs::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT]);
                }
            }
        }

        if (\PoP_ApplicationProcessors_Utils::addAppliesto()) {
            $appliesto = $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, \PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_APPLIESTO])->getValue([\PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_APPLIESTO]);
            $form_data[MutationInputProperties::APPLIESTO] = $appliesto ?? array();
        }

        // Allow plugins to add their own fields
        return $this->hooksAPI->applyFilters(
            self::HOOK_FORM_DATA_CREATE_OR_UPDATE,
            $form_data
        );
    }

    protected function getEditorInput()
    {
        return [\PoP_Module_Processor_EditorFormInputs::class, \PoP_Module_Processor_EditorFormInputs::MODULE_FORMINPUT_EDITOR];
    }

    protected function getCategories()
    {
        if ($this->showCategories()) {
            if ($categories_module = $this->getCategoriesModule()) {
                // We might decide to allow the user to input many sections, or only one section, so this value might be an array or just the value
                // So treat it always as an array
                $categories = $this->moduleProcessorManager->getProcessor($categories_module)->getValue($categories_module);
                if ($categories && !is_array($categories)) {
                    $categories = array($categories);
                }

                return $categories;
            }
        }

        return array();
    }

    protected function showCategories()
    {
        return false;
    }

    protected function canInputMultipleCategories()
    {
        return false;
        // return $this->hooksAPI->applyFilters(
        //     'GD_CreateUpdate_Post:multiple-categories',
        //     true
        // );
    }

    protected function getCategoriesModule()
    {
        if ($this->showCategories()) {
            if ($this->canInputMultipleCategories()) {
                return [\PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::class, \PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS];
            }

            return [\PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::class, \PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION];
        }

        return null;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $status = $customPostTypeAPI->getStatus($result_id);
        if ($status == Status::PUBLISHED) {
            $success_string = sprintf(
                $this->translationAPI->__('<a href="%s" %s>Click here to view it</a>.', 'pop-application'),
                $customPostTypeAPI->getPermalink($result_id),
                getReloadurlLinkattrs()
            );
        } elseif ($status == Status::DRAFT) {
            $success_string = $this->translationAPI->__('The status is still “Draft”, so it won\'t be online.', 'pop-application');
        } elseif ($status == Status::PENDING) {
            $success_string = $this->translationAPI->__('Now waiting for approval from the admins.', 'pop-application');
        }

        return $this->hooksAPI->applyFilters('gd-createupdate-post:execute:successstring', $success_string, $result_id, $status);
    }

    protected function getFeaturedimageModule()
    {
        return [\PoP_Module_Processor_FeaturedImageFormComponents::class, \PoP_Module_Processor_FeaturedImageFormComponents::MODULE_FORMCOMPONENT_FEATUREDIMAGE];
    }

    protected function addReferences()
    {
        return true;
    }

    protected function volunteer()
    {
        return false;
    }

    protected function supportsTitle()
    {
        // Not all post types support a title
        return true;
    }

    protected function moderate()
    {
        return \GD_CreateUpdate_Utils::moderate();
    }
}
