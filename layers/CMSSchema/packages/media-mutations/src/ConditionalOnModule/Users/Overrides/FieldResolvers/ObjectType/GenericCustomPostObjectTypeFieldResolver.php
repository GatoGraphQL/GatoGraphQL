<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ConditionalOnModule\Users\Overrides\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType\AbstractCreateMediaItemObjectTypeFieldResolver;
use PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType\GenericCustomPostObjectTypeFieldResolverTrait;

class GenericCustomPostObjectTypeFieldResolver extends AbstractCreateMediaItemObjectTypeFieldResolver
{
    use GenericCustomPostObjectTypeFieldResolverTrait;
}
