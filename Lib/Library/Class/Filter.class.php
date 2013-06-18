<?php
//================================
//
//       功能：过滤非法字符
//       版本：1.0
//       作者：武仝
//       日期：2012-09-03
//       更新记录：
//       V1.0   2011-04-11 增加HTML标签的替换
//
//================================
class Filter{
	static function HtmlTag($str=""){
		$replaceArr=array(
			'/<.+?>/',
		);
		$str=strip_tags($str);
		return preg_replace($replaceArr,"",$str);
	}	
}
//echo Filter::HtmlTag("<div class=\"left\">何''处'得知此展'''览：'</div>");