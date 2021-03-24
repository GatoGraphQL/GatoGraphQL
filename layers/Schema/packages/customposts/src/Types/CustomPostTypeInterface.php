<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Types;

interface CustomPostTypeInterface
{
    /**
     * Return the object's ID
     */
    public function getID(object $customPostObject): string | int;
    public function getContent(string | int | object $customPostObjectOrID): ?string;
    public function getPlainTextContent(string | int | object $customPostObjectOrID): string;
    public function getPermalink(string | int | object $customPostObjectOrID): ?string;
    public function getSlug(string | int | object $customPostObjectOrID): ?string;
    public function getStatus(string | int | object $customPostObjectOrID): ?string;
    public function getPublishedDate(string | int | object $customPostObjectOrID): ?string;
    public function getModifiedDate(string | int | object $customPostObjectOrID): ?string;
    public function getTitle(string | int | object $customPostObjectOrID): ?string;
    public function getExcerpt(string | int | object $customPostObjectOrID): ?string;
}
