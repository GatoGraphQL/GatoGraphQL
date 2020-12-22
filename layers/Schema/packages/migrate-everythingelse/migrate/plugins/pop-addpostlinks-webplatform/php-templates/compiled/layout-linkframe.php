<?php
function lcr5bbdeb3aea1bawi($cx, $v, $bp, $in, $cb, $else = null)
{
    if (isset($bp[0])) {
        $v = lcr5bbdeb3aea1bam($cx, $v, array($bp[0] => $v));
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

function lcr5bbdeb3aea1bahbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null)
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
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb3aea1bam($cx, $_this, $ex) : $_this);
        } else {
            $cx['scopes'][] = $_this;
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb3aea1bam($cx, $context, $ex) : $context);
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

        return lcr5bbdeb3aea1baexch($cx, $ch, $vars, $options);
}

function lcr5bbdeb3aea1baencq($cx, $var)
{
    if ($var instanceof LS) {
        return (string)$var;
    }

    return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdeb3aea1baraw($cx, $var), ENT_QUOTES, 'UTF-8'));
}

function lcr5bbdeb3aea1baifvar($cx, $v, $zero)
{
    return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
}

function lcr5bbdeb3aea1baraw($cx, $v, $ex = 0)
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
                    $ret[] = lcr5bbdeb3aea1baraw($cx, $vv);
                }
                return join(',', $ret);
            }
        } else {
            return 'Array';
        }
    }

    return "$v";
}

function lcr5bbdeb3aea1bahbch($cx, $ch, $vars, $op, &$_this)
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

    return lcr5bbdeb3aea1baexch($cx, $ch, $vars, $options);
}

function lcr5bbdeb3aea1bam($cx, $a, $b)
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

function lcr5bbdeb3aea1baexch($cx, $ch, $vars, &$options)
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
        lcr5bbdeb3aea1baerr($cx, $e);
    }

    return $r;
}

function lcr5bbdeb3aea1baerr($cx, $err)
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
        'and' => function ($lvalue, $rvalue) {
            global $pop_serverside_operatorshelpers;
            return $pop_serverside_operatorshelpers->and($lvalue, $rvalue);
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
    echo '',lcr5bbdeb3aea1bawi($cx, (($inary && isset($in['dbObject'])) ? $in['dbObject'] : null), null, $in, function ($cx, $in) {
        $inary=is_array($in);
        echo '	<div ',lcr5bbdeb3aea1bahbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1])), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '',lcr5bbdeb3aea1baencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'';
        }),'>
';
        if (lcr5bbdeb3aea1baifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['print-source'])) ? $cx['scopes'][count($cx['scopes'])-1]['print-source'] : null), false)) {
            echo '			<p>
				',lcr5bbdeb3aea1baraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['source'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['source'] : null)),' <a href="',lcr5bbdeb3aea1baencq($cx, (($inary && isset($in['link'])) ? $in['link'] : null)),'" target="_blank">',lcr5bbdeb3aea1baencq($cx, (($inary && isset($in['link'])) ? $in['link'] : null)),'</a>
			</p>
';
        } else {
            echo '';
        }
        echo '		<p class="btn-group">
';
        if (lcr5bbdeb3aea1baifvar($cx, lcr5bbdeb3aea1bahbch($cx, 'and', array(array(((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-frame-in-collapse'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-frame-in-collapse'] : null),(($inary && isset($in['is-link-embeddable'])) ? $in['is-link-embeddable'] : null)),array()), 'raw', $in), false)) {
            echo '				<a href="#',lcr5bbdeb3aea1baencq($cx, lcr5bbdeb3aea1bahbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),' > .collapse" class="',lcr5bbdeb3aea1baencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['loadlink-btn'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['loadlink-btn'] : null)),'" style="',lcr5bbdeb3aea1baencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['loadlink-btn'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['loadlink-btn'] : null)),'" data-toggle="collapse"><i class="fa fa-fw fa-link"></i>',lcr5bbdeb3aea1baencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['loadlink'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['loadlink'] : null)),'</a>
';
        } else {
            echo '';
        }
        echo '			<a href="',lcr5bbdeb3aea1baencq($cx, (($inary && isset($in['link'])) ? $in['link'] : null)),'" class="',lcr5bbdeb3aea1baencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['opennewtab-btn'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['opennewtab-btn'] : null)),'" style="',lcr5bbdeb3aea1baencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['opennewtab-btn'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['opennewtab-btn'] : null)),'" target="_blank"><i class="fa fa-fw fa-external-link"></i>',lcr5bbdeb3aea1baencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['opennewtab'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['opennewtab'] : null)),'</a>
		</p>
';
        if (lcr5bbdeb3aea1baifvar($cx, (($inary && isset($in['is-link-embeddable'])) ? $in['is-link-embeddable'] : null), false)) {
            echo '';
            if (lcr5bbdeb3aea1baifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-frame-in-collapse'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-frame-in-collapse'] : null), false)) {
                echo '				<div class="collapse ',lcr5bbdeb3aea1baencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['collapse'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['collapse'] : null)),'" style="',lcr5bbdeb3aea1baencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['collapse'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['collapse'] : null)),'"></div>
				<script type="text/javascript">
				(function($){
					var collapse = $("#',lcr5bbdeb3aea1baencq($cx, lcr5bbdeb3aea1bahbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),'").children(\'.collapse\');
					collapse.one(\'show.bs.collapse\', function() { 
						collapse.html(\'',lcr5bbdeb3aea1bahbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'layout'),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb3aea1baencq($cx, lcr5bbdeb3aea1bahbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'';
                }),'\'); 
					});
				})(jQuery);
				</script>
';
            } else {
                echo '				',lcr5bbdeb3aea1bahbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'layout'),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '',lcr5bbdeb3aea1baencq($cx, lcr5bbdeb3aea1bahbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'';
                }),'
';
            }
            echo '';
        } else {
            echo '';
        }
        echo '	</div>
';
    }),'';
    return ob_get_clean();
};
