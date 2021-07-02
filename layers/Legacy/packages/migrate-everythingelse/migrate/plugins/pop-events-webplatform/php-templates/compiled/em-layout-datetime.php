<?php
function lcr5bbdeb51cc2f6wi($cx, $v, $bp, $in, $cb, $else = null)
{
    if (isset($bp[0])) {
        $v = lcr5bbdeb51cc2f6m($cx, $v, array($bp[0] => $v));
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

function lcr5bbdeb51cc2f6encq($cx, $var)
{
    if ($var instanceof LS) {
        return (string)$var;
    }

    return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdeb51cc2f6raw($cx, $var), ENT_QUOTES, 'UTF-8'));
}

function lcr5bbdeb51cc2f6hbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null)
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
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb51cc2f6m($cx, $_this, $ex) : $_this);
        } else {
            $cx['scopes'][] = $_this;
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb51cc2f6m($cx, $context, $ex) : $context);
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

    return lcr5bbdeb51cc2f6exch($cx, $ch, $vars, $options);
}

function lcr5bbdeb51cc2f6hbch($cx, $ch, $vars, $op, &$_this)
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

    return lcr5bbdeb51cc2f6exch($cx, $ch, $vars, $options);
}

function lcr5bbdeb51cc2f6raw($cx, $v, $ex = 0)
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
                    $ret[] = lcr5bbdeb51cc2f6raw($cx, $vv);
                }
                return join(',', $ret);
            }
        } else {
            return 'Array';
        }
    }

    return "$v";
}

function lcr5bbdeb51cc2f6m($cx, $a, $b)
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

function lcr5bbdeb51cc2f6exch($cx, $ch, $vars, &$options)
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
        lcr5bbdeb51cc2f6err($cx, $e);
    }

    return $r;
}

function lcr5bbdeb51cc2f6err($cx, $err)
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
    $helpers = array(            'enterModule' => function ($prevContext, $options) {
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
    echo '',lcr5bbdeb51cc2f6wi(
        $cx,
        (($inary && isset($in['dbObject'])) ? $in['dbObject'] : null),
        null,
        $in,
        function ($cx, $in) {
            $inary=is_array($in);
            echo '	<div class="datetime ',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['class'])) ? $cx['scopes'][count($cx['scopes'])-1]['class'] : null)),'" style="',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['style'])) ? $cx['scopes'][count($cx['scopes'])-1]['style'] : null)),'">
',lcr5bbdeb51cc2f6hbbch(
                $cx,
                'withModule',
                array(array($cx['scopes'][count($cx['scopes'])-1],'layout-downloadlinks'),array()),
                $in,
                false,
                function ($cx, $in) {
                    $inary=is_array($in);
                    echo '			<div class="downloadlinks ',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['downloadlinks'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['downloadlinks'] : null)),'" style="',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['downloadlinks'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['downloadlinks'] : null)),'">
				',lcr5bbdeb51cc2f6encq($cx, lcr5bbdeb51cc2f6hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
			</div>
';
                }
            ),'		<div class="',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['calendar'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['calendar'] : null)),'" style="',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['calendar'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['calendar'] : null)),'">
			<span class="',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['date'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['date'] : null)),'" style="',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['date'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['date'] : null)),'"><i class="fa fa-fw fa-calendar"></i>',lcr5bbdeb51cc2f6encq($cx, (($inary && isset($in['dates'])) ? $in['dates'] : null)),'</span>',lcr5bbdeb51cc2f6raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['separator'])) ? $cx['scopes'][count($cx['scopes'])-1]['separator'] : null)),'
			<span class="',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['time'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['time'] : null)),'" style="',lcr5bbdeb51cc2f6encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['time'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['time'] : null)),'"><i class="fa fa-fw fa-clock-o"></i>',lcr5bbdeb51cc2f6encq($cx, (($inary && isset($in['times'])) ? $in['times'] : null)),'</span>
		</div>
	</div>
';
        }
    ),'';
    return ob_get_clean();
};
