<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\AbstractCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentAuthorNameIsMissingMutationErrorPayloadObjectTypeResolverPicker extends AbstractCommentAuthorNameIsMissingErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->mustUserBeLoggedInToAddComment()) {
            return [];
        }
        return [
            AbstractCommentMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
