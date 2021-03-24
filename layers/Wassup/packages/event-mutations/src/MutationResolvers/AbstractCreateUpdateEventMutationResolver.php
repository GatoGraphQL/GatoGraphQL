<?php

declare(strict_types=1);

namespace PoPSitesWassup\EventMutations\MutationResolvers;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\EventMutations\Facades\EventMutationTypeAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoPSitesWassup\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;

abstract class AbstractCreateUpdateEventMutationResolver extends AbstractCreateUpdateCustomPostMutationResolver
{
    public function getCustomPostType(): string
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        return $eventTypeAPI->getEventCustomPostType();
    }
    protected function volunteer()
    {
        return true;
    }

    // Update Post Validation
    protected function validateContent(array &$errors, array $form_data): void
    {
        parent::validateContent($errors, $form_data);

        // Validate for any status (even "draft"), since without date EM doesn't create the Event
        if (empty(array_filter(array_values($form_data['when'])))) {
            $errors[] = TranslationAPIFacade::getInstance()->__('The dates/time cannot be empty', 'poptheme-wassup');
        }
    }

    protected function getCreateCustomPostData(array $form_data): array
    {
        $post_data = parent::getCreateCustomPostData($form_data);
        $post_data = $this->getCreateUpdateEventData($post_data, $form_data);
        return $post_data;
    }

    protected function getUpdateCustomPostData(array $form_data): array
    {
        $post_data = parent::getUpdateCustomPostData($form_data);
        $post_data = $this->getCreateUpdateEventData($post_data, $form_data);
        return $post_data;
    }

    protected function getCreateUpdateEventData(array $post_data, array $form_data): array
    {
        $post_data['when'] = $form_data['when'];
        $post_data['location'] = $form_data['location'];
        return $post_data;
    }

    protected function populate(\EM_Event &$EM_Event, array $post_data): void
    {
        $eventMutationTypeAPI = EventMutationTypeAPIFacade::getInstance();
        $eventMutationTypeAPI->populate($EM_Event, $post_data);
    }

    protected function save(\EM_Event &$EM_Event, array $post_data): string | int
    {
        $EM_Event = $this->populate($EM_Event, $post_data);
        $EM_Event->save();
        return $EM_Event->post_id;
    }

    protected function executeUpdateCustomPost(array $post_data): string | int | null | Error
    {
        $EM_Event = new \EM_Event($post_data['id'], 'post_id');
        return $this->save($EM_Event, $post_data);
    }

    protected function executeCreateCustomPost(array $post_data): string | int | null | Error
    {
        $EM_Event = new \EM_Event();
        return $this->save($EM_Event, $post_data);
    }
}
