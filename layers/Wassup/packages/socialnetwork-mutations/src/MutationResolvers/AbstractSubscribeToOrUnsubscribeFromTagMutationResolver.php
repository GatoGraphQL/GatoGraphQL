<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

abstract class AbstractSubscribeToOrUnsubscribeFromTagMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    public function validateErrors(array $form_data): ?array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $target_id = $form_data['target_id'];

            // Make sure the post exists
            $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
            $target = $postTagTypeAPI->getTag($target_id);
            if (!$target) {
                $errors[] = $this->translationAPI->__('The requested topic/tag does not exist.', 'pop-coreprocessors');
            }
        }
        return $errors;
    }

    protected function additionals($target_id, $form_data)
    {
        $this->hooksAPI->doAction('gd_subscritetounsubscribefrom_tag', $target_id, $form_data);
        parent::additionals($target_id, $form_data);
    }
}
