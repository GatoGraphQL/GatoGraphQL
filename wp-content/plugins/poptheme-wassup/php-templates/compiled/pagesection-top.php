<?php
 function lcr5a2ad1517962chbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null) {
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
    $ret = $cb($cx, is_array($ex) ? lcr5a2ad1517962cm($cx, $_this, $ex) : $_this);
   } else {
    $cx['scopes'][] = $_this;
    $ret = $cb($cx, is_array($ex) ? lcr5a2ad1517962cm($cx, $context, $ex) : $context);
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

  return lcr5a2ad1517962cexch($cx, $ch, $vars, $options);
 }

 function lcr5a2ad1517962cifvar($cx, $v, $zero) {
  return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
 }

 function lcr5a2ad1517962cencq($cx, $var) {
  if ($var instanceof LS) {
   return (string)$var;
  }

  return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5a2ad1517962craw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr5a2ad1517962craw($cx, $v, $ex = 0) {
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
      $ret[] = lcr5a2ad1517962craw($cx, $vv);
     }
     return join(',', $ret);
    }
   } else {
    return 'Array';
   }
  }

  return "$v";
 }

 function lcr5a2ad1517962chbch($cx, $ch, $vars, $op, &$_this) {
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

  return lcr5a2ad1517962cexch($cx, $ch, $vars, $options);
 }

 function lcr5a2ad1517962cwi($cx, $v, $bp, $in, $cb, $else = null) {
  if (isset($bp[0])) {
   $v = lcr5a2ad1517962cm($cx, $v, array($bp[0] => $v));
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

 function lcr5a2ad1517962csec($cx, $v, $bp, $in, $each, $cb, $else = null) {
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
     $raw = lcr5a2ad1517962cm($cx, $raw, array($bp[0] => $raw));
    }
    if (isset($bp[1])) {
     $raw = lcr5a2ad1517962cm($cx, $raw, array($bp[1] => $cx['sp_vars']['index']));
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

 function lcr5a2ad1517962cm($cx, $a, $b) {
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

 function lcr5a2ad1517962cexch($cx, $ch, $vars, &$options) {
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
   lcr5a2ad1517962cerr($cx, $e);
  }

  return $r;
 }

 function lcr5a2ad1517962cerr($cx, $err) {
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
    $helpers = array(            'generateId' => function($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->generateId($options);
	},
            'enterModule' => function($prevContext, $options){ 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->enterModule($prevContext, $options);
	},
            'withModule' => function($context, $module, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withModule($context, $module, $options);
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
    ob_start();echo '<ul class="nav navbar-nav navbar-right visiblesearch-visible-right" role="menu">
',lcr5a2ad1517962chbbch($cx, 'withModule', array(array($in,'block-oursponsors'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '';if (lcr5a2ad1517962cifvar($cx, ((isset($in['bs']) && is_array($in['bs']) && isset($in['bs']['dataset'])) ? $in['bs']['dataset'] : null), false)){echo '			<li class="dropdown nav-sponsors nav-dropdown visiblesearch-visible">
				<a href="#" class="dropdown-toggle" ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['pss']) && isset($cx['scopes'][count($cx['scopes'])-1]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-1]['pss']['pssId'] : null),'template'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['template'])) ? $cx['scopes'][count($cx['scopes'])-1]['template'] : null),'group'=>'void-link')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'-sponsors';}),' title="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['sponsors'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['sponsors'] : null)),'">
					<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['icons']) && isset($cx['scopes'][count($cx['scopes'])-1]['icons']['sponsors'])) ? $cx['scopes'][count($cx['scopes'])-1]['icons']['sponsors'] : null)),'"></span>
				</a>
				<div class="dropdown-menu pull-right" role="menu">
					<div class="col-xs-12" id="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'-sponsors">
						<p>',lcr5a2ad1517962craw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['sponsors-description'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['sponsors-description'] : null)),'</p>
						',lcr5a2ad1517962cencq($cx, lcr5a2ad1517962chbch($cx, 'enterModule', array(array($in),array()), 'encq', $in)),'
						<hr/>
						<div><span class="pull-right">',lcr5a2ad1517962craw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['sponsorus'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['sponsorus'] : null)),'</a></span>',lcr5a2ad1517962craw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['viewallsponsors'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['viewallsponsors'] : null)),'</div>
					</div>
				</div>
			</li>
';}else{echo '';}echo '';}),'	<li class="dropdown nav-about nav-dropdown visiblesearch-visible">
		<a title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['about'])) ? $in['titles']['about'] : null)),'" href="#" ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'void-link')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-info';}),'>
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['about'])) ? $in['icons']['about'] : null)),'"></span>
		</a>
		<div class="dropdown-menu dropdown-menu-right" role="menu">
			<div class="row">
				<div class="col-sm-6 col-logo hidden-xs">
',lcr5a2ad1517962cwi($cx, (($inary && isset($in['logo-large-white'])) ? $in['logo-large-white'] : null), null, $in, function($cx, $in) {$inary=is_array($in);echo '						<img src="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['src'])) ? $in['src'] : null)),'" class="img-responsive" alt="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'">
					';}),'<br/>
',lcr5a2ad1517962csec($cx, (($inary && isset($in['socialmedias'])) ? $in['socialmedias'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '						<a href="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['link'])) ? $in['link'] : null)),'" class="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['socialmedia'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['socialmedia'] : null)),'" style="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['socialmedia'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['socialmedia'] : null)),'" title="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'">
							',lcr5a2ad1517962craw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'
						</a><br/>
';}),'				</div>
				<div class="col-sm-6" id="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-menu-about">
',lcr5a2ad1517962chbbch($cx, 'withModule', array(array($in,'block-menu-about'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '						',lcr5a2ad1517962cencq($cx, lcr5a2ad1517962chbch($cx, 'enterModule', array(array($in),array()), 'encq', $in)),'
';}),'					<div class="hidden-sm hidden-md hidden-lg">
						<hr/>
						<div class="row">
							<div class="col-xs-6 col-logo">
',lcr5a2ad1517962cwi($cx, (($inary && isset($in['logo-large-white'])) ? $in['logo-large-white'] : null), null, $in, function($cx, $in) {$inary=is_array($in);echo '									<img src="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['src'])) ? $in['src'] : null)),'" class="img-responsive" alt="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'">
';}),'							</div>
							<div class="col-xs-6">								
',lcr5a2ad1517962csec($cx, (($inary && isset($in['socialmedias'])) ? $in['socialmedias'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '									<a href="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['link'])) ? $in['link'] : null)),'" class="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['socialmedia'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['socialmedia'] : null)),'" style="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['socialmedia'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['socialmedia'] : null)),'" title="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'">
										',lcr5a2ad1517962craw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'
									</a><br/>
';}),'							</div>
						</div>
					</div>				
				</div>
			</div>
			<hr/>
			<div class="text-padded text-center"><small>',lcr5a2ad1517962craw($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['footer'])) ? $in['titles']['footer'] : null)),'</small></div>
		</div>
	</li>
	<li class="nav-settings visiblesearch-visible">
		<a title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['settings'])) ? $in['titles']['settings'] : null)),'" href="',lcr5a2ad1517962cencq($cx, ((isset($in['links']) && is_array($in['links']) && isset($in['links']['settings'])) ? $in['links']['settings'] : null)),'">
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['settings'])) ? $in['icons']['settings'] : null)),'"></span>
		</a>
	</li>
	<li class="hidden-sm hidden-md hidden-lg pop-toggle-search">
		<a ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglesearch-xs')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';}),' href="#" class="toggle-side" title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglesearch'])) ? $in['titles']['togglesearch'] : null)),'" data-target="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['togglesearch-target'])) ? $in['togglesearch-target'] : null)),'" data-mode="toggle" data-class="pop-search-visible">
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['togglesearch'])) ? $in['icons']['togglesearch'] : null)),'"></span>
		</a>
	</li>
</ul>

<ul class="nav navbar-nav framesection-navbar-left visiblesearch-hidden" role="menu">
	<li class="activeside-hidden">
		<a ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'logo')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';}),' class="logo" href="',lcr5a2ad1517962cencq($cx, ((isset($in['links']) && is_array($in['links']) && isset($in['links']['home'])) ? $in['links']['home'] : null)),'" target="',lcr5a2ad1517962cencq($cx, ((isset($in['targets']) && is_array($in['targets']) && isset($in['targets']['home'])) ? $in['targets']['home'] : null)),'" title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['home'])) ? $in['titles']['home'] : null)),'" data-tooltip-placement="bottom">
',lcr5a2ad1517962cwi($cx, (($inary && isset($in['logo-small'])) ? $in['logo-small'] : null), null, $in, function($cx, $in) {$inary=is_array($in);echo '				<img src="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['src'])) ? $in['src'] : null)),'" alt="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['home'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['home'] : null)),'">
';}),'		</a>
	</li>
	<li class="dropdown nav-addcontent nav-dropdown activeside-hidden">
		<a title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['addcontent'])) ? $in['titles']['addcontent'] : null)),'" href="#" ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'void-link')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-addcontent';}),'>
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['addcontent'])) ? $in['icons']['addcontent'] : null)),'"></span>
		</a>
		<div class="dropdown-menu" role="menu">
			<div class="media">
				<div class="media-left">
					<div class="top-iconleft-container">
						',lcr5a2ad1517962craw($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['addcontent-left'])) ? $in['titles']['addcontent-left'] : null)),'
					</div>
				</div>
				<div class="media-body" id="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-menu-addcontent">
					<h3 class="media-heading">',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['addcontent-right'])) ? $in['titles']['addcontent-right'] : null)),'</h3>
',lcr5a2ad1517962chbbch($cx, 'withModule', array(array($in,'block-menu-addnew'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '						',lcr5a2ad1517962cencq($cx, lcr5a2ad1517962chbch($cx, 'enterModule', array(array($in),array()), 'encq', $in)),'
';}),'				</div>				
			</div>
		</div>
	</li>
</ul>
<ul class="nav navbar-nav navbar-nav-togglenav visiblesearch-hidden" role="menu">
	<li class="hidden-sm hidden-md hidden-lg">
		<a ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglenav-xs')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';}),' href="#" class="toggle-side" title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglenavigation'])) ? $in['titles']['togglenavigation'] : null)),'" data-target="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['offcanvas-sidenav-target'])) ? $in['offcanvas-sidenav-target'] : null)),'" data-toggle="offcanvas-toggle" data-mode="xs">
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['togglenavigation'])) ? $in['icons']['togglenavigation'] : null)),'"></span>
		</a>
	</li>
	<li class="hidden-xs">
		<a ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglenav')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';}),' href="#" class="toggle-side activenavigator-hidden" title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglenavigation'])) ? $in['titles']['togglenavigation'] : null)),'" data-target="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['offcanvas-sidenav-target'])) ? $in['offcanvas-sidenav-target'] : null)),'" data-toggle="offcanvas-toggle" data-tooltip-placement="bottom" ',lcr5a2ad1517962csec($cx, (($inary && isset($in['togglenav-params'])) ? $in['togglenav-params'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo ' ',lcr5a2ad1517962cencq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr5a2ad1517962cencq($cx, $in),'"';}),'>
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['togglenavigation'])) ? $in['icons']['togglenavigation'] : null)),'"></span>
		</a>
		<a ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglenavigator')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';}),' href="#" class="toggle-side activenavigator-visible" title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglenavigation'])) ? $in['titles']['togglenavigation'] : null)),'" data-target="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['offcanvas-navigator-target'])) ? $in['offcanvas-navigator-target'] : null)),'" data-toggle="offcanvas-toggle" data-tooltip-placement="bottom">
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['togglenavigation'])) ? $in['icons']['togglenavigation'] : null)),'"></span>
		</a>
	</li>
	<li class="hidden-sm hidden-md hidden-lg">
		<a ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglepagetabs-xs')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';}),' href="#" class="toggle-side" title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglepagetabs'])) ? $in['titles']['togglepagetabs'] : null)),'" data-target="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['offcanvas-pagetabs-target'])) ? $in['offcanvas-pagetabs-target'] : null)),'" data-toggle="offcanvas-toggle" data-mode="xs">
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['togglepagetabs'])) ? $in['icons']['togglepagetabs'] : null)),'"></span>
		</a>
	</li>
	<li class="hidden-xs">
		<a ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'togglepagetabs')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';}),' href="#" class="toggle-side" title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['togglepagetabs'])) ? $in['titles']['togglepagetabs'] : null)),'" data-target="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['offcanvas-pagetabs-target'])) ? $in['offcanvas-pagetabs-target'] : null)),'" data-toggle="offcanvas-toggle" data-tooltip-placement="bottom">
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['togglepagetabs'])) ? $in['icons']['togglepagetabs'] : null)),'"></span>
		</a>
	</li>
</ul>
<div id="account-loading-msg" class="hidden visiblesearch-hidden">',lcr5a2ad1517962craw($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['account-loading-msg'])) ? $in['titles']['account-loading-msg'] : null)),'</div>
<ul class="nav navbar-nav navbar-nav-account visiblesearch-hidden" role="menu">
	<li class="dropdown nav-account nav-dropdown visible-loggedin-localdomain">
		<a title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['myprofile'])) ? $in['titles']['myprofile'] : null)),'" href="',lcr5a2ad1517962cencq($cx, ((isset($in['tls']['feedback']['user']) && is_array($in['tls']['feedback']['user']) && isset($in['tls']['feedback']['user']['url'])) ? $in['tls']['feedback']['user']['url'] : null)),'" class="pop-user-url ',lcr5a2ad1517962cencq($cx, ((isset($in['tls']) && is_array($in['tls']) && isset($in['tls']['domain-id'])) ? $in['tls']['domain-id'] : null)),' user-avatar hidden-xs hidden-sm">
			<img src="',lcr5a2ad1517962cencq($cx, ((isset($in['tls']['feedback']['user']) && is_array($in['tls']['feedback']['user']) && isset($in['tls']['feedback']['user']['avatar'])) ? $in['tls']['feedback']['user']['avatar'] : null)),'" class="pop-user-avatar ',lcr5a2ad1517962cencq($cx, ((isset($in['tls']) && is_array($in['tls']) && isset($in['tls']['domain-id'])) ? $in['tls']['domain-id'] : null)),' img-thumbnail">
		</a>
		<a title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['myprofile'])) ? $in['titles']['myprofile'] : null)),'" href="#" class="user-avatar hidden-md hidden-lg" ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'void-link')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-useraccount-loggedin';}),'>
			<img src="',lcr5a2ad1517962cencq($cx, ((isset($in['tls']['feedback']['user']) && is_array($in['tls']['feedback']['user']) && isset($in['tls']['feedback']['user']['avatar'])) ? $in['tls']['feedback']['user']['avatar'] : null)),'" class="pop-user-avatar ',lcr5a2ad1517962cencq($cx, ((isset($in['tls']) && is_array($in['tls']) && isset($in['tls']['domain-id'])) ? $in['tls']['domain-id'] : null)),' img-thumbnail">
		</a>
		<div class="dropdown-menu" role="menu">
			<div class="media">
				<div class="media-left">
					<div class="top-useravatar-container">
						<a title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['myprofile'])) ? $in['titles']['myprofile'] : null)),'" href="',lcr5a2ad1517962cencq($cx, ((isset($in['tls']['feedback']['user']) && is_array($in['tls']['feedback']['user']) && isset($in['tls']['feedback']['user']['url'])) ? $in['tls']['feedback']['user']['url'] : null)),'" class="pop-user-url ',lcr5a2ad1517962cencq($cx, ((isset($in['tls']) && is_array($in['tls']) && isset($in['tls']['domain-id'])) ? $in['tls']['domain-id'] : null)),' user-avatar thumbnail">
							<img src="',lcr5a2ad1517962cencq($cx, ((isset($in['tls']['feedback']['user']) && is_array($in['tls']['feedback']['user']) && isset($in['tls']['feedback']['user']['avatar'])) ? $in['tls']['feedback']['user']['avatar'] : null)),'" class="pop-user-avatar ',lcr5a2ad1517962cencq($cx, ((isset($in['tls']) && is_array($in['tls']) && isset($in['tls']['domain-id'])) ? $in['tls']['domain-id'] : null)),'">
						</a>
						<a href="',lcr5a2ad1517962cencq($cx, ((isset($in['links']) && is_array($in['links']) && isset($in['links']['useravatar'])) ? $in['links']['useravatar'] : null)),'" class="',lcr5a2ad1517962cencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['useravatar'])) ? $in['classes']['useravatar'] : null)),' useravatar-link" style="',lcr5a2ad1517962cencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['useravatar'])) ? $in['styles']['useravatar'] : null)),'">
							',lcr5a2ad1517962craw($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['useravatar'])) ? $in['titles']['useravatar'] : null)),'
						</a>
					</div>
				</div>
				<div class="media-body" id="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-menu-userloggedin">
					<h3 class="media-heading"><a title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['myprofile'])) ? $in['titles']['myprofile'] : null)),'" href="',lcr5a2ad1517962cencq($cx, ((isset($in['tls']['feedback']['user']) && is_array($in['tls']['feedback']['user']) && isset($in['tls']['feedback']['user']['url'])) ? $in['tls']['feedback']['user']['url'] : null)),'" class="pop-user-url ',lcr5a2ad1517962cencq($cx, ((isset($in['tls']) && is_array($in['tls']) && isset($in['tls']['domain-id'])) ? $in['tls']['domain-id'] : null)),' user-avatar"><span class="pop-user-name ',lcr5a2ad1517962cencq($cx, ((isset($in['tls']) && is_array($in['tls']) && isset($in['tls']['domain-id'])) ? $in['tls']['domain-id'] : null)),'">',lcr5a2ad1517962craw($cx, ((isset($in['tls']['feedback']['user']) && is_array($in['tls']['feedback']['user']) && isset($in['tls']['feedback']['user']['name'])) ? $in['tls']['feedback']['user']['name'] : null)),'</span></a></h3>
',lcr5a2ad1517962chbbch($cx, 'withModule', array(array($in,'block-menu-userloggedin'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '						',lcr5a2ad1517962cencq($cx, lcr5a2ad1517962chbch($cx, 'enterModule', array(array($in),array()), 'encq', $in)),'
';}),'				</div>				
			</div>
		</div>
	</li>
	<li class="dropdown nav-account nav-dropdown visible-notloggedin-localdomain">
		<a title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['loginaddprofile'])) ? $in['titles']['loginaddprofile'] : null)),'" href="',lcr5a2ad1517962cencq($cx, ((isset($in['links']) && is_array($in['links']) && isset($in['links']['login'])) ? $in['links']['login'] : null)),'" class="hidden-xs hidden-sm">
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['account'])) ? $in['icons']['account'] : null)),'"></span>
		</a>
		<a title="',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['loginaddprofile'])) ? $in['titles']['loginaddprofile'] : null)),'" href="#" class="hidden-md hidden-lg" ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($in['pss']) && is_array($in['pss']) && isset($in['pss']['pssId'])) ? $in['pss']['pssId'] : null),'group'=>'void-link')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-useraccount-notloggedin';}),'>
			<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($in['icons']) && is_array($in['icons']) && isset($in['icons']['account'])) ? $in['icons']['account'] : null)),'"></span>
		</a>
		<div class="dropdown-menu" role="menu" id="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-menu-usernotloggedin">
			<div class="media">
				<div class="media-left">
					<div class="top-iconleft-container">
						',lcr5a2ad1517962craw($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['account-left'])) ? $in['titles']['account-left'] : null)),'
					</div>
				</div>
				<div class="media-body" id="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-menu-addcontent">
					<h3 class="media-heading">',lcr5a2ad1517962cencq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['account-right'])) ? $in['titles']['account-right'] : null)),'</h3>
',lcr5a2ad1517962chbbch($cx, 'withModule', array(array($in,'block-menu-usernotloggedin'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '						',lcr5a2ad1517962cencq($cx, lcr5a2ad1517962chbch($cx, 'enterModule', array(array($in),array()), 'encq', $in)),'
';}),'				</div>				
			</div>
		</div>
	</li>
',lcr5a2ad1517962chbbch($cx, 'withModule', array(array($in,'block-notifications'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '		<li class="dropdown nav-notifications nav-dropdown">
			<a href="#" class="dropdown-toggle" ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['pss']) && isset($cx['scopes'][count($cx['scopes'])-1]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-1]['pss']['pssId'] : null),'template'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['template'])) ? $cx['scopes'][count($cx['scopes'])-1]['template'] : null),'group'=>'notification-link')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'-notificationslink';}),' title="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['notifications'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['notifications'] : null)),'" ',lcr5a2ad1517962csec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['params']) && isset($cx['scopes'][count($cx['scopes'])-1]['params']['notifications-link'])) ? $cx['scopes'][count($cx['scopes'])-1]['params']['notifications-link'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo ' ',lcr5a2ad1517962cencq($cx, (isset($cx['sp_vars']['key']) ? $cx['sp_vars']['key'] : null)),'="',lcr5a2ad1517962cencq($cx, $in),'"';}),'>
				<span class="glyphicon ',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['icons']) && isset($cx['scopes'][count($cx['scopes'])-1]['icons']['notifications'])) ? $cx['scopes'][count($cx['scopes'])-1]['icons']['notifications'] : null)),'"></span>
				<span id="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['ids']['notifications-count'])) ? $cx['scopes'][count($cx['scopes'])-1]['ids']['notifications-count'] : null)),'" class="hidden ',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['notifications-count'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['notifications-count'] : null)),'" style="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['notifications-count'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['notifications-count'] : null)),'"></span>
			</a>
			<div class="dropdown-menu" role="menu">
				<div class="col-xs-12" id="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'-notifications">
					<div ',lcr5a2ad1517962chbbch($cx, 'generateId', array(array(),array('targetId'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['pss']) && isset($cx['scopes'][count($cx['scopes'])-1]['pss']['pssId'])) ? $cx['scopes'][count($cx['scopes'])-1]['pss']['pssId'] : null),'template'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['template'])) ? $cx['scopes'][count($cx['scopes'])-1]['template'] : null),'group'=>'notifications')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'';}),' class="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['notifications'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['notifications'] : null)),'" style="',lcr5a2ad1517962cencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['notifications'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['notifications'] : null)),'">
						',lcr5a2ad1517962cencq($cx, lcr5a2ad1517962chbch($cx, 'enterModule', array(array($in),array()), 'encq', $in)),'
					</div>
					<hr/>
					<div><span class="pull-right">',lcr5a2ad1517962craw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['viewallnotifications'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['viewallnotifications'] : null)),'</a></span></div>
				</div>
			</div>
		</li>
';}),'</ul>
<ul class="nav navbar-nav visiblesearch-visible" role="menu">
	<li class="nav-quicklink" id="',lcr5a2ad1517962cencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'-quicklink-everything">
',lcr5a2ad1517962chbbch($cx, 'withModule', array(array($in,'block-quicklink-everything'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '			',lcr5a2ad1517962cencq($cx, lcr5a2ad1517962chbch($cx, 'enterModule', array(array($in),array()), 'encq', $in)),'
';}),'	</li>
</ul>';return ob_get_clean();
};
?>