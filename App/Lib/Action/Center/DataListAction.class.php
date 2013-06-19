<?php
class DataListAction extends ProtectedAction {
	public function __construct() {
		import ( "@.Library.Class.PermissionCheck" );
		PermissionCheck::checkIsLogin ();
	}
	public function index() {
		$this->display ();
	}
	public function getOfferListJson() {
		$mm = new OfferModel ();
		$where = "u_id = {$_SESSION['cmp']['id']}";
		$w_date = "";
		if ($_POST ['dstart']) {
			$dstart = $_POST ['dstart'] == "" ? "" : $_POST ['dstart'] . " 00:00:00";
			$dend = $_POST ['dend'] == "" ? "" : $_POST ['dend'] . " 23:59:59";
			$w_date = " AND (o_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND o_date >= '{$dstart}'";
			}
		}
		$where .= $w_date;
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->order ( 'o_date desc' )->limit ( "{$startP},{$endP}" )->select ();
		for($i = 0; $i < count ( $arr ); $i ++) {
			$arr [$i] ['o_perPrice'] = getCheckNum4Float ( $arr [$i] ['o_price'] / $arr [$i] ['o_amount'] );
			$arr [$i] ['o_date'] = date ( "Y-m-d H:i", strtotime ( $arr [$i] ['o_date'] ) );
		}
		$res = array ();
		// $count = count($arr);
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	public function offerList() {
		$this->display ();
	}
	public function orderList() {
		if ($_POST) {
			$mm = new OrderModel ();
			$data ['id'] = $_POST ['id'];
			$data ['o_status'] = "cancel";
			$data ['o_isDelete'] = 1;
			if ($mm->save ( $data ) !== false) {
				import ( "@.Library.Class.Check" );
				$rs = $mm->where("id = {$_POST ['id']}")->find();
				Check::checkOrderBackManey($rs['o_userId'], $rs['o_price'], $rs['o_rightPrice'],$_POST ['id']);
				Check::updateOrderStatusRecord ( $_POST ['id'], "confirming", $data ['o_status'] );
				$this->ajaxReturn ( "success", "订单取消成功。", 1 );
			} else {
				$this->ajaxReturn ( "fail", "订单取消失败。", 0 );
			}
		}
		import ( "@.Library.Class.Check" );
		$staArr = Check::getAllStatus();
		$this->assign ( "staArr", $staArr );
		$this->display ();
	}

	public function getOrderListJson() {
		$mm = new ViewOrderModel ();
		$where = "o_userId = {$_SESSION['cmp']['id']}";
		$w_date = "";
		if ($_POST ['dstart']) {
			$dstart = $_POST ['dstart'] == "" ? "" : $_POST ['dstart'] . " 00:00:00";
			$dend = $_POST ['dend'] == "" ? "" : $_POST ['dend'] . " 23:59:59";
			$w_date = " AND (o_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND o_date >= '{$dstart}'";
			}
		}
		$w_status = $_POST['status'] == "" ? "" : " AND o_status = '{$_POST['status']}'";
		$where .= $w_date.$w_status;
		// $count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->order('o_date desc')->limit ( "{$startP},{$endP}" )->select ();
		import ( "@.Library.Class.Check" );
		for($i = 0; $i < count ( $arr ); $i ++) {
			// var_dump(String::uuid());
			$arr [$i] ['statusInfo'] = Check::checkOrderStatus ( $arr [$i] ['o_status'] );
			$arr [$i] ['o_date'] = date ( "Y-m-d H:i", strtotime ( $arr [$i] ['o_date'] ) );
		}
	
		$res = array ();
		$count = count ( $arr );
		$res ['total'] = $count;
		$res ['rows'] = $count == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	public function showOffer() {
		// var_dump($_GET['id']);
		$mm = new OfferModel ();
		$attrM = new AttributeModel ();
		$rs = $mm->where ( "id = {$_GET['id']}" )->find ();
		
		$s = str_replace ( "_is", "", $rs ['o_attr'] );
		$attrArr = $attrM->where ( "id IN ({$s})" )->select ();
		// $attrArr = $attrM->where("id IN ({$rs['o_attr']})")->select();
		$attrValue = explode ( ";", $rs ['o_attr_info'] );
		
		$procArr = explode ( ",", $rs ['o_process'] );
		$procValue = explode ( ";", $rs ['o_pro_attr'] );
		$processInfo = "";
		for($i = 0; $i < count ( $procArr ); $i ++) {
			$str = "";
			if ($procArr [$i] == $procValue [$i]) {
				$str = $procArr [$i];
			} else {
				$str = $procArr [$i] . "[" . $procValue [$i] . "]";
			}
			
			$processInfo .= $processInfo == "" ? $str : " ,  " . $str;
		}
		$this->assign ( 'rs', $rs );
		$this->assign ( 'attrArr', $attrArr );
		$this->assign ( 'attrValue', $attrValue );
		$this->assign ( 'processInfo', $processInfo );
		$this->display ( 'offerPanel' );
	}
	
	public function showOrder() {
		$odM = new OrderModel ();
		import ( "@.Library.Class.Check" );
		$orderInfo = $odM->where ( "id = {$_GET['id']}" )->find ();
		$odfM = new OrderFullModel ();
		$rs = $odfM->where ( "order_id = {$_GET['id']}" )->find ();
		$orderInfo['statusInfo'] = Check::checkOrderStatus ( $orderInfo['o_status'] );
		if ($_POST) {
			if ($_FILES) {
				import ( "@.Library.Class.UploadFile" );
				$upload = new UploadFile (); // 实例化上传类
				$upload->maxSize = 1024 * 1024 * 10; // 设置附件上传大小
				$upload->allowExts = array (
						'rar',
						'zip' 
				); // 设置附件上传类型
				$upload->saveRule = ""; // 不启用重命名
				$upload->savePath = C ( 'ORDER_UPLOAD_PATH' ) . 'OD' . $_POST ['id'] . "/"; // 设置附件上传目录(每个订单都分配一个目录存放，防止有相同的文件名)
				if ($upload->upload ()) { // 上传成功 获取上传文件信息
					$info = $upload->getUploadFileInfo ();
				} else {
					// 上传错误提示错误信息
					$error = $upload->getErrorMsg () == "" ? "上传仅限于 *.rar,*.zip 格式，大小10M以内。" : $upload->getErrorMsg ();
					$this->ajaxReturn ( "fail", $error, 0 );
				}
			}
			$data ['id'] = $rs ['id']; // 报价项目详细信息ID
			$file = is_array($info) ? $info [0] ['savepath'] . $info [0] ['savename'] : ""; // 报价项目附件文件地址
			$data ['o_fileName'] = $file;
			$odfM->save ( $data );
			$odA ['id'] = $_POST ['id'];
			$odA ['o_desc'] = $_POST ['o_desc'];
			$d = $odM->save ( $odA );
			if ($d !== false) {
				// exit();
				$this->ajaxReturn ( "success", "提交成功。", 1 );
			}else{
				$this->ajaxReturn ( "fail", "提交失败。", 0 );
			}
		}
		// $mmCar = new ShopCarModel ();
		// $ofArr = array ();
		$row = Check::getOrderFullInfoByOrderId($_GET['id']);
		$this->assign ( "orderInfo", $orderInfo );
		$this->assign ( "row", $row );
		$this->display ( 'orderPanel' );
	}
}