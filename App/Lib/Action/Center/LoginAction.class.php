<?php
class LoginAction extends ProtectedAction {
	public function index() {
		if ($_COOKIE ['cmp_isrem'] == "true") {
			$this->assign ( "uid", base64_decode ( $_COOKIE ['cmp_name'] ) );
			$this->assign ( "pwd", base64_decode ( $_COOKIE ['cmp_pwd'] ) );
			$this->assign ( "check", 'checked="true"' );
		}
		$this->display ();
		// $this->display ( 'panel' );
	}
	public function panel() {
		if ($_COOKIE ['cmp_isrem'] == "true") {
			$this->assign ( "uid", base64_decode ( $_COOKIE ['cmp_name'] ) );
			$this->assign ( "pwd", base64_decode ( $_COOKIE ['cmp_pwd'] ) );
			$this->assign ( "check", 'checked="true"' );
		}
		$this->display ();
		// $this->display ( 'panel' );
	}
	
	/**
	 * 用户退出
	 */
	public function loginOut() {
		unset ( $_SESSION ['cmp'] );
		$this->ajaxReturn ( "success", "退出成功，正在转入页面...", 1 );
		// header ( "Location:/" );
	}
	public function login() {
	}
	public function loginCheck() {
		$manV = new ViewUserModel ();
		$login_name = addslashes ( $_POST ['login_name'] );
		$login_pwd = addslashes ( $_POST ['login_pwd'] );
		$rs = $manV->where ( "u_name='{$login_name}' AND u_status = 1" )->find ();
		if (is_array ( $rs )) {
			//管理员组或审核之后的用户可登录
			if ($rs ['g_manager'] == 1 || $rs ['u_allow'] == 1) {
				if (md5 ( $login_pwd ) == $rs ['u_pwd']) {
					$_SESSION ['cmp'] = $rs;
					/*
					 * $rp = new ViewRolePermissionsModel (); $role = new RoleModel (); //获取权限列表 $_SESSION ['wxpsi'] ['permissions'] = $rp->field ( "module,action" )->where ( "role_id={$_SESSION['wxpsi']['role_id']}" )->select (); //过期时间 1小时 因需要重新登录嫌麻烦 取消 //$_SESSION['wxpsi']['expires']=time()+60*60*1; //获取角色名称 $role_rs = $role->field ( "role_name" )->find ( $rs ['role_id'] ); //将角色名称赋值SESSION $_SESSION ['wxpsi'] ['role_name'] = $role_rs ["role_name"]; //更新登录次数 $data = array ("last_time" => date ( "Y/m/d H:i:s" ), "login_count" => $_SESSION ['wxpsi'] ['login_count'] + 1 ); $user->where ( "id={$_SESSION['wxpsi']['id']}" )->save ( $data );
					 */
					if ($_POST ['isremember'] == "true") {
						// 保存用户名密码
						setcookie ( "cmp_name", base64_encode ( $_POST ['login_name'] ), time () + 24 * 60 * 60 * 31, "/" );
						setcookie ( "cmp_pwd", base64_encode ( $_POST ['login_pwd'] ), time () + 24 * 60 * 60 * 31, "/" );
						setcookie ( "cmp_isrem", "true", time () + 60 * 60 * 24 * 30 * 6 );
					}
					// sdvvd
					$user = new ManagerModel ();
					$arr = array ();
					$arr ["id"] = $rs ['id'];
					$arr ["u_logTime"] = date ( 'Y-m-d H:i:s' );
					$user->save ( $arr );
					$this->ajaxReturn ( "success", "登录成功，正在转入页面...", 1 );
				} else {
					$this->ajaxReturn ( "fail", "密码错误！", 0 );
				}
			}else{
				$this->ajaxReturn ( "fail", "账户审核中，请稍候登录。", 0 );
			}
		} else {
			$this->ajaxReturn ( "fail", "用户名不存在！", 0 );
		}
	}
}