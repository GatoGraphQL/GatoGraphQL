<?php

declare(strict_types=1);

namespace PoPSitesWassup\GravityFormsMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use RGForms;
use PoP\Root\Exception\AbstractException;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class GravityFormsAddEntryToFormMutationResolver extends AbstractMutationResolver
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        // $execution_response = do_shortcode('[gravityform id="'.$form_id.'" title="false" description="false" ajax="false"]');
        return RGForms::get_form($mutationDataProvider->getValue('form_id'), false, false);
    }
}
