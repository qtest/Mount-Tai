<?php
class PaperAction extends ProtectedAction {
	public function __construct() {
		import ( "@.Library.Class.PermissionCheck" );
		PermissionCheck::checkIsLogin ();
	}
	public function getAllPAPER4Json() {
		$mm = new ViewPaperModel ();
		$where = "m_isDelete = 0"; // AND type_id = 1
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->group ( 'm_name' )->order('type_id')->limit ( "{$startP},{$endP}" )->select ();
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	public function getPaper4TreeJson() {
		$mm = new PaperModel ();
		$arrMain = $mm->where ( "parent_id = 0 AND type_id = {$_GET['typeId']} AND m_status = 1 AND m_isDelete = 0" )->select ();
		$res = array ();
		if (is_array ( $arrMain )) {
			foreach ( $arrMain as $row ) {
				$arr = array ();
				$arr ['id'] = $row ['id'];
				$arr ['text'] = $row ['m_name'];
				$arrChild = $mm->where ( "parent_id = {$row['id']} AND type_id = {$_GET['typeId']} AND m_isDelete = 0" )->select ();
				if (is_array ( $arrChild )) {
					$arr ['state'] = "closed";
					// var_dump($arrChild);
					// var_dump($mm->getLastSql());
					$arrC = array ();
					foreach ( $arrChild as $val ) {
						$arrcc ['id'] = $val ['id'];
						$arrcc ['text'] = $val ['m_name'] . "克 [{$row['m_name']}]";
						array_push ( $arrC, $arrcc );
					}
					$arr ['children'] = $arrC;
				}
				array_push ( $res, $arr );
			}
		}
		echo json_encode ( $res );
	}
	public function index() {
		$this->display ();
	}
	public function actionCheckIndex() {
		$type = $_REQUEST ['type'];
		// echo $type;exit;
		// var_dump($_POST);exit();
		$mm = new PaperModel ();
		switch ($type) {
			case 'add' :
				if ($_POST) {
					$where = $_POST ['parent_id'] == "" ? "" : " AND parent_id = {$_POST['parent_id']}";
					$rs = $mm->where ( "m_name = '{$_POST['m_name']}' {$where}" )->limit ( 1 )->find ();
					// var_dump($op->getLastSql());
					// exit();
					if ($rs ['m_name'] != "") {
						$this->ajaxReturn ( "fail", "名称已经存在！", 0 );
					}
					$_POST ['m_status'] = $_POST ['m_status'] == 1 ? true : false;
					if ($_POST ['parent_id'] != "") {
						$_POST ['m_status'] = 1;
					}
					//$_POST ['type_id'] = 1;
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
					$where = $_POST ['parent_id'] == "" ? "" : " AND parent_id = {$_POST['parent_id']}";
					$rs = $mm->where ( "m_name = '{$_POST['m_name']}' AND id <> {$_POST['id']} {$where}" )->limit ( 1 )->find ();
					// var_dump($mm->getLastSql());
					// exit();
					if ($rs ['m_name'] != "") {
						$this->ajaxReturn ( "fail", "名称已经存在！", 0 );
					}
					$_POST ['m_status'] = $_POST ['m_status'] == 1 ? true : false;
					//$_POST ['type_id'] = 1;
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
				// var_dump($data);exit;
				$mm->create ( $data );
				if ($mm->save () > 0) {
					$this->ajaxReturn ( "success", "删除成功！", 1 );
				} else {
					$this->ajaxReturn ( "fail", "操作失败", 0 );
				}
				break;
			default :
				$this->ajaxReturn ( 'fail', '找不到方法！', 0 );
				break;
		}
		$tmm = new TypeModel ();
		$typeArr = $tmm->where ( "t_parent = 2" )->select ();
		$this->assign ( "typeArr", $typeArr );
		//$this->assign ( "type_id", 1 );
		$this->display ( 'panel' );
	}
	public function setAttr() {
		if ($_POST) {
			/*
			 * array(4) { ["parent_id"]=> string(2) "29" ["id"]=> array(2) { [0]=> string(2) "30" [1]=> string(2) "31" } ["price"]=> array(2) { [0]=> string(7) "1234.00" [1]=> string(8) "12352.00" } ["ched"]=> array(1) { [0]=> string(2) "31" } }
			 */
			// var_dump($_POST);exit();
			
			$idArr = $_POST ['id'];
			$priceArr = $_POST ['price'];
			$dPriceArr = $_POST ['dPrice'];
			$cheArr = $_POST ['ched'];
			// var_dump($_POST);exit();
			$mm = new PaperModel ();
			for($i = 0; $i < count ( $idArr ); $i ++) {
				$status = "";
				$data ['id'] = $idArr [$i];
				$data ['m_price'] = $priceArr [$i];
				$data ['m_dPrice'] = $dPriceArr [$i];
				// 判断是否已选中启用
				if (in_array ( $idArr [$i], $cheArr )) {
					// $status = " AND m_status = 1";
					$data ['m_status'] = 1;
				} else {
					$data ['m_status'] = 0;
				}
				$c = $mm->save ( $data );
				if ($c === false) {
					$this->ajaxReturn ( "fail", "操作失败", 0 );
					break;
				}
			}
			$this->ajaxReturn ( "success", "操作成功！", 1 );
		}
		
		$mm = new ViewPaperAttrModel ();
		$papArr = $mm->where ( "P1.parent_id = {$_GET['id']} AND P1.m_isDelete = 0" )->order ( 'm_name' )->select ();
		// var_dump($mm->getLastSql());exit();
		$this->assign ( "papArr", $papArr );
		$this->display ( 'attr' );
	}
}