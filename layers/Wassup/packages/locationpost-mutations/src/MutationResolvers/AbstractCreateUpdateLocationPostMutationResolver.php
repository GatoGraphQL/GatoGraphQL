<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostMutations\MutationResolvers;

use PoPSitesWassup\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;

abstract class AbstractCreateUpdateLocationPostMutationResolver extends AbstractCreateUpdateCustomPostMutationResolver
{
    public function getCustomPostType(): string
    {
        return \POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST;
    }

    /**
     * @param mixed $post_id
     */
    protected function additionals($post_id, array $form_data): void
    {
        parent::additionals($post_id, $form_data);

        // Locations
        \PoPSchema\CustomPostMeta\Utils::updateCustomPostMeta($post_id, \GD_METAKEY_POST_LOCATIONS, $form_data['locations']);
    }
}
