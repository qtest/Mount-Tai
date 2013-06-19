<?php
// 正度全张标准规格
function getZhengduSize() {
	return array (
			"length" => 1092,
			"width" => 787 
	);
}
// 大度全张标准规格
function getDaduSize() {
	return array (
			"length" => 1194,
			"width" => 889 
	);
}
// 不干胶全张标准规格
function getBgjSize() {
	return array (
			"length" => 7560,
			"width" => 5350 
	);
}

/**
 * 获取昨天的日期
 */
function getYesterday() {
	$yesTime = time () - 24 * 60 * 60;
	return array (
			"start" => date ( "Y-m-d 00:00:00", $yesTime ),
			"end" => date ( "Y-m-d 23:59:59", $yesTime ) 
	);
}
/**
 * 输出模块与动作的表单
 */
function printMAInput() {
	echo '<input type="hidden" name="m" value="' . MODULE_NAME . '" />' . "\n";
	echo '<input type="hidden" name="a" value="' . ACTION_NAME . '" />' . "\n";
}
/**
 * 输出模块与动作的ACTION
 */
function printMAAction() {
	return U ( MODULE_NAME . "/" . ACTION_NAME );
}
/**
 * 获取所有get
 *
 * @return array
 *
 */
function getGetVar() {
	$var = array ();
	$var ['m'] = MODULE_NAME;
	$var ['a'] = ACTION_NAME;
	foreach ( $_GET as $k => $v ) :
		if (! is_array ( $v ))
			$var [$k] = $v;
	endforeach
	;
	return $var;
}
/**
 * 保留两位小数
 *
 * @param unknown $num        	
 * @return string
 */
function getCheckNum4Float($num) {
	return sprintf ( "%.2f", $num );
}
/**
 * 获取当前月的第一天和最后一天
 *
 * @param string $y        	
 * @param string $m        	
 * @return multitype:number
 */
function mFristAndLast() {
	$firstday = date ( 'Y-m-01', strtotime ( date("Y-m-d") ) );
	$lastday = date ( 'Y-m-d', strtotime ( "$firstday +1 month -1 day" ) );
	return array (
			"firstday" => $firstday,
			"lastday" => $lastday 
	);
}
/**
 * 将时间格式字符串,转化为date
 *
 * @param 时间格式字符串 $str
 * @return string
 */
function strToDateTime($str){
	if(empty($str)){
		return "";
	}
	$tt = strtotime($str);
	if($tt){
		return date("Y年m月d日 H:i",$tt);
	}
	return "";
}
/**
 * 数字转中文大写
 * 
 * @param number $ns
 * @return mixed
 */
function numberToBigString($ns) {
	static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
	$cnyunits=array("元","角","分"),
	$grees=array("拾","佰","仟","万","拾","佰","仟","亿");
	list($ns1,$ns2)=explode(".",$ns,2);
	$ns2=array_filter(array($ns2[1],$ns2[0]));
	$ret=array_merge($ns2,array(implode("",_cny_map_unit(str_split($ns1),$grees)),""));
	$ret=implode("",array_reverse(_cny_map_unit($ret,$cnyunits)));
	return str_replace(array_keys($cnums),$cnums,$ret);
}

function _cny_map_unit($list,$units) {
	$ul=count($units);
	$xs=array();
	foreach (array_reverse($list) as $x) {
		$l=count($xs);
		if ($x!="0" || !($l%4)) $n=($x=='0'?'':$x).($units[($l-1)%$ul]);
		else $n=is_numeric($xs[0][0])?$x:'';
		array_unshift($xs,$n);
	}
	return $xs;
}
function getMyOrderInfoStatus(){
	return "'confirming','checking','check_fail','check_success','producing','produced','delivery'";//
}
/**
 * 6-20位 数字 字母 下划线
 * @param unknown $name
 * @return number
 */
function checkName($name){
	return preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]{5,20}$/u",$name);
}
/**
 * 6-20位 数字 字母
 * @param unknown $name
 * @return number
 */
function checkStr($str){
	return preg_match("/^[0-9a-zA-Z]{6,20}$/",$str);
}