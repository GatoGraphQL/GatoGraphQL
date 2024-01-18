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
		require_once ABSPATH . 'wp-admin/includes/file.php';

        $downloadedFileOrError = \download_url($url);

        if (\is_wp_error($downloadedFileOrError)) {
            /** @var WP_Error */
            $wpError = $downloadedFileOrError;
            throw new MediaItemCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        $downloadedFile = $downloadedFileOrError;

        return $this->createMediaItemFromLocalFile(
            $downloadedFile,
            $mediaItemData,
        );
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromContents(string $filename, string $body, array $mediaItemData): string|int
    {
		$uploadedFileOrError = \wp_upload_bits($filename, null, $body);
        if (isset($uploadedFileOrError['error'])) {
            /** @var string */
            $errorMessage = $uploadedFileOrError['error'];
            throw new MediaItemCRUDMutationException(
                $errorMessage
            );
        }

        $uploadedFile = $uploadedFileOrError;
        
        /** @var string */
        $file = $uploadedFile['file'];
        return $this->createMediaItemFromLocalFile(
            $file,
            $mediaItemData,
        );
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    protected function createMediaItemFromLocalFile(
        string $file,
        array $mediaItemData
    ): string|int {
		$fileData = [
            'name' => \sanitize_file_name(basename($file)),
            'type' => !empty($mediaItemData['mimeType']) ? $mediaItemData['mimeType'] : \wp_check_filetype($file),
            'tmp_name' => $file,
            'error' => 0,
            'size' => filesize($file),
        ];

        $uploadedFile = \wp_handle_sideload(
            $fileData,
            [
                'test_form' => false,
            ]
        );

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
