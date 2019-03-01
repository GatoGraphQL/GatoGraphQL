<?php
namespace PoP\Engine;

abstract class FilterComponent_HashtagsBase extends FilterComponentBase
{
    public function getTags($filter)
    {
        $tags = array();

        // The tags might have the '#' symbol, if so remove it
        if ($value = $this->getFilterinputValue($filter)) {
            // tags provided separated by space, color or comma
            foreach (multiexplode(array(',', ';', ' '), $value) as $hashtag) {
                if ($hashtag) {
                    $tags[] = (substr($hashtag, 0, 1) == '#') ? substr($hashtag, 1) : $hashtag;
                }
            }
        }

        return $tags;
    }
}
