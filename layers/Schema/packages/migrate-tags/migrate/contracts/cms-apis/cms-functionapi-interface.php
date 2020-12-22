<?php
namespace PoPSchema\Tags;

interface FunctionAPI extends \PoPSchema\Taxonomies\FunctionAPI
{
    public function getCustomPostTags($post_id, array $query = [], array $options = []): array;
    public function getCustomPostTagCount($post_id, array $query = [], array $options = []): int;
    public function getTag($tag_id);
    public function getTagByName($tag_name);
    public function getTags($query, array $options = []): array;
    public function getTagCount(array $query = [], array $options = []): int;
    public function getTagLink($tag_id);
    public function getTagName($tag_id);
    public function getTagBase();
    public function setPostTags($post_id, array $tags, bool $append = false);
}
