<?php
 function lcr593c9e95ddcf0sec($cx, $v, $bp, $in, $each, $cb, $else = null) {
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
     $raw = lcr593c9e95ddcf0m($cx, $raw, array($bp[0] => $raw));
    }
    if (isset($bp[1])) {
     $raw = lcr593c9e95ddcf0m($cx, $raw, array($bp[1] => $cx['sp_vars']['index']));
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

 function lcr593c9e95ddcf0hbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null) {
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
   } else if (isset($cx['blparam'][0])) {
    $ex = $cx['blparam'][0];
   }
   if (($context === '_NO_INPUT_HERE_') || ($context === $_this)) {
    $ret = $cb($cx, is_array($ex) ? lcr593c9e95ddcf0m($cx, $_this, $ex) : $_this);
   } else {
    $cx['scopes'][] = $_this;
    $ret = $cb($cx, is_array($ex) ? lcr593c9e95ddcf0m($cx, $context, $ex) : $context);
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

  return lcr593c9e95ddcf0exch($cx, $ch, $vars, $options);
 }

 function lcr593c9e95ddcf0encq($cx, $var) {
  if ($var instanceof LS) {
   return (string)$var;
  }

  return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr593c9e95ddcf0raw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr593c9e95ddcf0hbch($cx, $ch, $vars, $op, &$_this) {
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

  return lcr593c9e95ddcf0exch($cx, $ch, $vars, $options);
 }

 function lcr593c9e95ddcf0raw($cx, $v, $ex = 0) {
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
      $ret[] = lcr593c9e95ddcf0raw($cx, $vv);
     }
     return join(',', $ret);
    }
   } else {
    return 'Array';
   }
  }

  return "$v";
 }

 function lcr593c9e95ddcf0ifvar($cx, $v, $zero) {
  return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
 }

 function lcr593c9e95ddcf0m($cx, $a, $b) {
  if (is_array($b)) {
   if ($a === null) {
    return $b;
   } else if (is_array($a)) {
    return array_merge($a, $b);
   } else if (($cx['flags']['method'] || $cx['flags']['prop']) && is_object($a)) {
    foreach ($b as $i => $v) {
     $a->$i = $v;
    }
   }
  }
  return $a;
 }

 function lcr593c9e95ddcf0exch($cx, $ch, $vars, &$options) {
  $args = $vars[0];
  $args[] = $options;
  $e = null;
  $r = true;

  try {
   $r = call_user_func_array($cx['helpers'][$ch], $args);
  } catch (\Exception $E) {
   $e = "Runtime: call custom helper '$ch' error: " . $E->getMessage();
  }

  if($e !== null) {
   lcr593c9e95ddcf0err($cx, $e);
  }

  return $r;
 }

 function lcr593c9e95ddcf0err($cx, $err) {
  if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_LOG']) {
   error_log($err);
   return;
  }
  if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_EXCEPTION']) {
   throw new \Exception($err);
  }
 }

if (!class_exists("LS")) {
class LS {
 public static $jsContext = array (
  'flags' => 
  array (
    'jstrue' => 1,
    'jsobj' => 1,
  ),
);
    public function __construct($str, $escape = false) {
        $this->string = $escape ? (($escape === 'encq') ? static::encq(static::$jsContext, $str) : static::enc(static::$jsContext, $str)) : $str;
    }
    public function __toString() {
        return $this->string;
    }
    public static function stripExtendedComments($template) {
        return preg_replace(static::EXTENDED_COMMENT_SEARCH, '{{! }}', $template);
    }
    public static function escapeTemplate($template) {
        return addcslashes(addcslashes($template, '\\'), "'");
    }
    public static function raw($cx, $v, $ex = 0) {
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
    public static function enc($cx, $var) {
        return htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8');
    }
    public static function encq($cx, $var) {
        return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8'));
    }
}
}
return function ($in = null, $options = null) {
    $helpers = array(            'destroyUrl' => function($url, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->destroyUrl($url, $options);
	},
            'get' => function($db, $index, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->get($db, $index, $options);
	},
            'ifget' => function($db, $index, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->ifget($db, $index, $options);
	},
            'withget' => function($db, $index, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withget($db, $index, $options);
	},
            'interceptAttr' => function($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->interceptAttr($options);
	},
            'generateId' => function($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->generateId($options);
	},
            'lastGeneratedId' => function($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->lastGeneratedId($options);
	},
            'applyLightTemplate' => function($template, $options){ 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->applyLightTemplate($template, $options);
	},
            'enterModule' => function($prevContext, $options){ 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->enterModule($prevContext, $options);
	},
            'withBlock' => function($context, $blockSettingsId, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withBlock($context, $blockSettingsId, $options);
	},
            'withSublevel' => function($sublevel, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withSublevel($sublevel, $options);
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
    ob_start();echo '',lcr593c9e95ddcf0sec($cx, ((isset($in['block-settings-ids']) && is_array($in['block-settings-ids']) && isset($in['block-settings-ids']['blockunits'])) ? $in['block-settings-ids']['blockunits'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0hbbch($cx, 'withBlock', array(array($cx['scopes'][count($cx['scopes'])-1],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '		<div ',lcr593c9e95ddcf0hbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'targetId'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['pss']) && isset($cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'] : null),'group'=>'modal')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['id'])) ? $cx['scopes'][count($cx['scopes'])-2]['id'] : null)),'',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['tls']['feedback']) && isset($cx['scopes'][count($cx['scopes'])-2]['tls']['feedback']['unique-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['tls']['feedback']['unique-id'] : null)),'-',lcr593c9e95ddcf0encq($cx, $cx['scopes'][count($cx['scopes'])-1]),'';}),' role="dialog" class="modal ',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['class'])) ? $cx['scopes'][count($cx['scopes'])-2]['class'] : null)),'" ',lcr593c9e95ddcf0sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['params'])) ? $cx['scopes'][count($cx['scopes'])-2]['params'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo ' ',lcr593c9e95ddcf0encq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr593c9e95ddcf0encq($cx, $in),'"';}),' aria-hidden="true" aria-labelledby="',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'targetId'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['pss']) && isset($cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'] : null),'group'=>'modal')), 'encq', $in)),'">	
			<div class="pop-modaldialog modal-dialog ',lcr593c9e95ddcf0hbbch($cx, 'ifget', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['dialogs'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['dialogs'] : null),(($inary && isset($in['template'])) ? $in['template'] : null)),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0raw($cx, lcr593c9e95ddcf0hbch($cx, 'get', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['dialogs'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['dialogs'] : null),(($inary && isset($in['template'])) ? $in['template'] : null)),array()), 'raw', $in)),'';}),'">
				<div class="pop-modalcontent modal-content">
					<div class="modal-header">
						<button type="button" class="close close-lg" data-dismiss="modal" aria-hidden="true">&times;</button>
						<div class="pop-header">
',lcr593c9e95ddcf0hbbch($cx, 'ifget', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['titles']) && isset($cx['scopes'][count($cx['scopes'])-2]['titles']['headers'])) ? $cx['scopes'][count($cx['scopes'])-2]['titles']['headers'] : null),(($inary && isset($in['template'])) ? $in['template'] : null)),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '								',lcr593c9e95ddcf0raw($cx, lcr593c9e95ddcf0hbch($cx, 'get', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['titles']) && isset($cx['scopes'][count($cx['scopes'])-2]['titles']['headers'])) ? $cx['scopes'][count($cx['scopes'])-2]['titles']['headers'] : null),(($inary && isset($in['template'])) ? $in['template'] : null)),array()), 'raw', $in)),'
';}),'							<div class="pop-box ',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['header'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['header'] : null)),'"></div>
						</div>
					</div>
					<div class="pop-modalbody ',lcr593c9e95ddcf0hbbch($cx, 'ifget', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['bodies'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['bodies'] : null),(($inary && isset($in['template'])) ? $in['template'] : null)),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0raw($cx, lcr593c9e95ddcf0hbch($cx, 'get', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['bodies'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['bodies'] : null),(($inary && isset($in['template'])) ? $in['template'] : null)),array()), 'raw', $in)),'';}),'">
						',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'enterModule', array(array($in),array('parentContext'=>$cx['scopes'][count($cx['scopes'])-2])), 'encq', $in)),'
					</div>
				</div>
			</div>
			<a ',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'interceptAttr', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2])), 'encq', $in)),' ',lcr593c9e95ddcf0hbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'targetId'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['pss']) && isset($cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'] : null),'group'=>'interceptor')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['id'])) ? $cx['scopes'][count($cx['scopes'])-2]['id'] : null)),'',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['tls']['feedback']) && isset($cx['scopes'][count($cx['scopes'])-2]['tls']['feedback']['unique-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['tls']['feedback']['unique-id'] : null)),'-',lcr593c9e95ddcf0encq($cx, $cx['scopes'][count($cx['scopes'])-1]),'';}),' href="#',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'targetId'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['pss']) && isset($cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'] : null),'group'=>'modal')), 'encq', $in)),'" data-toggle="modal" ';if (lcr593c9e95ddcf0ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['intercept-skipstateupdate'])) ? $cx['scopes'][count($cx['scopes'])-2]['intercept-skipstateupdate'] : null), false)){echo 'data-intercept-skipstateupdate="true"';}else{echo '';}echo ' data-intercept-url="',lcr593c9e95ddcf0hbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['template'])) ? $cx['scopes'][count($cx['scopes'])-2]['template'] : null)),array('context'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['pss']['feedback']) && isset($cx['scopes'][count($cx['scopes'])-2]['pss']['feedback']['intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-2]['pss']['feedback']['intercept-urls'] : null))), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]) && isset($cx['scopes'][count($cx['scopes'])-3]['template'])) ? $cx['scopes'][count($cx['scopes'])-3]['template'] : null)),array()), 'encq', $in)),'';}),'" data-title="',lcr593c9e95ddcf0raw($cx, ((isset($in['bs']['feedback']) && is_array($in['bs']['feedback']) && isset($in['bs']['feedback']['title'])) ? $in['bs']['feedback']['title'] : null)),'"></a>
',lcr593c9e95ddcf0hbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['template'])) ? $cx['scopes'][count($cx['scopes'])-2]['template'] : null)),array('context'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['pss']['feedback']) && isset($cx['scopes'][count($cx['scopes'])-2]['pss']['feedback']['extra-intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-2]['pss']['feedback']['extra-intercept-urls'] : null))), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0hbbch($cx, 'withget', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]) && isset($cx['scopes'][count($cx['scopes'])-3]['template'])) ? $cx['scopes'][count($cx['scopes'])-3]['template'] : null)),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0sec($cx, $in, null, $in, true, function($cx, $in) {$inary=is_array($in);echo '						<a ',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'interceptAttr', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-5])), 'encq', $in)),' ',lcr593c9e95ddcf0hbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-5],'targetId'=>((isset($cx['scopes'][count($cx['scopes'])-5]) && is_array($cx['scopes'][count($cx['scopes'])-5]['pss']) && isset($cx['scopes'][count($cx['scopes'])-5]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-5]['pss']['pssId'] : null),'group'=>'interceptor')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-5]) && is_array($cx['scopes'][count($cx['scopes'])-5]) && isset($cx['scopes'][count($cx['scopes'])-5]['id'])) ? $cx['scopes'][count($cx['scopes'])-5]['id'] : null)),'',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-5]) && is_array($cx['scopes'][count($cx['scopes'])-5]['tls']['feedback']) && isset($cx['scopes'][count($cx['scopes'])-5]['tls']['feedback']['unique-id'])) ? $cx['scopes'][count($cx['scopes'])-5]['tls']['feedback']['unique-id'] : null)),'-',lcr593c9e95ddcf0encq($cx, $cx['scopes'][count($cx['scopes'])-4]),'-',lcr593c9e95ddcf0encq($cx, (isset($cx['sp_vars']['index']) ? $cx['sp_vars']['index'] : null)),'';}),' href="#',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-5],'targetId'=>((isset($cx['scopes'][count($cx['scopes'])-5]) && is_array($cx['scopes'][count($cx['scopes'])-5]['pss']) && isset($cx['scopes'][count($cx['scopes'])-5]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-5]['pss']['pssId'] : null),'group'=>'modal')), 'encq', $in)),'" data-toggle="modal" ';if (lcr593c9e95ddcf0ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-5]) && is_array($cx['scopes'][count($cx['scopes'])-5]) && isset($cx['scopes'][count($cx['scopes'])-5]['intercept-skipstateupdate'])) ? $cx['scopes'][count($cx['scopes'])-5]['intercept-skipstateupdate'] : null), false)){echo 'data-intercept-skipstateupdate="true"';}else{echo '';}echo ' data-intercept-url="',lcr593c9e95ddcf0encq($cx, $in),'"></a>
';}),'';}),'';}),'			<a ',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'interceptAttr', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2])), 'encq', $in)),' ',lcr593c9e95ddcf0hbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'targetId'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['pss']) && isset($cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'] : null),'group'=>'destroy-interceptor')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['id'])) ? $cx['scopes'][count($cx['scopes'])-2]['id'] : null)),'',lcr593c9e95ddcf0encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['tls']['feedback']) && isset($cx['scopes'][count($cx['scopes'])-2]['tls']['feedback']['unique-id'])) ? $cx['scopes'][count($cx['scopes'])-2]['tls']['feedback']['unique-id'] : null)),'-',lcr593c9e95ddcf0encq($cx, $cx['scopes'][count($cx['scopes'])-1]),'';}),' data-target="#',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'lastGeneratedId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'targetId'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['pss']) && isset($cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-2]['pss']['pssId'] : null),'group'=>'modal')), 'encq', $in)),'" data-intercept-url="',lcr593c9e95ddcf0hbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['template'])) ? $cx['scopes'][count($cx['scopes'])-2]['template'] : null)),array('context'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['pss']['feedback']) && isset($cx['scopes'][count($cx['scopes'])-2]['pss']['feedback']['intercept-urls'])) ? $cx['scopes'][count($cx['scopes'])-2]['pss']['feedback']['intercept-urls'] : null))), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0hbbch($cx, 'withSublevel', array(array(((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]) && isset($cx['scopes'][count($cx['scopes'])-3]['template'])) ? $cx['scopes'][count($cx['scopes'])-3]['template'] : null)),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'destroyUrl', array(array($in),array()), 'encq', $in)),'';}),'';}),'" data-intercept-skipstateupdate="true"></a>
',lcr593c9e95ddcf0sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-2]['template-ids']['insideextensions'])) ? $cx['scopes'][count($cx['scopes'])-2]['template-ids']['insideextensions'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '				',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'applyLightTemplate', array(array($in),array('context'=>$cx['scopes'][count($cx['scopes'])-3])), 'encq', $in)),'
';}),'		</div>
';}),'';}),'',lcr593c9e95ddcf0sec($cx, ((isset($in['template-ids']) && is_array($in['template-ids']) && isset($in['template-ids']['extensions'])) ? $in['template-ids']['extensions'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '	',lcr593c9e95ddcf0encq($cx, lcr593c9e95ddcf0hbch($cx, 'applyLightTemplate', array(array($in),array('context'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),'
';}),'';return ob_get_clean();
};
?>