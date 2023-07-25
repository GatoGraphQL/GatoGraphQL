<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostMutations\MutationResolverBridges;

use GD_CreateUpdate_Utils;
use GD_Custom_Module_Processor_SelectFormInputs;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\MutationResolverBridges\AbstractCRUDComponentMutationResolverBridge;
use PoP\EditPosts\HelperAPIFactory;
use PoP\Root\App;
use PoP_ApplicationProcessors_Utils;
use PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs;
use PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs;
use PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs;
use PoP_Module_Processor_CreateUpdatePostSelectFormInputs;
use PoP_Module_Processor_CreateUpdatePostTextFormInputs;
use PoP_Module_Processor_EditorFormInputs;
use PoP_Module_Processor_FeaturedImageFormComponents;
use PoP_Module_Processor_PostSelectableTypeaheadFormComponents;
use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties as CustomPostMediaMutationInputProperties;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\Posts\Constants\InputNames;
use PoPSitesWassup\CustomPostMutations\Constants\MutationInputProperties;

abstract class AbstractCreateOrUpdateCustomPostMutationResolverBridge extends AbstractCRUDComponentMutationResolverBridge
{
    public final const HOOK_FORM_DATA_CREATE_OR_UPDATE = __CLASS__ . ':form-data-create-or-update';

    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }

    /**
     * @param array<string,mixed> $data_properties
     */
    protected function modifyDataProperties(array &$data_properties, string|int $result_id): void
    {
        parent::modifyDataProperties($data_properties, $result_id);

        $data_properties[DataloadingConstants::QUERYARGS]['status'] = [
            CustomPostStatus::PUBLISH,
            CustomPostStatus::PENDING,
            CustomPostStatus::DRAFT,
        ];
    }

    /**
     * The ID comes directly as a parameter in the request, it's not a form field
     */
    protected function getUpdateCustomPostID(): string|int|null
    {
        return App::request(InputNames::POST_ID) ?? App::query(InputNames::POST_ID);
    }

    abstract protected function isUpdate(): bool;

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        if ($this->isUpdate()) {
            $mutationData[MutationInputProperties::ID] = $this->getUpdateCustomPostID();
        }

        if ($this->supportsTitle()) {
            $mutationData[MutationInputProperties::TITLE] = trim(strip_tags($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::COMPONENT_FORMINPUT_CUP_TITLE])->getValue([PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::COMPONENT_FORMINPUT_CUP_TITLE])));
        }

        $editor = $this->getEditorInput();
        if ($editor !== null) {
            $cmseditpostshelpers = HelperAPIFactory::getInstance();
            $mutationData[MutationInputProperties::CONTENT] = trim($cmseditpostshelpers->kses(stripslashes($this->getComponentProcessorManager()->getComponentProcessor($editor)->getValue($editor))));
        }

        if ($this->showCategories()) {
            $mutationData[MutationInputProperties::CATEGORIES] = $this->getCategories();
        }

        // Status: 2 possibilities:
        // - Moderate: then using the Draft/Pending/Publish Select, user cannot choose 'Publish' when creating a post
        // - No moderation: using the 'Keep as Draft' checkbox, completely omitting value 'Pending', post is either 'draft' or 'publish'
        if ($this->moderate()) {
            $mutationData[MutationInputProperties::STATUS] = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_FORMINPUT_CUP_STATUS])->getValue([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_FORMINPUT_CUP_STATUS]);
        } else {
            $keepasdraft = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT])->getValue([PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT]);
            $mutationData[MutationInputProperties::STATUS] = $keepasdraft ? CustomPostStatus::DRAFT : CustomPostStatus::PUBLISH;
        }

        if ($featuredimage = $this->getFeaturedimageComponent()) {
            $mutationData[CustomPostMediaMutationInputProperties::FEATUREDIMAGE_BY]['id'] = $this->getComponentProcessorManager()->getComponentProcessor($featuredimage)->getValue($featuredimage);
        }

        if ($this->addReferences()) {
            $references = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES])->getValue([PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES]);
            $mutationData[MutationInputProperties::REFERENCES] = $references ?? array();
        }

        if (PoP_ApplicationProcessors_Utils::addCategories()) {
            $topics = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_CATEGORIES])->getValue([PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_CATEGORIES]);
            $mutationData[MutationInputProperties::TOPICS] = $topics ?? array();
        }

        // Only if the Volunteering is enabled
        if (defined('POP_VOLUNTEERING_INITIALIZED')) {
            if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                if ($this->volunteer()) {
                    $mutationData[MutationInputProperties::VOLUNTEERSNEEDED] = $this->getComponentProcessorManager()->getComponentProcessor([GD_Custom_Module_Processor_SelectFormInputs::class, GD_Custom_Module_Processor_SelectFormInputs::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT])->getValue([GD_Custom_Module_Processor_SelectFormInputs::class, GD_Custom_Module_Processor_SelectFormInputs::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT]);
                }
            }
        }

        if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
            $appliesto = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_APPLIESTO])->getValue([PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_APPLIESTO]);
            $mutationData[MutationInputProperties::APPLIESTO] = $appliesto ?? array();
        }

        // Allow plugins to add their own fields
        $mutationDataInArray = [&$mutationData];
        App::doAction(
            self::HOOK_FORM_DATA_CREATE_OR_UPDATE,
            $mutationDataInArray
        );
    }

    /**
     * @return mixed[]
     */
    protected function getEditorInput(): array
    {
        return [PoP_Module_Processor_EditorFormInputs::class, PoP_Module_Processor_EditorFormInputs::COMPONENT_FORMINPUT_EDITOR];
    }

    protected function getCategories()
    {
        if ($this->showCategories()) {
            if ($categories_component = $this->getCategoriesComponent()) {
                // We might decide to allow the user to input many sections, or only one section, so this value might be an array or just the value
                // So treat it always as an array
                $categories = $this->getComponentProcessorManager()->getComponentProcessor($categories_component)->getValue($categories_component);
                if ($categories && !is_array($categories)) {
                    $categories = array($categories);
                }

                return $categories;
            }
        }

        return array();
    }

    protected function showCategories(): bool
    {
        return false;
    }

    protected function canInputMultipleCategories(): bool
    {
        return false;
        // return \PoP\Root\App::applyFilters(
        //     'GD_CreateUpdate_Post:multiple-categories',
        //     true
        // );
    }

    protected function getCategoriesComponent(): ?array
    {
        if ($this->showCategories()) {
            if ($this->canInputMultipleCategories()) {
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTIONS];
            }

            return [PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTION];
        }

        return null;
    }

    public function getSuccessString(string|int $result_id): ?string
    {
        $status = $this->getCustomPostTypeAPI()->getStatus($result_id);
        if ($status === CustomPostStatus::PUBLISH) {
            $success_string = sprintf(
                $this->__('<a href="%s" %s>Click here to view it</a>.', 'pop-application'),
                $this->getCustomPostTypeAPI()->getPermalink($result_id),
                getReloadurlLinkattrs()
            );
        } elseif ($status === CustomPostStatus::DRAFT) {
            $success_string = $this->__('The status is still “Draft”, so it won\'t be online.', 'pop-application');
        } elseif ($status === CustomPostStatus::PENDING) {
            $success_string = $this->__('Now waiting for approval from the admins.', 'pop-application');
        }

        return App::applyFilters('gd-createupdate-post:execute:successstring', $success_string, $result_id, $status);
    }

    /**
     * @return mixed[]
     */
    protected function getFeaturedimageComponent(): array
    {
        return [PoP_Module_Processor_FeaturedImageFormComponents::class, PoP_Module_Processor_FeaturedImageFormComponents::COMPONENT_FORMCOMPONENT_FEATUREDIMAGE];
    }

    protected function addReferences(): bool
    {
        return true;
    }

    protected function volunteer(): bool
    {
        return false;
    }

    protected function supportsTitle(): bool
    {
        // Not all post types support a title
        return true;
    }

    protected function moderate()
    {
        return GD_CreateUpdate_Utils::moderate();
    }
}
