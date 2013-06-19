<?php
class ServiceAction extends ProtectedAction {
	public function index() {
		$mmB = new BankInfoModel ();
		if ($_POST) {
			$_POST['b_status'] = $_POST['b_status'] == 'true' ? 1 : 0;
			//var_dump($_POST);exit();
			if ($mmB->save ( $_POST ) !== false) {
				$this->ajaxReturn ( "success", "操作成功", 1 );
			} else {
				$this->ajaxReturn ( "fail", "操作失败", 0 );
			}
		}
		$bankArr = $mmB->select ();
		// $mmT = new TransportModel();
		// $transArr = $mmT->where("")->select();
		$this->assign ( "bankArr", $bankArr );
		$this->display ();
	}
	
	public function checkBank() {
		$type=$_REQUEST['type'];
		$mmB = new BankInfoModel ();
		switch ($type){
			case 'add':
				if ($mmB->add ( $_POST ) !== false) {
					$this->ajaxReturn ( "success", "操作成功", 1 );
				} else {
					$this->ajaxReturn ( "fail", "操作失败", 0 );
				}
				break;
			case 'edit':
				if ($mmB->save ( $_POST ) !== false) {
					$this->ajaxReturn ( "success", "操作成功", 1 );
				} else {
					$this->ajaxReturn ( "fail", "操作失败", 0 );
				}
				break;
			default:
				break;
		}
	}
	public function checkTtrans() {
		
	}
}