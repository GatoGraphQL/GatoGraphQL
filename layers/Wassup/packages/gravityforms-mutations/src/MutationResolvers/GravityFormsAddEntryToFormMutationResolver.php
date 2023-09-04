<?php

declare(strict_types=1);

namespace PoPSitesWassup\GravityFormsMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use RGForms;

class GravityFormsAddEntryToFormMutationResolver extends AbstractMutationResolver
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // $execution_response = do_shortcode('[gravityform id="'.$form_id.'" title="false" description="false" ajax="false"]');
        return RGForms::get_form($fieldDataAccessor->getValue('form_id'), false, false);
    }
}
