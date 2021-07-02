<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_ShowMoreHelpers
{
    public function showmore($str, $options)
    {

        // len == 0 => No need for showmore
        $len = $options['hash']['len'] ?? 0;

        // Only if at least 100 chars more, so that it doesn't shorten just a tiny bit of text
        if ($len > 0 && strlen($str) > $len + 100) {
            // If we find "</p>", then we must also hide the bit until that </p>
            $delim = "</p>";
            $total_len = $len;
            $morelink = '<a href="#" class="pop-showmore-more">'.GD_STRING_MORE.'</a>';
            $lesslink = '<a href="#" class="pop-showmore-less hidden">'.GD_STRING_LESS.'</a>';
            $moreless = false;
            $add_morelink = true;
            if ((strlen($str) > $total_len) && (strpos(substr($str, $len), $delim) > -1)) {
                // Add the moreless links at the end, if only to show the hidden text inside the <p></p>
                $moreless = true;
                $add_morelink = false;
                
                // Wrap excess characters inside the <p></p> inside a hidden span
                // Add the morelink inside the <p></p> so it shows inline
                $str =
                 substr($str, 0, $len).
                 '<span class="pop-showmore-more-full hidden">'.
                 substr($str, $len, strpos(substr($str, $len), $delim)).
                 '</span> '.
                 $morelink.
                 substr($str, $len+strpos(substr($str, $len), $delim));

                $total_len = $len + strpos(substr($str, $len), $delim) + strlen($delim);
            }

            if ($moreless || (strlen($str) > $total_len)) {
                // Make sure there still some string left after the operation. If not, then nothing to hide
                $str_end = substr($str, $total_len);
                $has_endstr = strlen(trim($str_end));
                if ($moreless || $has_endstr) {
                    $str_beg = substr($str, 0, $total_len);
                    $str_new =
                    '<span class="pop-showmore-more-teaser">'.$str_beg.'</span>'.
                    ($has_endstr ? '<span class="pop-showmore-more-full hidden">'.$str_end.'</span> ' : ' ').
                    ($add_morelink ? $morelink : '').
                    $lesslink;
                    return new LS($str_new);
                }
            }
        }
        
        return $str;
    }
}

/**
 * Initialization
 */
global $pop_serverside_showmorehelpers;
$pop_serverside_showmorehelpers = new PoP_ServerSide_ShowMoreHelpers();
