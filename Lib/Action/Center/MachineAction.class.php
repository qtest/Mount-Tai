<?php
class MachineAction extends ProtectedAction {
	public function __construct() {
		import ( "@.Library.Class.PermissionCheck" );
		PermissionCheck::checkIsLogin ();
	}
	public function index() {
		$this->display ();
	}
	
	public function getAllMC4Json() {
		$mm = new MachineModel ();
		$count = $mm->field ( "COUNT(*) AS total" )->where("m_isDelete = 0")->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where("m_isDelete = 0")->limit ( "{$startP},{$endP}" )->select ();
		$res ['total'] = $count ['total'];
		$res ['rows'] =  $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	
	public function actionCheckIndex() {
		$type = $_REQUEST ['type'];
		// echo $type;exit;
		// var_dump($_POST);exit();
		$mm = new MachineModel ();
		switch ($type) {
			case 'add' :
				if ($_POST) {
					//$where = $_POST ['parent_id'] == "" ? "" : " AND parent_id = {$_POST['parent_id']}";
					$rs = $mm->where ( "m_name = '{$_POST['m_name']}'" )->limit ( 1 )->find ();
					// var_dump($op->getLastSql());
					// exit();
					if ($rs ['m_name'] != "") {
						$this->ajaxReturn ( "fail", "名称已经存在！", 0 );
					}
					$_POST ['m_status'] = $_POST ['m_status'] == 1 ? true : false;
					$_POST ['m_lastDate'] = date ( 'Y-m-d H:i:s' );
					$mm->create ( $_POST );
					if ($mm->add () > 0) {
						$this->ajaxReturn ( "success", "操作成功", 1 );
					} else {
						$this->ajaxReturn ( "fail", "操作失败", 0 );
					}
				}
				break;
			case 'edit' :
				if ($_POST) {
					$rs = $mm->where ( "m_name = '{$_POST['m_name']}' AND id <> {$_POST['id']}" )->limit ( 1 )->find ();
					// var_dump($op->getLastSql());
					// exit();
					if ($rs ['m_name'] != "") {
						$this->ajaxReturn ( "fail", "名称已经存在！", 0 );
					}
					$_POST ['m_status'] = $_POST ['m_status'] == 1 ? true : false;
					$_POST ['m_lastDate'] = date ( 'Y-m-d H:i:s' );
					$mm->create ( $_POST );
					if ($mm->save () > 0) {
						// var_dump($mm->getLastSql());exit;
						$this->ajaxReturn ( "success", "操作成功", 1 );
					} else {
						$this->ajaxReturn ( "fail", "操作失败", 0 );
					}
				}
				$rs = $mm->where ( "id = {$_REQUEST['id']}" )->find ();
				$this->assign ( "rs", $rs );
				break;
			case 'del' :
				// $rs = $mm->where ( "id = '{$_REQUEST['id']}'" )->delete ();
				$data ['id'] = $_REQUEST ['id'];
				$data ['m_isDelete'] = true;
				
				$mm->create ( $data );
				if ($mm->save () !== false) {
					//var_dump($mm->getLastSql());exit;
					$this->ajaxReturn ( "success", "删除成功！", 1 );
				} else {
					$this->ajaxReturn ( "fail", "操作失败", 0 );
				}
				break;
			default :
				$this->ajaxReturn ( 'fail', '找不到方法！', 0 );
				break;
		}
		$this->display ( 'panel' );
	}
}