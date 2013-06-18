<?php
class CustomerAction extends Action {
	public function login() {
		if ($_COOKIE ['cmp_isrem'] == "true") {
			$this->assign ( "uid", base64_decode ( $_COOKIE ['cmp_name'] ) );
			// $this->assign ( "pwd", base64_decode ( $_COOKIE ['cmp_pwd'] ) );
			$this->assign ( "check", 'checked="true"' );
		}
		$this->display ();
	}
	
	/**
	 * 用户退出
	 */
	public function loginOut() {
		unset ( $_SESSION ['cmp'] );
		$this->ajaxReturn ( "success", "退出成功，正在转入页面...", 1 );
		// header ( "Location:/" );
	}
	public function loginCheck() {
		if (IS_POST) {
			if (empty ( $_POST ['login_name'] ) || empty ( $_POST ['login_pwd'] )) {
				$this->ajaxReturn ( "fail", "请输入用户名和密码！", 0 );
				exit ();
			}
			$manV = new ViewUserModel ();
			$login_name = addslashes ( $_POST ['login_name'] );
			$login_pwd = addslashes ( $_POST ['login_pwd'] );
			$rs = $manV->where ( "u_name='{$login_name}' AND u_status = 1" )->find ();
			if (is_array ( $rs )) {
				// 管理员组或审核之后的用户可登录
				if ($rs ['g_manager'] == 1 || $rs ['u_status'] == 1) {
					if (md5 ( $login_pwd ) == $rs ['u_pwd']) {
						$_SESSION ['cmp'] = $rs;
						/*
						 * $rp = new ViewRolePermissionsModel (); $role = new RoleModel (); //获取权限列表 $_SESSION ['wxpsi'] ['permissions'] = $rp->field ( "module,action" )->where ( "role_id={$_SESSION['wxpsi']['role_id']}" )->select (); //过期时间 1小时 因需要重新登录嫌麻烦 取消 //$_SESSION['wxpsi']['expires']=time()+60*60*1; //获取角色名称 $role_rs = $role->field ( "role_name" )->find ( $rs ['role_id'] ); //将角色名称赋值SESSION $_SESSION ['wxpsi'] ['role_name'] = $role_rs ["role_name"]; //更新登录次数 $data = array ("last_time" => date ( "Y/m/d H:i:s" ), "login_count" => $_SESSION ['wxpsi'] ['login_count'] + 1 ); $user->where ( "id={$_SESSION['wxpsi']['id']}" )->save ( $data );
						 */
						if ($_POST ['isremember'] == "true") {
							// 保存用户名密码
							setcookie ( "cmp_name", base64_encode ( $_POST ['login_name'] ), time () + 24 * 60 * 60 * 31, "/" );
							// setcookie ( "cmp_pwd", base64_encode ( $_POST ['login_pwd'] ), time () + 24 * 60 * 60 * 31, "/" );
							setcookie ( "cmp_isrem", "true", time () + 60 * 60 * 24 * 30 * 6 );
						} else {
							setcookie ( "cmp_name", NULL );
							setcookie ( "cmp_isrem", NULL );
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
				} else {
					$this->ajaxReturn ( "fail", "账户审核中，请稍候登录。", 0 );
				}
			} else {
				$this->ajaxReturn ( "fail", "用户名不存在！", 0 );
			}
		}
	}
	public function register() {
		/*
		 * if($.trim($("#company").val())==""){ Maya.Msg("请填写 <strong>公司名称</strong>"); return false; } if($.trim($("#manager").val())==""){ Maya.Msg("请填写 <strong>联系人姓名</strong>"); return false; } if($.trim($("#name").val())==""){ Maya.Msg("请填写 <strong>用户名</strong>"); return false; } if($.trim($("#pwd").val())=="" || $.trim($("#new_pwd").val())==""){ Maya.Msg("请填写 <strong>密码</strong> 或 <strong>重复密码</strong>"); return false; } /*if(isNaN(the.QQ.value)){ alert("你输入的QQ不是数字！"); the.QQ.focus(); return false; }
		 */
		if ($_POST) {
			if (empty ( $_POST ['name'] )) {
				$this->ajaxReturn ( "fail", "请输入<strong>用户名</strong>！", 0 );
			}
			if (empty ( $_POST ['pwd'] ) || empty ( $_POST ['new_pwd'] )) {
				$this->ajaxReturn ( "fail", "请输入<strong>密码</strong> 或 <strong>重复密码</strong>！", 0 );
			}
			if (checkName ( $_POST ['name'] ) !== 1) {
				$this->ajaxReturn ( "fail", "<strong>用户名</strong>应为5-20位，可以包含字母、数字、下划线！", 0 );
			}
			if (checkName ( $_POST ['pwd'] ) !== 1 || checkName ( $_POST ['new_pwd'] ) !== 1) {
				$this->ajaxReturn ( "fail", "<strong>密码</strong>应为6-20位，可以包含字母、数字！", 0 );
			}
			if (empty ( $_POST ['company'] )) {
				$this->ajaxReturn ( "fail", "请输入<strong>公司名称</strong>！", 0 );
			}
			if (empty ( $_POST ['manager'] )) {
				$this->ajaxReturn ( "fail", "请输入<strong>联系人姓名</strong>！", 0 );
			}
			if ($this->is_email ( $_POST ['email'] ) === false) {
				$this->ajaxReturn ( "fail", "填写的<strong>邮箱</strong>格式有误！", 0 );
			}
			
			if ($_POST ['pwd'] === $_POST ['new_pwd']) {
				$m = new ManagerModel ();
				// $data ['id'] = $_POST ['id'];
				$data ['u_name'] = $_POST ['name'];
				$data ['u_pwd'] = md5 ( $_POST ['new_pwd'] );
				$data ['u_province'] = $_POST ['province'];
				$data ['u_city'] = $_POST ['city'];
				$data ['u_company'] = $_POST ['company'];
				$data ['u_tel'] = $_POST ['tel'];
				$data ['u_manager'] = $_POST ['manager'];
				$data ['u_phone'] = $_POST ['phone'];
				$data ['u_address'] = $_POST ['addr'];
				$data ['u_email'] = $_POST ['email'];
				$data ['u_regDate'] = date ( 'Y-m-d H:i:s' );
				$m->create ( $data );
				if ($this->checkUser ( $data ['u_name'] ) === false) { // 如果用户不存在
					if ($m->add ()) {
						$this->ajaxReturn ( "success", "注册成功！正在跳转中....", 1 );
					} else {
						$this->ajaxReturn ( "fail", "注册失败！请联系管理员", 0 );
					}
				} else {
					$this->ajaxReturn ( "fail", "用户名已经被使用！", 0 );
				}
			} else {
				$this->ajaxReturn ( "fail", "两次输入的密码不一致！", 0 );
			}
		}
		$this->display ();
	}
	public function is_email($value) {
		return eregi ( '^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$', $value );
	}
	
	/**
	 * 验证用户是否存在
	 *
	 * @param 称呼 $name        	
	 * @return boolean 不存在 => false,存在 => true
	 */
	protected function checkUser($name) {
		$m = new ManagerModel ();
		$user = $m->where ( " u_name = '{$name}'" )->find ();
		// var_dump($user);
		// exit();
		return $user ['u_name'] == "" ? false : true;
	}
}