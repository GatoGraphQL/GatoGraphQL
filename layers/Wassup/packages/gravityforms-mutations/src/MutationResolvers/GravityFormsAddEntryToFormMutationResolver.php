<?php

declare(strict_types=1);

namespace PoPSitesWassup\GravityFormsMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class GravityFormsAddEntryToFormMutationResolver extends AbstractMutationResolver
{
    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        // $execution_response = do_shortcode('[gravityform id="'.$form_id.'" title="false" description="false" ajax="false"]');
        return \RGForms::get_form($form_data['form_id'], false, false);
    }
}
