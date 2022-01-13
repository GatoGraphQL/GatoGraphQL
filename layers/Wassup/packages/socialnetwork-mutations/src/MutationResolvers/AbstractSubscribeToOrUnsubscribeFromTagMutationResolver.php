<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Root\App;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;

abstract class AbstractSubscribeToOrUnsubscribeFromTagMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }

    public function validateErrors(array $form_data): array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $target_id = $form_data['target_id'];

            // Make sure the post exists
            $target = $this->getPostTagTypeAPI()->getTag($target_id);
            if (!$target) {
                $errors[] = $this->__('The requested topic/tag does not exist.', 'pop-coreprocessors');
            }
        }
        return $errors;
    }

    protected function additionals($target_id, $form_data): void
    {
        App::getHookManager()->doAction('gd_subscritetounsubscribefrom_tag', $target_id, $form_data);
        parent::additionals($target_id, $form_data);
    }
}
