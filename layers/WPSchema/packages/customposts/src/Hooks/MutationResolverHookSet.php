<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\CustomPostCRUDHookNames;
use PoPWPSchema\CustomPosts\Constants\MutationInputProperties;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class MutationResolverHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CustomPostCRUDHookNames::GET_CREATE_OR_UPDATE_DATA,
            $this->addCreateOrUpdateCustomPostData(...),
            10,
            2
        );
    }

    protected function hasProvidedInput(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): bool {
        return $fieldDataAccessor->hasValue(MutationInputProperties::MENU_ORDER);
    }

    /**
     * @param array<string,mixed> $customPostData
     * @return array<string,mixed>
     */
    public function addCreateOrUpdateCustomPostData(
        array $customPostData,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        if (!$this->hasProvidedInput($fieldDataAccessor)) {
            return $customPostData;
        }
        /** @var int|null */
        $menuOrder = $fieldDataAccessor->getValue(MutationInputProperties::MENU_ORDER);
        $customPostData['menu-order'] = $menuOrder;
        return $customPostData;
    }
}
