<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolverUtils;

use PoP\Hooks\Facades\HooksAPIFacade;

class MutationResolverUtils
{
    public static function getMyCommunityFormInputs()
    {
        $inputs = HooksAPIFacade::getInstance()->applyFilters(
            'GD_UserCommunities_MyCommunitiesUtils:form-inputs',
            array(
                'communities' => null,
            )
        );

        // If any input is null, throw an exception
        $null_inputs = array_filter($inputs, 'is_null');
        if ($null_inputs) {
            throw new Exception(
                sprintf(
                    'No form inputs defined for: %s',
                    '"'.implode('", "', array_keys($null_inputs)).'"'
                )
            );
        }

        return $inputs;
    }
}
