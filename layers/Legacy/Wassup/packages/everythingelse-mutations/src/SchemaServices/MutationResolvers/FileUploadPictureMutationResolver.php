<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use GD_FileUpload_UserPhotoFactory;
use PoP\Root\Exception\AbstractException;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class FileUploadPictureMutationResolver extends AbstractMutationResolver
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataProvider): mixed
    {
        // Copy the images to the fileupload-userphoto upload folder
        $user_id = $fieldDataProvider->get('user_id');
        $gd_fileupload_userphoto = GD_FileUpload_UserPhotoFactory::getInstance();
        $gd_fileupload_userphoto->copyPicture($user_id);
        return $user_id;
    }
}
