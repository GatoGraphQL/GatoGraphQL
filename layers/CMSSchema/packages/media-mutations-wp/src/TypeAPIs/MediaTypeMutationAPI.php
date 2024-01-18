<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutationsWP\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;
use WP_Error;

class MediaTypeMutationAPI implements MediaTypeMutationAPIInterface
{
    use BasicServiceTrait;

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromURL(string $url, array $mediaItemData): string|int
    {
        // @todo Implement
        $filename = '';
        $body = '';
        return $this->createMediaItemFromContents($filename, $body, $mediaItemData);
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromContents(string $filename, string $body, array $mediaItemData): string|int
    {
        $uploadedFile = \wp_upload_bits($filename, null, $body);
        if (isset($uploadedFile['error'])) {
            /** @var string */
            $errorMessage = $uploadedFile['error'];
            throw new MediaItemCRUDMutationException(
                $errorMessage
            );
        }

        /** @var string */
        $uploadedFilename = $uploadedFile['file'];
        
        $mediaItemData = $this->convertMediaItemCreationArgs($mediaItemData);
        $customPostID = 0;
        if (isset($mediaItemData['customPostID'])) {
            $customPostID = $mediaItemData['customPostID'];
            unset($mediaItemData['customPostID']);
        }
        
        $mediaItemIDOrError = \wp_insert_attachment(
            $mediaItemData,
            $uploadedFilename,
            $customPostID,
            true
        );
        
        if (\is_wp_error($mediaItemIDOrError)) {
            /** @var WP_Error */
            $wpError = $mediaItemIDOrError;
            throw new MediaItemCRUDMutationException(
                $wpError->get_error_message()
            );
        }
        $mediaItemID = $mediaItemIDOrError;
        return $mediaItemID;
    }

    /**
     * @param array<string,mixed> $mediaItemData
     */
    protected function convertMediaItemCreationArgs(array $mediaItemData): array
    {
        if (isset($mediaItemData['authorID'])) {
            $mediaItemData['post_author'] = $mediaItemData['authorID'];
            unset($mediaItemData['authorID']);
        }
        if (isset($mediaItemData['title'])) {
            $mediaItemData['post_title'] = $mediaItemData['title'];
            unset($mediaItemData['title']);
        }
        if (isset($mediaItemData['slug'])) {
            $mediaItemData['post_name'] = $mediaItemData['slug'];
            unset($mediaItemData['slug']);
        }
        if (isset($mediaItemData['caption'])) {
            $mediaItemData['post_excerpt'] = $mediaItemData['caption'];
            unset($mediaItemData['caption']);
        }
        if (isset($mediaItemData['description'])) {
            $mediaItemData['post_content'] = $mediaItemData['description'];
            unset($mediaItemData['description']);
        }
        if (isset($mediaItemData['mimeType'])) {
            $mediaItemData['post_mime_type'] = $mediaItemData['mimeType'];
            unset($mediaItemData['mimeType']);
        }
        return $mediaItemData;
    }
}
