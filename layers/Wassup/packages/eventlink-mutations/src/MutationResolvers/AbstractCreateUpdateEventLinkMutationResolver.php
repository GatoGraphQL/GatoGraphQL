<?php

declare(strict_types=1);

namespace PoPSitesWassup\EventLinkMutations\MutationResolvers;

use EM_Event;
use PoPSitesWassup\EventMutations\MutationResolvers\AbstractCreateUpdateEventMutationResolver;
use PoPSitesWassup\CustomPostLinkMutations\MutationResolvers\MutationResolverUtils;

abstract class AbstractCreateUpdateEventLinkMutationResolver extends AbstractCreateUpdateEventMutationResolver
{
    protected function populate(object &$event, array $post_data): void
    {
        /** @var EM_Event */
        $EM_Event = &$event;
        // Add class "Link" on the event object
        if (!$EM_Event->get_categories()->terms[\POP_EVENTLINKS_CAT_EVENTLINKS]) {
            $EM_Event->get_categories()->terms[\POP_EVENTLINKS_CAT_EVENTLINKS] = new \EM_Category(\POP_EVENTLINKS_CAT_EVENTLINKS);
        }
        parent::populate($EM_Event, $post_data);
    }


    /**
     * Function below was copied from class GD_CreateUpdate_PostLink
     */
    protected function validateContent(array &$errors, array $form_data): void
    {
        parent::validateContent($errors, $form_data);
        MutationResolverUtils::validateContent($errors, $form_data);
    }
}
