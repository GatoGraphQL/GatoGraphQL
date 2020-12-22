<?php
function lcr5be5a1e045f82ifvar($cx, $v, $zero)
{
    return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
}

function lcr5be5a1e045f82encq($cx, $var)
{
    if ($var instanceof LS) {
        return (string)$var;
    }

    return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5be5a1e045f82raw($cx, $var), ENT_QUOTES, 'UTF-8'));
}

function lcr5be5a1e045f82hbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null)
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
            $ret = $cb($cx, is_array($ex) ? lcr5be5a1e045f82m($cx, $_this, $ex) : $_this);
        } else {
            $cx['scopes'][] = $_this;
            $ret = $cb($cx, is_array($ex) ? lcr5be5a1e045f82m($cx, $context, $ex) : $context);
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

        return lcr5be5a1e045f82exch($cx, $ch, $vars, $options);
}

function lcr5be5a1e045f82sec($cx, $v, $bp, $in, $each, $cb, $else = null)
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
                $raw = lcr5be5a1e045f82m($cx, $raw, array($bp[0] => $raw));
            }
            if (isset($bp[1])) {
                $raw = lcr5be5a1e045f82m($cx, $raw, array($bp[1] => $index));
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

function lcr5be5a1e045f82hbch($cx, $ch, $vars, $op, &$_this)
{
    if (isset($cx['blparam'][0][$ch])) {
        return $cx['blparam'][0][$ch];
    }

    $options = array(
        'name' => $ch,
        'hash' => $vars[1],
        'contexts' => count($cx['scopes']) ? $cx['scopes'] : array(null),
        'fn.blockParams' => 0,
        '_this' => &$_this
    );

    if ($cx['flags']['spvar']) {
        $options['data'] = $cx['sp_vars'];
    }

    return lcr5be5a1e045f82exch($cx, $ch, $vars, $options);
}

function lcr5be5a1e045f82raw($cx, $v, $ex = 0)
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
                    $ret[] = lcr5be5a1e045f82raw($cx, $vv);
                }
                return join(',', $ret);
            }
        } else {
            return 'Array';
        }
    }

    return "$v";
}

function lcr5be5a1e045f82m($cx, $a, $b)
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

function lcr5be5a1e045f82exch($cx, $ch, $vars, &$options)
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
        lcr5be5a1e045f82err($cx, $e);
    }

    return $r;
}

function lcr5be5a1e045f82err($cx, $err)
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
        'enterTemplate' => function ($template, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->enterTemplate($template, $options);
        },
        'enterModule' => function ($prevContext, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->enterModule($prevContext, $options);
        },
        'withModule' => function ($context, $moduleName, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->withModule($context, $moduleName, $options);
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
    echo '';
    if (!lcr5be5a1e045f82ifvar($cx, ((isset($in['feedback']) && is_array($in['feedback']) && isset($in['feedback']['do-not-render'])) ? $in['feedback']['do-not-render'] : null), false)) {
        echo '	<div class="',lcr5be5a1e045f82encq($cx, (($inary && isset($in['class'])) ? $in['class'] : null)),' ',lcr5be5a1e045f82encq($cx, (($inary && isset($in['runtime-class'])) ? $in['runtime-class'] : null)),' ',lcr5be5a1e045f82encq($cx, ((isset($in['feedback']) && is_array($in['feedback']) && isset($in['feedback']['class'])) ? $in['feedback']['class'] : null)),'" style="',lcr5be5a1e045f82encq($cx, (($inary && isset($in['style'])) ? $in['style'] : null)),'',lcr5be5a1e045f82encq($cx, (($inary && isset($in['runtime-style'])) ? $in['runtime-style'] : null)),'" ',lcr5be5a1e045f82hbbch($cx, 'generateId', array(array(),array('addURL'=>'true')), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '',lcr5be5a1e045f82encq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
        }),' data-settings-id="',lcr5be5a1e045f82encq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" ',lcr5be5a1e045f82sec($cx, (($inary && isset($in['params'])) ? $in['params'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo ' ',lcr5be5a1e045f82encq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr5be5a1e045f82encq($cx, $in),'"';
        }),'>

',lcr5be5a1e045f82hbbch($cx, 'withModule', array(array($in,'controlgroup-top'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '			<div class="blocksection-controls ',lcr5be5a1e045f82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['controlgroup-top'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['controlgroup-top'] : null)),'" style="',lcr5be5a1e045f82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['controlgroup-top'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['controlgroup-top'] : null)),'">
				',lcr5be5a1e045f82encq($cx, lcr5be5a1e045f82hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-1]),array()), 'encq', $in)),'
			</div>
';
        }),'
		',lcr5be5a1e045f82raw($cx, (($inary && isset($in['description-abovetitle'])) ? $in['description-abovetitle'] : null)),'
				
';
        if (lcr5be5a1e045f82ifvar($cx, (($inary && isset($in['title'])) ? $in['title'] : null), false)) {
            echo '			<div class="blocksection-title ',lcr5be5a1e045f82encq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['blocksection-title'])) ? $in['classes']['blocksection-title'] : null)),'" style="',lcr5be5a1e045f82encq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['blocksection-title'])) ? $in['styles']['blocksection-title'] : null)),'">
				<',lcr5be5a1e045f82encq($cx, (($inary && isset($in['title-htmltag'])) ? $in['title-htmltag'] : null)),' class="title ',lcr5be5a1e045f82encq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['block-title'])) ? $in['classes']['block-title'] : null)),'" style="',lcr5be5a1e045f82encq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['block-title'])) ? $in['styles']['block-title'] : null)),'">
';
            if (lcr5be5a1e045f82ifvar($cx, (($inary && isset($in['title-link'])) ? $in['title-link'] : null), false)) {
                echo '						<a href="',lcr5be5a1e045f82encq($cx, (($inary && isset($in['title-link'])) ? $in['title-link'] : null)),'">',lcr5be5a1e045f82raw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'</a>
';
            } else {
                echo '						',lcr5be5a1e045f82raw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'
';
            }
            echo '				</',lcr5be5a1e045f82encq($cx, (($inary && isset($in['title-htmltag'])) ? $in['title-htmltag'] : null)),'>
			</div>
';
        } else {
            echo '';
        }
        echo '
		';
        if (lcr5be5a1e045f82ifvar($cx, (($inary && isset($in['add-clearfixdiv'])) ? $in['add-clearfixdiv'] : null), false)) {
            echo '<div class="clearfix"></div>';
        } else {
            echo '';
        }
        echo '
		
		',lcr5be5a1e045f82raw($cx, (($inary && isset($in['description-top'])) ? $in['description-top'] : null)),'

',lcr5be5a1e045f82hbbch($cx, 'withModule', array(array($in,'controlgroup-bottom'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '			<div class="blocksection-controls ',lcr5be5a1e045f82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['controlgroup-bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['controlgroup-bottom'] : null)),'" style="',lcr5be5a1e045f82encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['controlgroup-bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['controlgroup-bottom'] : null)),'">
				',lcr5be5a1e045f82encq($cx, lcr5be5a1e045f82hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-1]),array()), 'encq', $in)),'
			</div>
';
        }),'
',lcr5be5a1e045f82hbbch($cx, 'withModule', array(array($in,'submenu'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '			<div class="blocksection-submenu">
				',lcr5be5a1e045f82encq($cx, lcr5be5a1e045f82hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-1]),array()), 'encq', $in)),'
			</div>
';
        }),'
',lcr5be5a1e045f82hbbch($cx, 'withModule', array(array($in,'latestcount'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '			<div class="blocksection-latestcount">
				',lcr5be5a1e045f82encq($cx, lcr5be5a1e045f82hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-1]),array()), 'encq', $in)),'
			</div>
';
        }),'
		',lcr5be5a1e045f82raw($cx, (($inary && isset($in['description'])) ? $in['description'] : null)),'

';
        if (lcr5be5a1e045f82ifvar($cx, ((isset($in['settings-ids']) && is_array($in['settings-ids']) && isset($in['settings-ids']['block-inners'])) ? $in['settings-ids']['block-inners'] : null), false)) {
            echo '			<div class="blocksection-inners clearfix ',lcr5be5a1e045f82encq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['blocksection-inners'])) ? $in['classes']['blocksection-inners'] : null)),'" style="',lcr5be5a1e045f82encq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['blocksection-inners'])) ? $in['styles']['blocksection-inners'] : null)),'">
';
            if (lcr5be5a1e045f82ifvar($cx, (($inary && isset($in['show-disabled-layer'])) ? $in['show-disabled-layer'] : null), false)) {
                echo '					<div class="pop-disabledlayer ';
                if (!lcr5be5a1e045f82ifvar($cx, ((isset($in['feedback']) && is_array($in['feedback']) && isset($in['feedback']['checkpoint-failed'])) ? $in['feedback']['checkpoint-failed'] : null), false)) {
                    echo 'hidden';
                } else {
                    echo '';
                }
                echo '">
						<div class="layer"></div>
					</div>
';
            } else {
                echo '';
            }
            echo '',lcr5be5a1e045f82sec($cx, ((isset($in['settings-ids']) && is_array($in['settings-ids']) && isset($in['settings-ids']['block-inners'])) ? $in['settings-ids']['block-inners'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '',lcr5be5a1e045f82hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],$in),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '						',lcr5be5a1e045f82encq($cx, lcr5be5a1e045f82hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
';
                }),'';
            }),'			</div>
';
        } else {
            echo '';
        }
        echo '
		';
        if (lcr5be5a1e045f82ifvar($cx, (($inary && isset($in['add-clearfixdiv'])) ? $in['add-clearfixdiv'] : null), false)) {
            echo '<div class="clearfix"></div>';
        } else {
            echo '';
        }
        echo '

';
        if (lcr5be5a1e045f82ifvar($cx, ((isset($in['templates']) && is_array($in['templates']) && isset($in['templates']['block-extensions'])) ? $in['templates']['block-extensions'] : null), false)) {
            echo '			<div class="blocksection-extensions clearfix ',lcr5be5a1e045f82encq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['blocksection-extensions'])) ? $in['classes']['blocksection-extensions'] : null)),'" style="',lcr5be5a1e045f82encq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['blocksection-extensions'])) ? $in['styles']['blocksection-extensions'] : null)),'">
',lcr5be5a1e045f82sec($cx, ((isset($in['templates']) && is_array($in['templates']) && isset($in['templates']['block-extensions'])) ? $in['templates']['block-extensions'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '					',lcr5be5a1e045f82encq($cx, lcr5be5a1e045f82hbch($cx, 'enterTemplate', array(array($in),array('context'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),'
';
            }),'			</div>
';
        } else {
            echo '';
        }
        echo '
		',lcr5be5a1e045f82raw($cx, (($inary && isset($in['description-bottom'])) ? $in['description-bottom'] : null)),'
	</div>
';
    } else {
        echo '';
    }
    echo '';
    return ob_get_clean();
};
