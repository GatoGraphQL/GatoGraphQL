<?php
function lcr5bbdeb9fec45fwi($cx, $v, $bp, $in, $cb, $else = null)
{
    if (isset($bp[0])) {
        $v = lcr5bbdeb9fec45fm($cx, $v, array($bp[0] => $v));
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

function lcr5bbdeb9fec45fencq($cx, $var)
{
    if ($var instanceof LS) {
        return (string)$var;
    }

    return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdeb9fec45fraw($cx, $var), ENT_QUOTES, 'UTF-8'));
}

function lcr5bbdeb9fec45fhbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null)
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
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb9fec45fm($cx, $_this, $ex) : $_this);
        } else {
            $cx['scopes'][] = $_this;
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb9fec45fm($cx, $context, $ex) : $context);
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

        return lcr5bbdeb9fec45fexch($cx, $ch, $vars, $options);
}

function lcr5bbdeb9fec45fhbch($cx, $ch, $vars, $op, &$_this)
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

    return lcr5bbdeb9fec45fexch($cx, $ch, $vars, $options);
}

function lcr5bbdeb9fec45fifvar($cx, $v, $zero)
{
    return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
}

function lcr5bbdeb9fec45fraw($cx, $v, $ex = 0)
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
                    $ret[] = lcr5bbdeb9fec45fraw($cx, $vv);
                }
                return join(',', $ret);
            }
        } else {
            return 'Array';
        }
    }

    return "$v";
}

function lcr5bbdeb9fec45fsec($cx, $v, $bp, $in, $each, $cb, $else = null)
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
                $raw = lcr5bbdeb9fec45fm($cx, $raw, array($bp[0] => $raw));
            }
            if (isset($bp[1])) {
                $raw = lcr5bbdeb9fec45fm($cx, $raw, array($bp[1] => $index));
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

function lcr5bbdeb9fec45fm($cx, $a, $b)
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

function lcr5bbdeb9fec45fexch($cx, $ch, $vars, &$options)
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
        lcr5bbdeb9fec45ferr($cx, $e);
    }

    return $r;
}

function lcr5bbdeb9fec45ferr($cx, $err)
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
    echo '',lcr5bbdeb9fec45fwi($cx, (($inary && isset($in['dbObject'])) ? $in['dbObject'] : null), null, $in, function ($cx, $in) {
        $inary=is_array($in);
        echo '	<div class="layout notification-layout preview ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['class'])) ? $cx['scopes'][count($cx['scopes'])-1]['class'] : null)),' ',lcr5bbdeb9fec45fencq($cx, (($inary && isset($in['status'])) ? $in['status'] : null)),' target-',lcr5bbdeb9fec45fencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['tls']) && isset($cx['scopes'][count($cx['scopes'])-1]['tls']['domain-id'])) ? $cx['scopes'][count($cx['scopes'])-1]['tls']['domain-id'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['style'])) ? $cx['scopes'][count($cx['scopes'])-1]['style'] : null)),'" ',lcr5bbdeb9fec45fhbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1])), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'';
        }),'>
',lcr5bbdeb9fec45fhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'link'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '			',lcr5bbdeb9fec45fencq($cx, lcr5bbdeb9fec45fhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
';
        }),'',lcr5bbdeb9fec45fhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'quicklinkgroup-top'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '			<div class="quicklinkgroup quicklinkgroup-top ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-top'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-top'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-top'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-top'] : null)),'">
				',lcr5bbdeb9fec45fencq($cx, lcr5bbdeb9fec45fhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
			</div>
';
        }),'		<div class="wrapper ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['wrapper'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['wrapper'] : null)),'">
			<div class="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['thumb-wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['thumb-wrapper'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['thumb-wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['thumb-wrapper'] : null)),'">
',lcr5bbdeb9fec45fhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'postthumb'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '					<div class="post-thumb ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['thumb'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['thumb'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['thumb'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['thumb'] : null)),'">
						',lcr5bbdeb9fec45fencq($cx, lcr5bbdeb9fec45fhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
					</div>
';
        }),'',lcr5bbdeb9fec45fhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'user-avatar'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '					<div class="avatar ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['avatar'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['avatar'] : null)),'">					
						',lcr5bbdeb9fec45fencq($cx, lcr5bbdeb9fec45fhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array('dbKey'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['bs']['dbkeys']) && isset($cx['scopes'][count($cx['scopes'])-2]['bs']['dbkeys']['user-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['bs']['dbkeys']['user-id'] : null),'dbObjectID'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['user-id'])) ? $cx['scopes'][count($cx['scopes'])-1]['user-id'] : null))), 'encq', $in)),'
					</div>
';
        }),'			</div>
			<div class="content-body ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['content-body'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['content-body'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['content-body'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['content-body'] : null)),'">
				<div class="message ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['content'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['content'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['content'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['content'] : null)),'">
					';
        if (lcr5bbdeb9fec45fifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['add-url-link'])) ? $cx['scopes'][count($cx['scopes'])-1]['add-url-link'] : null), false)) {
            echo '<a href="',lcr5bbdeb9fec45fencq($cx, (($inary && isset($in['url'])) ? $in['url'] : null)),'" ';
            if (lcr5bbdeb9fec45fifvar($cx, (($inary && isset($in['target'])) ? $in['target'] : null), false)) {
                echo 'target="',lcr5bbdeb9fec45fencq($cx, (($inary && isset($in['target'])) ? $in['target'] : null)),'"';
            } else {
                echo '';
            }
            echo '>';
        } else {
            echo '';
        }
        echo '
						',lcr5bbdeb9fec45fraw($cx, (($inary && isset($in['message'])) ? $in['message'] : null)),'
					';
        if (lcr5bbdeb9fec45fifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['add-url-link'])) ? $cx['scopes'][count($cx['scopes'])-1]['add-url-link'] : null), false)) {
            echo '</a>';
        } else {
            echo '';
        }
        echo '
				</div>
',lcr5bbdeb9fec45fhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'quicklinkgroup-bottom'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '					<div class="quicklinkgroup quicklinkgroup-bottom ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-bottom'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-bottom'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-bottom'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-bottom'] : null)),'">
						',lcr5bbdeb9fec45fencq($cx, lcr5bbdeb9fec45fhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
					</div>
';
        }),'';
        if (lcr5bbdeb9fec45fifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['bottom'] : null), false)) {
            echo '					<div class="extra ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['bottom'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['bottom'] : null)),'">
',lcr5bbdeb9fec45fsec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['bottom'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '							<div class="extra-inner ',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['bottom-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['bottom-inner'] : null)),'" style="',lcr5bbdeb9fec45fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['bottom-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['bottom-inner'] : null)),'">
',lcr5bbdeb9fec45fhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '									',lcr5bbdeb9fec45fencq($cx, lcr5bbdeb9fec45fhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';
                }),'							</div>
';
            }),'					</div>
';
        } else {
            echo '';
        }
        echo '			</div>
		</div>
	</div>
';
    }),'';
    return ob_get_clean();
};
