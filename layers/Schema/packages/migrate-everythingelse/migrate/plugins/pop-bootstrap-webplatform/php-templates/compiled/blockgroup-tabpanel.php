<?php
function lcr5bbdeb48cfd7fhbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null)
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
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb48cfd7fm($cx, $_this, $ex) : $_this);
        } else {
            $cx['scopes'][] = $_this;
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb48cfd7fm($cx, $context, $ex) : $context);
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

        return lcr5bbdeb48cfd7fexch($cx, $ch, $vars, $options);
}

function lcr5bbdeb48cfd7fencq($cx, $var)
{
    if ($var instanceof LS) {
        return (string)$var;
    }

    return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdeb48cfd7fraw($cx, $var), ENT_QUOTES, 'UTF-8'));
}

function lcr5bbdeb48cfd7fhbch($cx, $ch, $vars, $op, &$_this)
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

    return lcr5bbdeb48cfd7fexch($cx, $ch, $vars, $options);
}

function lcr5bbdeb48cfd7fsec($cx, $v, $bp, $in, $each, $cb, $else = null)
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
                $raw = lcr5bbdeb48cfd7fm($cx, $raw, array($bp[0] => $raw));
            }
            if (isset($bp[1])) {
                $raw = lcr5bbdeb48cfd7fm($cx, $raw, array($bp[1] => $index));
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

function lcr5bbdeb48cfd7fifvar($cx, $v, $zero)
{
    return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
}

function lcr5bbdeb48cfd7fwi($cx, $v, $bp, $in, $cb, $else = null)
{
    if (isset($bp[0])) {
        $v = lcr5bbdeb48cfd7fm($cx, $v, array($bp[0] => $v));
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

function lcr5bbdeb48cfd7fraw($cx, $v, $ex = 0)
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
                    $ret[] = lcr5bbdeb48cfd7fraw($cx, $vv);
                }
                return join(',', $ret);
            }
        } else {
            return 'Array';
        }
    }

    return "$v";
}

function lcr5bbdeb48cfd7fm($cx, $a, $b)
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

function lcr5bbdeb48cfd7fexch($cx, $ch, $vars, &$options)
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
        lcr5bbdeb48cfd7ferr($cx, $e);
    }

    return $r;
}

function lcr5bbdeb48cfd7ferr($cx, $err)
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
    $helpers = array(            'interceptAttr' => function ($options) {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->interceptAttr($options);
    },
        'generateId' => function ($options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->generateId($options);
        },
        'lastGeneratedId' => function ($options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->lastGeneratedId($options);
        },
        'enterModule' => function ($prevContext, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->enterModule($prevContext, $options);
        },
        'withModule' => function ($context, $moduleName, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->withModule($context, $moduleName, $options);
        },
        'withSublevel' => function ($sublevel, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->withSublevel($sublevel, $options);
        },
        'get' => function ($db, $index, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->get($db, $index, $options);
        },
        'withget' => function ($db, $index, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->withget($db, $index, $options);
        },
        'compare' => function ($lvalue, $rvalue, $options) {
            global $pop_serverside_comparehelpers;
            return $pop_serverside_comparehelpers->compare($lvalue, $rvalue, $options);
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
    echo '<div ',lcr5bbdeb48cfd7fhbbch($cx, 'generateId', array(array(),array('group'=>(($inary && isset($in['bootstrap-type'])) ? $in['bootstrap-type'] : null))), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' role="tabpanel">
',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array((($inary && isset($in['panel-header-type'])) ? $in['panel-header-type'] : null),'tab'),array()), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '		<ul id="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('group'=>(($inary && isset($in['bootstrap-type'])) ? $in['bootstrap-type'] : null))), 'encq', $in)),'-panel-title" class="nav nav-tabs ',lcr5bbdeb48cfd7fencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['panelheader'])) ? $in['classes']['panelheader'] : null)),'" style="',lcr5bbdeb48cfd7fencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['panelheader'])) ? $in['styles']['panelheader'] : null)),'" role="tablist" ',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['panelheader-params'])) ? $in['panelheader-params'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo ' ',lcr5bbdeb48cfd7fencq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr5bbdeb48cfd7fencq($cx, $in),'"';
        }),'>
',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['panel-headers'])) ? $in['panel-headers'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo '				<li role="presentation" class="',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['panelheader-item'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['panelheader-item'] : null)),' ',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array(((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['active'])) ? $cx['scopes'][count($cx['scopes'])-1]['active'] : null),(($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),array()), $in, false, function ($cx, $in) {
                $inary=is_array($in);
                echo 'active';
            }, function ($cx, $in) {
                $inary=is_array($in);
                echo '',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), null, $in, true, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['active'])) ? $cx['scopes'][count($cx['scopes'])-2]['active'] : null),(($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),array()), $in, false, function ($cx, $in) {
                        $inary=is_array($in);
                        echo 'active';
                    }),'';
                }),'';
            }),' ';
            if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), false)) {
                echo 'dropdown';
            } else {
                echo '';
            }
            echo '" style="',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['panelheader-item'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['panelheader-item'] : null)),'" ';
            if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['tooltip'])) ? $in['tooltip'] : null), false)) {
                echo ' ',lcr5bbdeb48cfd7fhbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>'tooltip')), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'';
                }),' title="',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['tooltip'])) ? $in['tooltip'] : null)),'"';
            } else {
                echo '';
            }
            echo '>
';
            if (lcr5bbdeb48cfd7fifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['intercept'])) ? $cx['scopes'][count($cx['scopes'])-1]['intercept'] : null), false)) {
                echo '						<a ',lcr5bbdeb48cfd7fhbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>'tablink')), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'';
                }),' href="',lcr5bbdeb48cfd7fwi($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-1]['intercept-urls'] : null), null, $in, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fhbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['bs']) && isset($cx['scopes'][count($cx['scopes'])-2]['bs']['bsId'])) ? $cx['scopes'][count($cx['scopes'])-2]['bs']['bsId'] : null)),array()), $in, false, function ($cx, $in) {
                        $inary=is_array($in);
                        echo '',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['settings-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['settings-id'] : null)),array()), 'encq', $in)),'';
                    }),'';
                }),'">
';
            } else {
                echo '						<a href="#',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" aria-controls="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" role="tab" data-toggle="tab">
';
            }
            echo '						';
            if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null), false)) {
                echo '<i class="fa fa-fw ',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null)),'"></i>';
            } else {
                echo '';
            }
            echo '<span class="tab-title">',lcr5bbdeb48cfd7fraw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'</span>';
            if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), false)) {
                echo ' <span class="caret"></span>';
            } else {
                echo '';
            }
            echo '
					</a>
';
            if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), false)) {
                echo '						<ul class="dropdown-menu pull-right" role="menu">
',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), null, $in, true, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '								<li role="presentation" class="',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['active'])) ? $cx['scopes'][count($cx['scopes'])-2]['active'] : null),(($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),array()), $in, false, function ($cx, $in) {
                        $inary=is_array($in);
                        echo 'active';
                    }),'">
';
                    if (lcr5bbdeb48cfd7fifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['intercept'])) ? $cx['scopes'][count($cx['scopes'])-2]['intercept'] : null), false)) {
                        echo '										<a href="',lcr5bbdeb48cfd7fwi($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-2]['intercept-urls'] : null), null, $in, function ($cx, $in) {
                            $inary=is_array($in);
                            echo '',lcr5bbdeb48cfd7fhbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]['bs']) && isset($cx['scopes'][count($cx['scopes'])-3]['bs']['bsId'])) ? $cx['scopes'][count($cx['scopes'])-3]['bs']['bsId'] : null)),array()), $in, false, function ($cx, $in) {
                                $inary=is_array($in);
                                echo '',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['settings-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['settings-id'] : null)),array()), 'encq', $in)),'';
                            }),'';
                        }),'">
';
                    } else {
                        echo '										<a href="#',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" aria-controls="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" role="tab" data-toggle="tab">
';
                    }
                    echo '										';
                    if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null), false)) {
                        echo '<i class="fa fa-fw ',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null)),'"></i>';
                    } else {
                        echo '';
                    }
                    echo '<span class="tab-subtitle">',lcr5bbdeb48cfd7fraw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'</span>
									</a>
								</li>
';
                }),'						</ul>
';
            } else {
                echo '';
            }
            echo '				</li>
';
        }),'		</ul>
';
    }),'',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array((($inary && isset($in['panel-header-type'])) ? $in['panel-header-type'] : null),'btn-group'),array()), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '		<div id="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('group'=>(($inary && isset($in['bootstrap-type'])) ? $in['bootstrap-type'] : null))), 'encq', $in)),'-panel-title" class="',lcr5bbdeb48cfd7fencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['panelheader'])) ? $in['classes']['panelheader'] : null)),'" style="',lcr5bbdeb48cfd7fencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['panelheader'])) ? $in['styles']['panelheader'] : null)),'" role="group" ',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['panelheader-params'])) ? $in['panelheader-params'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo ' ',lcr5bbdeb48cfd7fencq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr5bbdeb48cfd7fencq($cx, $in),'"';
        }),'>
',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['panel-headers'])) ? $in['panel-headers'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo '				<a class="',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array(((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['active'])) ? $cx['scopes'][count($cx['scopes'])-1]['active'] : null),(($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),array()), $in, false, function ($cx, $in) {
                $inary=is_array($in);
                echo 'active';
            }, function ($cx, $in) {
                $inary=is_array($in);
                echo '',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), null, $in, true, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['active'])) ? $cx['scopes'][count($cx['scopes'])-2]['active'] : null),(($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),array()), $in, false, function ($cx, $in) {
                        $inary=is_array($in);
                        echo 'active';
                    }),'';
                }),'';
            }),' ',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['panelheader-item'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['panelheader-item'] : null)),'" style="',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['panelheader-item'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['panelheader-item'] : null)),'" ';
            if (lcr5bbdeb48cfd7fifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['intercept'])) ? $cx['scopes'][count($cx['scopes'])-1]['intercept'] : null), false)) {
                echo ' href="',lcr5bbdeb48cfd7fwi($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-1]['intercept-urls'] : null), null, $in, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fhbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['bs']) && isset($cx['scopes'][count($cx['scopes'])-2]['bs']['bsId'])) ? $cx['scopes'][count($cx['scopes'])-2]['bs']['bsId'] : null)),array()), $in, false, function ($cx, $in) {
                        $inary=is_array($in);
                        echo '',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['settings-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['settings-id'] : null)),array()), 'encq', $in)),'';
                    }),'';
                }),'" ';
            } else {
                echo ' href="#',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" aria-controls="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" role="tab" data-toggle="tab"';
            }
            echo '>
					';
            if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null), false)) {
                echo '<i class="fa fa-fw ',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null)),'"></i>';
            } else {
                echo '';
            }
            echo '',lcr5bbdeb48cfd7fraw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'
				</a>
';
            if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), false)) {
                echo '					<span class="btn-group-dropdown dropdown">
						<button type="button" class="',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array(((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['active'])) ? $cx['scopes'][count($cx['scopes'])-1]['active'] : null),(($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo 'active';
                }, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), null, $in, true, function ($cx, $in) {
                        $inary=is_array($in);
                        echo '',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['active'])) ? $cx['scopes'][count($cx['scopes'])-2]['active'] : null),(($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),array()), $in, false, function ($cx, $in) {
                            $inary=is_array($in);
                            echo 'active';
                        }),'';
                    }),'';
                }),' ',lcr5bbdeb48cfd7fencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['panelheader-item'])) ? $in['classes']['panelheader-item'] : null)),' dropdown-toggle" style="',lcr5bbdeb48cfd7fencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['panelheader-item'])) ? $in['styles']['panelheader-item'] : null)),'" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
						<ul class="dropdown-menu pull-right" role="menu">
',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), null, $in, true, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '								<li role="presentation" class="',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['active'])) ? $cx['scopes'][count($cx['scopes'])-2]['active'] : null),(($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),array()), $in, false, function ($cx, $in) {
                        $inary=is_array($in);
                        echo 'active';
                    }),'">
									<a ';
                    if (lcr5bbdeb48cfd7fifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['intercept'])) ? $cx['scopes'][count($cx['scopes'])-2]['intercept'] : null), false)) {
                        echo '
										href="',lcr5bbdeb48cfd7fwi($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-2]['intercept-urls'] : null), null, $in, function ($cx, $in) {
                            $inary=is_array($in);
                            echo '',lcr5bbdeb48cfd7fhbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]['bs']) && isset($cx['scopes'][count($cx['scopes'])-3]['bs']['bsId'])) ? $cx['scopes'][count($cx['scopes'])-3]['bs']['bsId'] : null)),array()), $in, false, function ($cx, $in) {
                                $inary=is_array($in);
                                echo '',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['settings-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['settings-id'] : null)),array()), 'encq', $in)),'';
                            }),'';
                        }),'" ';
                    } else {
                        echo ' href="#',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" aria-controls="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" role="tab" data-toggle="tab" ';
                    }
                    echo '</a>
										';
                    if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null), false)) {
                        echo '<i class="fa fa-fw ',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null)),'"></i>';
                    } else {
                        echo '';
                    }
                    echo '',lcr5bbdeb48cfd7fraw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'
									</a>
								</li>
';
                }),'						</ul>
					</span>
';
            } else {
                echo '';
            }
            echo '';
        }),'		</div>
';
    }),'',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array((($inary && isset($in['panel-header-type'])) ? $in['panel-header-type'] : null),'dropdown'),array()), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '		<div id="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('group'=>(($inary && isset($in['bootstrap-type'])) ? $in['bootstrap-type'] : null))), 'encq', $in)),'-panel-title" class="clearfix ',lcr5bbdeb48cfd7fencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['panelheader'])) ? $in['classes']['panelheader'] : null)),'" style="',lcr5bbdeb48cfd7fencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['panelheader'])) ? $in['styles']['panelheader'] : null)),'" ',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['panelheader-params'])) ? $in['panelheader-params'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo ' ',lcr5bbdeb48cfd7fencq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr5bbdeb48cfd7fencq($cx, $in),'"';
        }),'>
			<div class="dropdown pull-right">
				<a href="#" class="dropdown-toggle close close-sm" data-toggle="dropdown" role="button">',lcr5bbdeb48cfd7fraw($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['dropdown'])) ? $in['titles']['dropdown'] : null)),'</a>
				<ul class="dropdown-menu" role="menu">
',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['panel-headers'])) ? $in['panel-headers'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo '						<li role="presentation">
';
            if (lcr5bbdeb48cfd7fifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['intercept'])) ? $cx['scopes'][count($cx['scopes'])-1]['intercept'] : null), false)) {
                echo '								<a href="',lcr5bbdeb48cfd7fwi($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-1]['intercept-urls'] : null), null, $in, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fhbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['bs']) && isset($cx['scopes'][count($cx['scopes'])-2]['bs']['bsId'])) ? $cx['scopes'][count($cx['scopes'])-2]['bs']['bsId'] : null)),array()), $in, false, function ($cx, $in) {
                        $inary=is_array($in);
                        echo '',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['settings-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['settings-id'] : null)),array()), 'encq', $in)),'';
                    }),'';
                }),'">
';
            } else {
                echo '								<a href="#',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" aria-controls="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" role="tab" data-toggle="tab">
';
            }
            echo '								';
            if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null), false)) {
                echo '<i class="fa fa-fw ',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null)),'"></i>';
            } else {
                echo '';
            }
            echo '',lcr5bbdeb48cfd7fraw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'
							</a>
						</li>
',lcr5bbdeb48cfd7fsec($cx, (($inary && isset($in['subheaders'])) ? $in['subheaders'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '							<li role="presentation" class="menu-item-parent">
';
                if (lcr5bbdeb48cfd7fifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['intercept'])) ? $cx['scopes'][count($cx['scopes'])-2]['intercept'] : null), false)) {
                    echo '									<a href="',lcr5bbdeb48cfd7fwi($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-2]['intercept-urls'] : null), null, $in, function ($cx, $in) {
                        $inary=is_array($in);
                        echo '',lcr5bbdeb48cfd7fhbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]['bs']) && isset($cx['scopes'][count($cx['scopes'])-3]['bs']['bsId'])) ? $cx['scopes'][count($cx['scopes'])-3]['bs']['bsId'] : null)),array()), $in, false, function ($cx, $in) {
                            $inary=is_array($in);
                            echo '',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['settings-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['settings-id'] : null)),array()), 'encq', $in)),'';
                        }),'';
                    }),'">
';
                } else {
                    echo '									<a href="#',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" aria-controls="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['settings-id'])) ? $in['settings-id'] : null)),'" role="tab" data-toggle="tab">
';
                }
                echo '									';
                if (lcr5bbdeb48cfd7fifvar($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null), false)) {
                    echo '<i class="fa fa-fw ',lcr5bbdeb48cfd7fencq($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null)),'"></i>';
                } else {
                    echo '';
                }
                echo '',lcr5bbdeb48cfd7fraw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'
								</a>
							</li>
';
            }),'';
        }),'				</div>
			</div>
		</div>
';
    }),'';
    if (lcr5bbdeb48cfd7fifvar($cx, ((isset($in['settings-ids']) && is_array($in['settings-ids']) && isset($in['settings-ids']['blockunits'])) ? $in['settings-ids']['blockunits'] : null), false)) {
        echo '		<div class="tab-content">
',lcr5bbdeb48cfd7fsec($cx, ((isset($in['settings-ids']) && is_array($in['settings-ids']) && isset($in['settings-ids']['blockunits'])) ? $in['settings-ids']['blockunits'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo '				<div id="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, $in),'" role="tabpanel" class="tab-pane ',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['bootstrap-component'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['bootstrap-component'] : null)),' ',lcr5bbdeb48cfd7fhbbch($cx, 'compare', array(array(((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['active'])) ? $cx['scopes'][count($cx['scopes'])-1]['active'] : null),$in),array()), $in, false, function ($cx, $in) {
                $inary=is_array($in);
                echo 'active';
            }),' ',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['panel'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['panel'] : null)),' ',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'get', array(array(((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['custom-panel-class'])) ? $cx['scopes'][count($cx['scopes'])-1]['custom-panel-class'] : null),$in),array()), 'encq', $in)),'" style="',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['bootstrap-component'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['bootstrap-component'] : null)),'',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['panel'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['panel'] : null)),'" ',lcr5bbdeb48cfd7fsec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['panel-params'])) ? $cx['scopes'][count($cx['scopes'])-1]['panel-params'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo ' ',lcr5bbdeb48cfd7fencq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr5bbdeb48cfd7fencq($cx, $in),'"';
            }),' ',lcr5bbdeb48cfd7fhbbch($cx, 'withget', array(array(((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['custom-panel-params'])) ? $cx['scopes'][count($cx['scopes'])-1]['custom-panel-params'] : null),$in),array()), $in, false, function ($cx, $in) {
                $inary=is_array($in);
                echo '',lcr5bbdeb48cfd7fsec($cx, $in, null, $in, true, function ($cx, $in) {
                    $inary=is_array($in);
                    echo ' ',lcr5bbdeb48cfd7fencq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr5bbdeb48cfd7fencq($cx, $in),'"';
                }),'';
            }),'>
					<div id="',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1],'group'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-1]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, $in),'-container" class="body ',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'get', array(array(((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['body-class'])) ? $cx['scopes'][count($cx['scopes'])-1]['body-class'] : null),$in),array()), 'encq', $in)),' ',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['container'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['container'] : null)),'" style="',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['container'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['container'] : null)),'">
',lcr5bbdeb48cfd7fhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],$in),array()), $in, false, function ($cx, $in) {
                $inary=is_array($in);
                echo '							',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
							<a ',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'interceptAttr', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2])), 'encq', $in)),' ',lcr5bbdeb48cfd7fhbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>'interceptor')), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['id'])) ? $cx['scopes'][count($cx['scopes'])-2]['id'] : null)),'-',lcr5bbdeb48cfd7fencq($cx, $cx['scopes'][count($cx['scopes'])-1]),'';
                }),' href="#',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'])) ? $cx['scopes'][count($cx['scopes'])-2]['bootstrap-type'] : null))), 'encq', $in)),'-',lcr5bbdeb48cfd7fencq($cx, $cx['scopes'][count($cx['scopes'])-1]),'" data-toggle="tab" role="tab" data-intercept-url="',lcr5bbdeb48cfd7fhbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['module'])) ? $cx['scopes'][count($cx['scopes'])-2]['module'] : null)),array('context'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-2]['intercept-urls'] : null))), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb48cfd7fencq($cx, lcr5bbdeb48cfd7fhbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['module'])) ? $cx['scopes'][count($cx['scopes'])-1]['module'] : null)),array()), 'encq', $in)),'';
                }),'"></a>
';
            }),'					</div>
				</div>
';
        }),'		</div>
';
    } else {
        echo '';
    }
    echo '</div>';
    return ob_get_clean();
};
