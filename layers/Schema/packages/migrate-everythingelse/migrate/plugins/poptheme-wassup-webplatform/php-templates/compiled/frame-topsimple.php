<?php
function lcr5bbdec6bbea2bencq($cx, $var)
{
    if ($var instanceof LS) {
        return (string)$var;
    }

    return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdec6bbea2braw($cx, $var), ENT_QUOTES, 'UTF-8'));
}

function lcr5bbdec6bbea2bwi($cx, $v, $bp, $in, $cb, $else = null)
{
    if (isset($bp[0])) {
        $v = lcr5bbdec6bbea2bm($cx, $v, array($bp[0] => $v));
    }
    if (($v === false) || ($v === null) || (is_array($v) && (count($v) === 0))) {
        return $else ? $else($cx, $in) : '';
    }
    if ($v === $in) {
        $ret = $cb($cx, $v);
    } else {
        $cx['scopes'][] = $in;
        $ret = $cb($cx, $v);
        array_pop($cx['scopes']);
    }
    return $ret;
}

function lcr5bbdec6bbea2bhbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null)
{
    $options = array(
        'name' => $ch,
        'hash' => $vars[1],
        'contexts' => count($cx['scopes']) ? $cx['scopes'] : array(null),
        'fn.blockParams' => 0,
        '_this' => &$_this,
    );

    if ($cx['flags']['spvar']) {
        $options['data'] = $cx['sp_vars'];
    }

    if (isset($vars[2])) {
        $options['fn.blockParams'] = count($vars[2]);
    }

    // $invert the logic
    if ($inverted) {
        $tmp = $else;
        $else = $cb;
        $cb = $tmp;
    }

    $options['fn'] = function ($context = '_NO_INPUT_HERE_', $data = null) use ($cx, &$_this, $cb, $options, $vars) {
        if ($cx['flags']['echo']) {
            ob_start();
        }
        if (isset($data['data'])) {
            $old_spvar = $cx['sp_vars'];
            $cx['sp_vars'] = array_merge(array('root' => $old_spvar['root']), $data['data'], array('_parent' => $old_spvar));
        }
        $ex = false;
        if (isset($data['blockParams']) && isset($vars[2])) {
            $ex = array_combine($vars[2], array_slice($data['blockParams'], 0, count($vars[2])));
            array_unshift($cx['blparam'], $ex);
        } elseif (isset($cx['blparam'][0])) {
            $ex = $cx['blparam'][0];
        }
        if (($context === '_NO_INPUT_HERE_') || ($context === $_this)) {
            $ret = $cb($cx, is_array($ex) ? lcr5bbdec6bbea2bm($cx, $_this, $ex) : $_this);
        } else {
            $cx['scopes'][] = $_this;
            $ret = $cb($cx, is_array($ex) ? lcr5bbdec6bbea2bm($cx, $context, $ex) : $context);
            array_pop($cx['scopes']);
        }
        if (isset($data['data'])) {
            $cx['sp_vars'] = $old_spvar;
        }
        return $cx['flags']['echo'] ? ob_get_clean() : $ret;
    };

    if ($else) {
        $options['inverse'] = function ($context = '_NO_INPUT_HERE_') use ($cx, $_this, $else) {
            if ($cx['flags']['echo']) {
                ob_start();
            }
            if ($context === '_NO_INPUT_HERE_') {
                $ret = $else($cx, $_this);
            } else {
                $cx['scopes'][] = $_this;
                $ret = $else($cx, $context);
                array_pop($cx['scopes']);
            }
            return $cx['flags']['echo'] ? ob_get_clean() : $ret;
        };
    } else {
        $options['inverse'] = function () {
            return '';
        };
    }

        return lcr5bbdec6bbea2bexch($cx, $ch, $vars, $options);
}

function lcr5bbdec6bbea2bsec($cx, $v, $bp, $in, $each, $cb, $else = null)
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
                $raw = lcr5bbdec6bbea2bm($cx, $raw, array($bp[0] => $raw));
            }
            if (isset($bp[1])) {
                $raw = lcr5bbdec6bbea2bm($cx, $raw, array($bp[1] => $index));
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

function lcr5bbdec6bbea2braw($cx, $v, $ex = 0)
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
                    $ret[] = lcr5bbdec6bbea2braw($cx, $vv);
                }
                return join(',', $ret);
            }
        } else {
            return 'Array';
        }
    }

    return "$v";
}

function lcr5bbdec6bbea2bm($cx, $a, $b)
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

function lcr5bbdec6bbea2bexch($cx, $ch, $vars, &$options)
{
    $args = $vars[0];
    $args[] = $options;
    $e = null;
    $r = true;

    try {
        $r = call_user_func_array($cx['helpers'][$ch], $args);
    } catch (\Exception $E) {
        $e = "Runtime: call custom helper '$ch' error: " . $E->getMessage();
    }

    if ($e !== null) {
        lcr5bbdec6bbea2berr($cx, $e);
    }

    return $r;
}

function lcr5bbdec6bbea2berr($cx, $err)
{
    if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_LOG']) {
        error_log($err);
        return;
    }
    if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_EXCEPTION']) {
        throw new \Exception($err);
    }
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
    $helpers = array(            'generateId' => function ($options) {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->generateId($options);
    },
    );
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
    echo '<ul class="nav navbar-nav framesection-navbar-left" role="menu">
	<li class="activeside-hidden">
		<a class="logo pop-hidden-embed" href="',lcr5bbdec6bbea2bencq($cx, ((isset($in['links']) && is_array($in['links']) && isset($in['links']['home'])) ? $in['links']['home'] : null)),'" title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['home'])) ? $in['titles']['home'] : null)),'">
',lcr5bbdec6bbea2bwi($cx, (($inary && isset($in['logo-small'])) ? $in['logo-small'] : null), null, $in, function ($cx, $in) {
        $inary=is_array($in);
        echo '				<img src="',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['src'])) ? $in['src'] : null)),'" alt="',lcr5bbdec6bbea2bencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['home'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['home'] : null)),'">
';
    }),'		</a>
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'logo')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' class="logo pop-hidden-simple" href="',lcr5bbdec6bbea2bencq($cx, ((isset($in['links']) && is_array($in['links']) && isset($in['links']['home'])) ? $in['links']['home'] : null)),'" target="',lcr5bbdec6bbea2bencq($cx, ((isset($in['targets']) && is_array($in['targets']) && isset($in['targets']['home'])) ? $in['targets']['home'] : null)),'" title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['homenewtab'])) ? $in['titles']['homenewtab'] : null)),'" data-tooltip-placement="bottom">
',lcr5bbdec6bbea2bwi($cx, (($inary && isset($in['logo-small'])) ? $in['logo-small'] : null), null, $in, function ($cx, $in) {
        $inary=is_array($in);
        echo '				<img src="',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['src'])) ? $in['src'] : null)),'" alt="',lcr5bbdec6bbea2bencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['homenewtab'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['homenewtab'] : null)),'">
';
    }),'		</a>
	</li>
</ul>
<ul class="nav navbar-nav navbar-nav-togglenav pop-hidden-embed" role="menu">
	<li class="hidden-sm hidden-md hidden-lg">
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglenav-xs')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' href="#" class="toggle-side" title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglenavigation'])) ? $in['titles']['togglenavigation'] : null)),'" data-target="',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['offcanvas-sidenav-target'])) ? $in['offcanvas-sidenav-target'] : null)),'" data-toggle="offcanvas-toggle" data-mode="xs">
			<span class="glyphicon ',lcr5bbdec6bbea2bencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['togglenavigation'])) ? $in['icons']['togglenavigation'] : null)),'"></span>
		</a>
	</li>
	<li class="hidden-xs">
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglenav')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' href="#" class="toggle-side activenavigator-hidden" title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglenavigation'])) ? $in['titles']['togglenavigation'] : null)),'" data-target="',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['offcanvas-sidenav-target'])) ? $in['offcanvas-sidenav-target'] : null)),'" data-toggle="offcanvas-toggle" data-tooltip-placement="bottom" ',lcr5bbdec6bbea2bsec($cx, (($inary && isset($in['togglenav-params'])) ? $in['togglenav-params'] : null), null, $in, true, function ($cx, $in) {
        $inary=is_array($in);
        echo ' ',lcr5bbdec6bbea2bencq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr5bbdec6bbea2bencq($cx, $in),'"';
    }),'>
			<span class="glyphicon ',lcr5bbdec6bbea2bencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['togglenavigation'])) ? $in['icons']['togglenavigation'] : null)),'"></span>
		</a>
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglenavigator')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' href="#" class="toggle-side activenavigator-visible" title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglenavigation'])) ? $in['titles']['togglenavigation'] : null)),'" data-target="',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['offcanvas-navigator-target'])) ? $in['offcanvas-navigator-target'] : null)),'" data-toggle="offcanvas-toggle" data-tooltip-placement="bottom">
			<span class="glyphicon ',lcr5bbdec6bbea2bencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['togglenavigation'])) ? $in['icons']['togglenavigation'] : null)),'"></span>
		</a>
	</li>
</ul>
<ul class="nav navbar-nav framesection-navbar-title" role="menu">
	<li class="framesection-title">
		<h4 ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'title')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),'>',lcr5bbdec6bbea2braw($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['document'])) ? $in['titles']['document'] : null)),'</h4>
	</li>
</ul>
<ul class="nav navbar-nav navbar-right framesection-navbar-right" role="menu">
	<li class="hidden-sm hidden-md hidden-lg">
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglepagetabs-xs')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' href="#" class="toggle-side" title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglepagetabs'])) ? $in['titles']['togglepagetabs'] : null)),'" data-target="',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['offcanvas-pagetabs-target'])) ? $in['offcanvas-pagetabs-target'] : null)),'" data-toggle="offcanvas-toggle" data-mode="xs">
			<span class="glyphicon glyphicon-time"></span>
		</a>
	</li>
	<li class="hidden-xs">
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglepagetabs')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' href="#" class="toggle-side" title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglepagetabs'])) ? $in['titles']['togglepagetabs'] : null)),'" data-target="',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['offcanvas-pagetabs-target'])) ? $in['offcanvas-pagetabs-target'] : null)),'" data-toggle="offcanvas-toggle" data-tooltip-placement="bottom">
			<span class="glyphicon glyphicon-time"></span>
		</a>
	</li>
	<li class="pop-hidden-simple hidden-sm hidden-md hidden-lg">
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'fullscreen-xs')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['fullscreen'])) ? $in['titles']['fullscreen'] : null)),'" href="#" data-toggle="button" class="pop-fullscreen-btn">
			<span class="glyphicon glyphicon-fullscreen"></span>
		</a>
	</li>
	<li class="pop-hidden-simple hidden-xs">
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'fullscreen')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['fullscreen'])) ? $in['titles']['fullscreen'] : null)),'" href="#" data-toggle="button" class="pop-fullscreen-btn" data-tooltip-placement="bottom">
			<span class="glyphicon glyphicon-fullscreen"></span>
		</a>
	</li>
	<li class="pop-hidden-simple hidden-sm hidden-md hidden-lg">
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'new-window-xs')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['newwindow'])) ? $in['titles']['newwindow'] : null)),'" href="#" data-toggle="button" class="module-newwindow-btn">
			<span class="glyphicon glyphicon-new-window"></span>
		</a>
	</li>
	<li class="pop-hidden-simple hidden-xs">
		<a ',lcr5bbdec6bbea2bhbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'new-window')), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdec6bbea2bencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' title="',lcr5bbdec6bbea2bencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['newwindow'])) ? $in['titles']['newwindow'] : null)),'" href="#" data-toggle="button" class="module-newwindow-btn" data-tooltip-placement="bottom">
			<span class="glyphicon glyphicon-new-window"></span>
		</a>
	</li>
</ul>';
    return ob_get_clean();
};
