<?php
/**
 * 系统权限检查类  2012-12-26 
 * 
 * @author 汤仁亮
 * 
 * @package WXPSI\Lib\Library\Class 
 * 
 * @version  2012-12-26 增加方法 checkIsLogin 判断是否已经登录
 */
class PermissionCheck {
	/**
	 * 检查是否已经登录
	 *
	 * 作者：汤仁亮
	 */
	public static function checkIsLogin() {
		if (isset ( $_SESSION ['cmp'] )) {
			// echo ("<script>top.location='" . U ( 'Index/' ) . "';</script>");
		} else { // 没有登录
			echo ("<script>top.location='" . U ( 'Index/index' ) . "';</script>");
		}
	}
	
	/**
	 * 检查是否以管理员身份登录
	 *
	 * 作者：汤仁亮
	 *
	 * @return boolean
	 */
	public static function checkIsLoginAsAdmin() {
		
		self::checkIsLogin ();
		
		if ($_SESSION ['cmp'] ['u_group'] == "1") {
			return true;
		} else { // 没有登录
			return false;
		}
	}
	/**
	 * 检查是否以管理员身份登录
	 *
	 * 作者：汤仁亮
	 *
	 * @return boolean
	 */
	public static function checkAdminMust() {
		self::checkIsLogin ();
		if (self::checkIsLoginAsAdmin ()) {
			return true;
		} else { // 没有登录
			echo ("<script>top.location='" . U ( 'Index/index' ) . "';</script>");
		}
	}

}