<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType;

/**
 * Class to be used only when a generic CustomPost type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class GenericCustomPostObjectTypeResolver extends CustomPostObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPost';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('A custom post that does not have its own type in the schema', 'customposts');
    }
}
