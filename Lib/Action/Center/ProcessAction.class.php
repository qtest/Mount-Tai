<?php
class ProcessAction extends ProtectedAction{
	public function __construct() {
		import ( "@.Library.Class.PermissionCheck" );
		PermissionCheck::checkIsLogin ();
	}
	public function getAll4Json(){
		$mm = new ViewProcessModel();
		$where .= "parent_id = 0 AND p_isDelete = 0";
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->limit ( "{$startP},{$endP}" )->select ();
		$res = array ();
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	
	public function getPro4TreeJson() {
		$mm = new ProcessModel ();
		$arrMain = $mm->where ( "parent_id = 0 AND p_status = 1 AND p_isDelete = 0" )->select ();
		//var_dump($arrMain);
		$res = array();
		if (is_array ( $arrMain )) {
			foreach ( $arrMain as $row ) {
				$arr = array();
				$arr['id'] = $row['id'];
				$arr['text'] = $row['p_name'];
				$arrChild = $mm->where("parent_id = {$row['id']} AND p_status = 1 AND p_isDelete = 0")->select();
				if(is_array($arrChild)){
					$arr['state'] = "closed";
					//var_dump($arrChild);
					//var_dump($mm->getLastSql());
					$arrC = array();
					foreach ($arrChild as $val){
						$arrcc['id'] = $val['id'];
						$arrcc['text'] = $val['p_name'];
						$arrcc['attributes'] = $row['id'];
						array_push($arrC, $arrcc);
					}
					$arr['children'] = $arrC;
				}
				array_push($res, $arr);
			}
		}
		echo json_encode ( $res );
	}
	
	public function index(){
		$this->display();
	}
	
	public function actionCheckIndex() {
		$type = $_REQUEST ['type'];
		// echo $type;exit;
		// var_dump($_POST);exit();
		$mm = new ProcessModel();
		switch ($type) {
			case 'add' :
				if ($_POST) {
					$where = $_POST ['parent_id'] == "" ? "" : " AND parent_id = {$_POST['parent_id']}";
					$rs = $mm->where ( "p_name = '{$_POST['p_name']}' {$where}" )->limit ( 1 )->find ();
					// var_dump($op->getLastSql());
					// exit();
					if ($rs ['p_name'] != "") {
						$this->ajaxReturn ( "fail", "名称已经存在！", 0 );
					}
					$_POST ['p_status'] = $_POST ['p_status'] == 1 ? true : false;
					if ($_POST ['parent_id'] != "") {
						$_POST ['p_status'] = 1;
					}
					//$_POST ['type_id'] = 1;
					$_POST ['p_lastDate'] = date ( 'Y-m-d H:i:s' );
					$mm->create ( $_POST );
					if ($mm->add () > 0) {
						$this->ajaxReturn ( "success", "操作成功", 1 );
					} else {
						$this->ajaxReturn ( "fail", "操作失败", 0 );
					}
				}
				//$this->assign ( "type_id", 1 );
				$this->display ( 'panel' );
				break;
			case 'edit' :
				if ($_POST) {
					$where = $_POST ['parent_id'] == "" ? "" : " AND parent_id = {$_POST['parent_id']}";
					$rs = $mm->where ( "p_name = '{$_POST['p_name']}' AND id <> {$_POST['id']} {$where}" )->limit ( 1 )->find ();
					// var_dump($op->getLastSql());
					// exit();
					if ($rs ['p_name'] != "") {
						$this->ajaxReturn ( "fail", "名称已经存在！", 0 );
					}
					$_POST ['p_status'] = $_POST ['p_status'] == 1 ? true : false;
					$_POST ['sizeDIY'] = $_POST ['sizeDIY'] == 1 ? true : false;
					$_POST ['numDIY'] = $_POST ['numDIY'] == 1 ? true : false;
					$_POST ['ismust'] = $_POST ['ismust'] == 1 ? true : false;
					//$_POST ['type_id'] = 1;
					$_POST ['p_lastDate'] = date ( 'Y-m-d H:i:s' );
					$mm->create ( $_POST );
					if ($mm->save () > 0) {
						//var_dump($mm->getLastSql());exit;
						$this->ajaxReturn ( "success", "操作成功", 1 );
					} else {
						$this->ajaxReturn ( "fail", "操作失败", 0 );
					}
				}
				$rs = $mm->where ( "id = {$_REQUEST['id']}" )->find ();
				//$this->assign ( "type_id", 1 );
				$this->assign ( "rs", $rs );
				$this->display ( 'panel' );
				break;
			case 'del' :
				// $rs = $mm->where ( "id = '{$_REQUEST['id']}'" )->delete ();
				$data ['id'] = $_REQUEST ['id'];
				$data ['p_isDelete'] = true;
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
	}
	
	public function setAttr() {
		if ($_POST) {
			$idArr = $_POST ['id'];
			$priceArr = $_POST ['price'];
			$cheArr = $_POST ['ched'];
			// var_dump($_POST);exit();
			$mm = new ProcessModel();
			for($i = 0; $i < count ( $idArr ); $i ++) {
				$status = "";
				$data ['id'] = $idArr [$i];
				$data ['p_price'] = $priceArr [$i];
				// 判断是否已选中启用
				if (in_array ( $idArr [$i], $cheArr )) {
					// $status = " AND m_status = 1";
					$data ['p_status'] = 1;
				} else {
					$data ['p_status'] = 0;
				}
				$c = $mm->save ( $data );
				if ($c === false) {
					$this->ajaxReturn ( "fail", "操作失败", 0 );
					break;
				}
			}
			$this->ajaxReturn ( "success", "操作成功！", 1 );
		}
	
		$mm = new ViewProcessAttrModel ();
		$proArr = $mm->where ( "P1.parent_id = {$_GET['id']} AND P1.p_isDelete = 0" )->order ( 'p_name' )->select ();
		// var_dump($mm->getLastSql());exit();
		$this->assign ( "proArr", $proArr );
		$this->display ( 'attr' );
	}
}