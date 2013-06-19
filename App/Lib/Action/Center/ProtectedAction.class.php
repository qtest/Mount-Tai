<?php

class ProtectedAction extends Action {
	public function _initialize() {
		if (isset ( $_SESSION ['cmp'] )) {
			// echo ("<script>top.location='" . U ( 'Index/' ) . "';</script>");
		} else { // 没有登录
			header("Location:".U ( '7788ysw/Index/index' ));
			//$this->redirect(U ( '7788ysw/Index/index' ),null,3,"请先登录");
		}
		return;
		$per = array ("module" => MODULE_NAME, "action" => ACTION_NAME );
		$pass = in_array ( $per, $_SESSION ['account'] ['permissions'] );
		//判断是否管理员，role_id=0为管理员
		if ($_SESSION ['account'] ['role_id'] == "0") {
			return;
		}
		if ($this->isAjax ()) {
			if (! $pass) {
				$this->ajaxReturn ( array ("info" => "权限不足！", "status" => "fail" ) );
			}
			if (! isset ( $_SESSION ['account'] )) {
				$this->ajaxReturn ( "fail", "请先登录！", 0 );
			}
		}
		if($this->isPost()){
			//如果没有登录
			if(!isset($_SESSION['account'])){
				$this->redirect("Index/Login",null,3,"请先登录");
			}
			//如果不存在权限
			if(!$pass){
				$this->error("没有权限！");
			}
		}
		if($this->isGet()){
			//如果没有登录
			if(!isset($_SESSION['account'])){
				$this->redirect("Index/Login",null,3,"请先登录");
			}
			//如果不存在权限
			if(!$pass){
				$this->error("没有权限！");
			}
		}else{
			if(!isset($_SESSION['account'])){
				$this->redirect("Index/Login",null,3,"请先登录");
			}
			//如果不存在权限
			if(!$pass){
				$this->error("没有权限！");
			}
		}
	
	}
}