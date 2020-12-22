<?php
function lcr5bbdeba2dbb82sec($cx, $v, $bp, $in, $each, $cb, $else = null)
{
    $push = ($in !== $v) || $each;

    $isAry = is_array($v) || ($v instanceof \ArrayObject);
    $isTrav = $v instanceof \Traversable;
    $loop = $each;
    $keys = null;
    $last = null;
    $isObj = false;

    if ($isAry && $else !== null && count($v) === 0) {
        $ret = $else($cx, $in);
        return $ret;
    }

    // #var, detect input type is object or not
    if (!$loop && $isAry) {
        $keys = array_keys($v);
        $loop = (count(array_diff_key($v, array_keys($keys))) == 0);
        $isObj = !$loop;
    }

    if (($loop && $isAry) || $isTrav) {
        if ($each && !$isTrav) {
            // Detect input type is object or not when never done once
            if ($keys == null) {
                $keys = array_keys($v);
                $isObj = (count(array_diff_key($v, array_keys($keys))) > 0);
            }
        }
        $ret = array();
        if ($push) {
            $cx['scopes'][] = $in;
        }
        $i = 0;
        if ($cx['flags']['spvar']) {
            $old_spvar = $cx['sp_vars'];
            $cx['sp_vars'] = array_merge(array('root' => $old_spvar['root']), $old_spvar, array('_parent' => $old_spvar));
            if (!$isTrav) {
                $last = count($keys) - 1;
            }
        }

        $isSparceArray = $isObj && (count(array_filter(array_keys($v), 'is_string')) == 0);
        foreach ($v as $index => $raw) {
            if ($cx['flags']['spvar']) {
                $cx['sp_vars']['first'] = ($i === 0);
                $cx['sp_vars']['last'] = ($i == $last);
                $cx['sp_vars']['key'] = $index;
                $cx['sp_vars']['index'] = $isSparceArray ? $index : $i;
                $i++;
            }
            if (isset($bp[0])) {
                $raw = lcr5bbdeba2dbb82m($cx, $raw, array($bp[0] => $raw));
            }
            if (isset($bp[1])) {
                $raw = lcr5bbdeba2dbb82m($cx, $raw, array($bp[1] => $index));
            }
            $ret[] = $cb($cx, $raw);
        }
        if ($cx['flags']['spvar']) {
            if ($isObj) {
                unset($cx['sp_vars']['key']);
            } else {
                unset($cx['sp_vars']['last']);
            }
            unset($cx['sp_vars']['index']);
            unset($cx['sp_vars']['first']);
            $cx['sp_vars'] = $old_spvar;
        }
        if ($push) {
            array_pop($cx['scopes']);
        }
        return join('', $ret);
    }
    if ($each) {
        if ($else !== null) {
            $ret = $else($cx, $v);
            return $ret;
        }
        return '';
    }
    if ($isAry) {
        if ($push) {
            $cx['scopes'][] = $in;
        }
        $ret = $cb($cx, $v);
        if ($push) {
            array_pop($cx['scopes']);
        }
        return $ret;
    }

    if ($cx['flags']['mustsec']) {
        return $v ? $cb($cx, $in) : '';
    }

    if ($v === true) {
        return $cb($cx, $in);
    }

    if (($v !== null) && ($v !== false)) {
        return $cb($cx, $v);
    }

    if ($else !== null) {
        $ret = $else($cx, $in);
        return $ret;
    }

    return '';
}

function lcr5bbdeba2dbb82ifvar($cx, $v, $zero)
{
    return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
}

function lcr5bbdeba2dbb82encq($cx, $var)
{
    if ($var instanceof LS) {
        return (string)$var;
    }

    return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdeba2dbb82raw($cx, $var), ENT_QUOTES, 'UTF-8'));
}

function lcr5bbdeba2dbb82m($cx, $a, $b)
{
    if (is_array($b)) {
        if ($a === null) {
            return $b;
        } elseif (is_array($a)) {
            return array_merge($a, $b);
        } elseif ($cx['flags']['method'] || $cx['flags']['prop']) {
            if (!is_object($a)) {
                $a = new StringObject($a);
            }
            foreach ($b as $i => $v) {
                $a->$i = $v;
            }
        }
    }
    return $a;
}

function lcr5bbdeba2dbb82raw($cx, $v, $ex = 0)
{
    if ($ex) {
        return $v;
    }

    if ($v === true) {
        if ($cx['flags']['jstrue']) {
            return 'true';
        }
    }

    if (($v === false)) {
        if ($cx['flags']['jstrue']) {
            return 'false';
        }
    }

    if (is_array($v)) {
        if ($cx['flags']['jsobj']) {
            if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
                return '[object Object]';
            } else {
                $ret = array();
                foreach ($v as $k => $vv) {
                    $ret[] = lcr5bbdeba2dbb82raw($cx, $vv);
                }
                return join(',', $ret);
            }
        } else {
            return 'Array';
        }
    }

    return "$v";
}

if (!class_exists("LS")) {
    class LS
    {
        public static $jsContext = array(
            'flags' =>
            array(
                'jstrue' => 1,
                'jsobj' => 1,
            ),
        );
        public function __construct($str, $escape = false)
        {
            $this->string = $escape ? (($escape === 'encq') ? static::encq(static::$jsContext, $str) : static::enc(static::$jsContext, $str)) : $str;
        }
        public function tostring()
        {
            return $this->string;
        }
        public static function stripExtendedComments($template)
        {
            return preg_replace(static::EXTENDED_COMMENT_SEARCH, '{{! }}', $template);
        }
        public static function escapeTemplate($template)
        {
            return addcslashes(addcslashes($template, '\\'), "'");
        }
        public static function raw($cx, $v, $ex = 0)
        {
            if ($ex) {
                return $v;
            }

            if ($v === true) {
                if ($cx['flags']['jstrue']) {
                    return 'true';
                }
            }

            if (($v === false)) {
                if ($cx['flags']['jstrue']) {
                    return 'false';
                }
            }

            if (is_array($v)) {
                if ($cx['flags']['jsobj']) {
                    if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
                        return '[object Object]';
                    } else {
                        $ret = array();
                        foreach ($v as $k => $vv) {
                            $ret[] = static::raw($cx, $vv);
                        }
                        return join(',', $ret);
                    }
                } else {
                    return 'Array';
                }
            }

            return "$v";
        }
        public static function enc($cx, $var)
        {
            return htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8');
        }
        public static function encq($cx, $var)
        {
            return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8'));
        }
    }
}
return function ($in = null, $options = null) {
    $helpers = array();
    $partials = array();
    $cx = array(
        'flags' => array(
            'jstrue' => true,
            'jsobj' => true,
            'jslen' => true,
            'spvar' => true,
            'prop' => false,
            'method' => false,
            'lambda' => false,
            'mustlok' => false,
            'mustlam' => false,
            'mustsec' => false,
            'echo' => true,
            'partnc' => false,
            'knohlp' => false,
            'debug' => isset($options['debug']) ? $options['debug'] : 1,
        ),
        'constants' =>  array(
            'DEBUG_ERROR_LOG' => 1,
            'DEBUG_ERROR_EXCEPTION' => 2,
            'DEBUG_TAGS' => 4,
            'DEBUG_TAGS_ANSI' => 12,
            'DEBUG_TAGS_HTML' => 20,
        ),
        'helpers' => isset($options['helpers']) ? array_merge($helpers, $options['helpers']) : $helpers,
        'partials' => isset($options['partials']) ? array_merge($partials, $options['partials']) : $partials,
        'scopes' => array(),
        'sp_vars' => isset($options['data']) ? array_merge(array('root' => $in), $options['data']) : array('root' => $in),
        'blparam' => array(),
        'partialid' => 0,
        'runtime' => '\LightnCandy\Runtime',
    );
    
    $inary=is_array($in);
    ob_start();
    echo '',lcr5bbdeba2dbb82sec($cx, ((isset($in['o']) && is_array($in['o']) && isset($in['o']['files'])) ? $in['o']['files'] : null), null, $in, true, function ($cx, $in) {
        $inary=is_array($in);
        echo '    <div class="module-download fade">
';
        if (lcr5bbdeba2dbb82ifvar($cx, (($inary && isset($in['error'])) ? $in['error'] : null), false)) {
            echo '            <div class="error">
            	<span class="label label-danger">',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['error'])) ? $in['error'] : null)),'</span>
            </div>
';
        } else {
            echo '        	<div class="preview">
        		<div class="row">
					<div class="col-sm-4 col-md-3">
						<label>',lcr5bbdeba2dbb82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['avatar'] : null)),'</label>
						<a href="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['thumbUrl'])) ? $in['thumbUrl'] : null)),'" rel="',lcr5bbdeba2dbb82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['image-rel'])) ? $cx['scopes'][count($cx['scopes'])-1]['image-rel'] : null)),'" title="',lcr5bbdeba2dbb82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['avatar'] : null)),'">
							<img class="thumbnail img-responsive" src="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['thumbUrl'])) ? $in['thumbUrl'] : null)),'" width="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['thumbSize'])) ? $in['thumbSize'] : null)),'" height="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['thumbSize'])) ? $in['thumbSize'] : null)),'">
						</a>
					</div>
					<div class="col-sm-8 col-md-9">
						<label>',lcr5bbdeba2dbb82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['photo'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['photo'] : null)),'</label>
						<a href="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['photoUrl'])) ? $in['photoUrl'] : null)),'" rel="',lcr5bbdeba2dbb82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['image-rel'])) ? $cx['scopes'][count($cx['scopes'])-1]['image-rel'] : null)),'" title="',lcr5bbdeba2dbb82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['photo'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['photo'] : null)),'">
							<img class="img-responsive" src="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['photoUrl'])) ? $in['photoUrl'] : null)),'" width="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['photoWidth'])) ? $in['photoWidth'] : null)),'" height="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['photoHeight'])) ? $in['photoHeight'] : null)),'">
						</a>
					</div>
				</div>
			</div>
';
        }
        echo '		<div class="delete clearfix" style="padding-top: 5px;">
			<button class="btn btn-danger delete" data-type="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['deleteType'])) ? $in['deleteType'] : null)),'" data-url="',lcr5bbdeba2dbb82encq($cx, (($inary && isset($in['deleteUrl'])) ? $in['deleteUrl'] : null)),'">
				<span class="glyphicon glyphicon-trash"></span> ',lcr5bbdeba2dbb82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['destroy'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['destroy'] : null)),'
			</button>
		</div>
    </div>
';
    }),'';
    return ob_get_clean();
};
