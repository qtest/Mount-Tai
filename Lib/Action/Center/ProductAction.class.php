<?php
class ProductAction extends ProtectedAction {
	public function __construct() {
		import ( "@.Library.Class.PermissionCheck" );
		PermissionCheck::checkIsLogin ();
	}
	public function getDataTreeData() {
		$mm = new ViewProductModel ();
		$proArr = $mm->where ( "p_status = 1 AND p_isDelete=0" )->order ( "p_index" )->select ();
		$res = array ();
		foreach ( $proArr as $row ) {
			$arr ['id'] = $row ['id'];
			$arr ['text'] = $row ['p_name'];
			array_push ( $res, $arr );
		}
		echo json_encode ( $res );
	}
	public function index() {
		$tmm = new TypeModel ();
		$typeArr = $tmm->where ( "t_parent = 2" )->select ();
		$cateArr = $tmm->where ( "t_parent = 1" )->select ();
		// $pcessM = new ProcessModel();
		// $pcessArr = $pcessM->select();
		$mm = new ViewProductModel ();
		$proArr = $mm->select ();
		$this->assign ( "typeArr", $typeArr );
		$this->assign ( "cateArr", $cateArr );
		// $this->assign("pcessArr",$pcessArr);
		$this->assign ( "proArr", $proArr );
		$this->display ();
	}
	public function getDataGridData() {
		$mm = new ViewProductModel ();
		$where = "p_isDelete=0";
		$where .= $_POST ['p_name'] == "" ? "" : " AND id={$_POST['p_name']}";
		$where .= $_POST ['cate_id'] == "" ? "" : " AND p_category = {$_POST['cate_id']}";
		$where .= $_POST ['type_id'] == "" ? "" : " AND p_type = {$_POST['type_id']}";
		// $where .= $_POST ['process_id'] == "" ? "" : " AND department_id =
		// {$_POST['dep_name']}";
		$where .= $_POST ['status'] == "" ? "" : " AND p_status = {$_POST['status']}";
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->order ( "p_name" )->limit ( "{$startP},{$endP}" )->select ();
		$res = array ();
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	public function postCheck() {
		$type = $_REQUEST ['type'];
		$mm = new ProductModel ();
		switch ($type) {
			case 'add' :
				if ($_POST) {
					$_POST ['p_status'] = $_POST ['p_status'] == 1 ? true : false;
					// $date = new DateTime();
					$_POST ['p_lastDate'] = date ( "Y-m-d H:i:s" );
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
					$_POST ['p_status'] = $_POST ['p_status'] == 1 ? true : false;
					$_POST ['p_lastDate'] = date ( "Y-m-d H:i:s" );
					$mm->create ( $_POST );
					if ($mm->save () > 0) {
						$this->ajaxReturn ( "success", "操作成功", 1 );
					} else {
						$this->ajaxReturn ( "fail", "操作失败", 0 );
					}
				}
				$rs = $mm->where ( "id = {$_REQUEST['id']}" )->find ();
				$this->assign ( "rs", $rs );
				break;
			case 'del' :
				$rs = $mm->where ( "id = '{$_REQUEST['id']}'" )->delete ();
				if ($rs > 0) {
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
		
		$umm = new UnitModel();
		$uArr = $umm->where("u_type = 1")->select();
		$this->assign ( "unitArr", $uArr );
		$this->assign ( "typeArr", $typeArr );
		$this->display ( "panel" );
	}
	
	public function getAttrView() {
		$attM = new AttributeModel ();
		$arr = $attM->where ( "parent_id = 0" )->select ();
		return $arr;
	}
	
	public function setAttr() {
		if ($_POST) {
			// var_dump($_POST);exit();
			$proAttrM = new ProductAttributeModel ();
			$proAttrM->startTrans (); // 启动事务操作
			$product_id = $_POST ['id'];
			$d = $proAttrM->where ( "pa_product = {$product_id}" )->delete ();
			if ($d === false) {
				$this->ajaxReturn ( "fail", "操作失败", 0 );
			}
			$strPro = $_POST ['pro'];
			if (! $this->checkProcess ( $product_id, $strPro )) {
				$proAttrM->rollback (); // 回滚
				$this->ajaxReturn ( "fail", "操作失败", 0 );
			}
			$idArr = $_POST ['ched'];
			$string = "";
			for($i = 0; $i < count ( $idArr ); $i ++) {
				$attrId = $idArr [$i];
				$attArr = $_POST ["{$attrId}"];
				$string = "";
				if (is_array ( $attArr )) {
					$string = implode ( ',', $attArr );
				}
				if ($attrId == 37) {
					if (! $this->checkPaper ( $product_id, $string )) {
						$proAttrM->rollback (); // 回滚
						$this->ajaxReturn ( "fail", "操作失败", 0 );
					}
				}
				// var_dump("attid:{$attrId} string:{$string}");
				$data ['pa_product'] = $product_id;
				$data ['pa_attribute'] = $attrId;
				$data ['pa_attr_attr'] = $string;
				$id = $proAttrM->add ( $data );
				if ($id === false) {
					$proAttrM->rollback (); // 回滚
					$this->ajaxReturn ( "fail", "操作失败", 0 );
				}
			}
			$proAttrM->commit (); // 提交
			$this->ajaxReturn ( "success", "操作成功！", 1 );
		}
		// $this->display ( 'Include:bgjAttr' );
		$attM = new ViewProductAttributeModel ();
		$arr = $attM->where ( "product_id = {$_GET['id']}" )->select ();
		$attM = new AttributeModel ();
		$attrArr = $attM->where ( "parent_id = 0" )->select ();
		$proM = new ProductProcessModel ();
		$proArr = $proM->where ( "pp_product = {$_GET['id']} AND pp_process = 0" )->find ();
		$proArr = $proArr['pp_process_attr'] == "" ? array() : explode(",", $proArr['pp_process_attr']);
		//var_dump($proM->getLastSql());exit();
		$this->assign ( "checArr", $arr );
		$this->assign ( "attrArr", $attrArr );
		$this->assign ( "proArr", $proArr );
		$this->display ( 'attr' );
	}
	/**
	 * 描述：处理后加工工序id字符串
	 *
	 * @param 项目id $product_id        	
	 * @param id字符串 $string        	
	 * @return boolean
	 */
	public function checkProcess($product_id, $strPro) {
		$mmPro = new ProductProcessModel ();
		$mmPro->startTrans ();
		$d = $mmPro->where ( "pp_product = {$product_id}" )->delete ();
		if ($d === false) {
			return false;
		}
		if ($strPro != "") {
			$data1 ['pp_product'] = $product_id;
			$data1 ['pp_process'] = 0;
			$data1 ['pp_process_attr'] = $strPro;
			$idd = $mmPro->add ( $data1 );
			if ($idd === false) {
				$mmPro->rollback (); // 回滚
				return false;
			}
		}
		$mm = new ProcessModel ();
		$proArr = $mm->where ( "id IN ({$strPro})" )->select ();
		foreach ( $proArr as $row ) {
			if ($row ['parent_id'] != 0) {
				$strPro .= "," . $row ['parent_id'];
			}
		}
		$idArr = array_unique ( explode ( ",", $strPro ) );
		$proTypeArr = $mm->where ( "parent_id = 0 AND id IN ({$strPro})" )->select ();
		foreach ( $proTypeArr as $row ) {
			$data ['pp_product'] = $product_id;
			$data ['pp_process'] = $row ['id'];
			$attP = $mm->where ( " parent_id = {$row['id']} AND id IN ({$strPro})" )->select ();
			$str = "";
			if (is_array ( $attP )) {
				for($i = 0; $i < count ( $attP ); $i ++) {
					$str .= $str == "" ? $attP [$i] ['id'] : "," . $attP [$i] ['id'];
				}
			}
			$data ['pp_process_attr'] = $str;
			// var_dump($data);
			$id = $mmPro->add ( $data );
			if ($id === false) {
				$mmPro->rollback (); // 回滚
				return false;
			}
		}
		$mmPro->commit ();
		return true;
	}
	/**
	 * 描述：将id字符串中缺少材料id的规格属性补全材料id，再查找材料id,遍历每个查到的数据，查找对应包含的规格属性
	 *
	 * @param 项目id $product_id        	
	 * @param id字符串 $string        	
	 * @return boolean
	 */
	public function checkPaper($product_id, $string) {
		$mmpp = new ProductPaperModel ();
		$mmpp->startTrans (); // 启动事务操作
		$d = $mmpp->where ( "pp_product = {$product_id}" )->delete ();
		if ($d === false) {
			return false;
		}
		$mm = new PaperModel ();
		$parentArr = $mm->where ( " id IN ({$string})" )->select ();
		foreach ( $parentArr as $row ) {
			if ($row ['parent_id'] != 0) {
				$string .= "," . $row ['parent_id'];
			}
		}
		$idArr = array_unique ( explode ( ",", $string ) );
		$papTypeArr = $mm->where ( "parent_id = 0 AND id IN ({$string})" )->select ();
		foreach ( $papTypeArr as $row ) {
			$data ['pp_product'] = $product_id;
			$data ['pp_paper'] = $row ['id'];
			$attP = $mm->where ( " parent_id = {$row['id']} AND id IN ({$string})" )->select ();
			$str = "";
			if (is_array ( $attP )) {
				for($i = 0; $i < count ( $attP ); $i ++) {
					$str .= $str == "" ? $attP [$i] ['id'] : "," . $attP [$i] ['id'];
				}
			}
			$data ['pp_paper_attr'] = $str;
			// var_dump($data);
			$id = $mmpp->add ( $data );
			if ($id === false) {
				$mmpp->rollback (); // 回滚
				return false;
			}
		}
		$mmpp->commit ();
		return true;
	}
	
	public function getAttributeArr() {
		$attM = new AttributeModel ();
		$arr = $attM->where ( "parent_id = {$_GET['id']}" )->select ();
		//echo $attM->getLastSql();
		echo json_encode ( $arr );
	}
}