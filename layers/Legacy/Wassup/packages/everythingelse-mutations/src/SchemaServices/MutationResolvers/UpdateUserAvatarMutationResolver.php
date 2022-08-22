<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use GD_FileUpload_UserPhotoFactory;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class UpdateUserAvatarMutationResolver extends AbstractMutationResolver
{
    public function savePicture($user_id, $delete_source = false): void
    {
        // Avatar
        $gd_fileupload_userphoto = GD_FileUpload_UserPhotoFactory::getInstance();
        $gd_fileupload_userphoto->savePicture($user_id, $delete_source);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $user_id = $fieldDataAccessor->getValue('user_id');
        $this->savePicture($user_id);
        $this->additionals($user_id, $fieldDataAccessor);

        return $user_id;
    }

    protected function additionals(string|int $user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('gd_useravatar_update:additionals', $user_id, $fieldDataAccessor);
    }
}
