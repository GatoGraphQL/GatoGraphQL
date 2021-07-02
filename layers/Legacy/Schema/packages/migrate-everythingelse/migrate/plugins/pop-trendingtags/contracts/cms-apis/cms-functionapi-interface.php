<?php
namespace PoP\TrendingTags;

interface FunctionAPI
{
    public function getTrendingHashtagIds($days, $number, $offset);
}
