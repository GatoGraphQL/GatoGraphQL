<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Types;

interface CustomPostTypeInterface
{
    /**
     * Return the object's ID
     */
    public function getID(object $object): mixed;
    public function getContent($objectOrID): ?string;
    public function getPlainTextContent($objectOrID): string;
    public function getPermalink($objectOrID): ?string;
    public function getSlug($postObjectOrID): ?string;
    public function getStatus($objectOrID): ?string;
    public function getPublishedDate($objectOrID): ?string;
    public function getModifiedDate($objectOrID): ?string;
    public function getTitle($objectOrID): ?string;
    public function getExcerpt($objectOrID): ?string;
}
