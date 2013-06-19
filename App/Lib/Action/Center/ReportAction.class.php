<?php
class ReportAction extends ProtectedAction {
	public function __construct(){
		import ( "@.Library.Class.PermissionCheck" );
		PermissionCheck::checkIsLogin ();
	}
	public function index() {
		$this->display ();
	}
	public function offerList() {
		$this->display ();
	}
	public function orderList() {
		$this->display ();
	}
	
	public function showOffer(){
		$this->display('offerPanel');
	}
	
	public function showOrder(){
		$odM = new OrderModel ();
		import ( "@.Library.Class.Check" );
		$orderInfo = $odM->where ( "id = {$_GET['id']}" )->find ();
		$odfM = new OrderFullModel ();
		$rs = $odfM->where ( "order_id = {$_GET['id']}" )->find ();
		$orderInfo['statusInfo'] = Check::checkOrderStatus ( $orderInfo['o_status'] );
		
		// $mmCar = new ShopCarModel ();
		// $ofArr = array ();
		$row = Check::getOrderFullInfoByOrderId($_GET['id']);
		$this->assign ( "orderInfo", $orderInfo );
		$this->assign ( "row", $row );
		$this->display ( 'orderPanel' );
	}
	
	public function orderInfo() {
		$mm = new OfferModel();
		$attrM = new AttributeModel();
		$rs = $mm->where("id = {$_GET['id']}")->find();
	
		$attrArr = $attrM->where("id IN ({$rs['o_attr']})")->select();
		$attrValue = explode(";", $rs['o_attr_info']);
	
		$procArr = explode(",", $rs['o_process']);
		$procValue = explode(";", $rs['o_pro_attr']);
		$processInfo = "";
		for($i=0;$i < count($procArr);$i++){
			$str = "";
			if($procArr[$i] == $procValue[$i]){
				$str = $procArr[$i];
			}else{
				$str = $procArr[$i]."(".$procValue[$i].")";
			}
				
			$processInfo .= $processInfo == "" ? $str : ",".$str;
		}
		$this->assign('rs',$rs);
		$this->assign('attrArr',$attrArr);
		$this->assign('attrValue',$attrValue);
		$this->assign('processInfo',$processInfo);
		$this->display ();
	}
	

	/**
	 * 资金变动记录
	 */
	public function record() {
		import ( "@.Library.Class.PageNormal" );
		$where = "1 = 1 AND u_id = {$_SESSION['cmp']['id']}";
		$w_date = "";
		if ($_GET ['dstart']) {
			$dstart = $_GET ['dstart'] == "" ? "" : $_GET ['dstart'] . " 00:00:00";
			$dend = $_GET ['dend'] == "" ? "" : $_GET ['dend'] . " 23:59:59";
			$w_date = " AND (r_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND r_date >= '{$dstart}'";
			}
		}
		$mm = new ViewRecordModel ();
		$where .= $w_date;
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_GET ['page'] ) ? $_GET ['page'] : 1;
		$pageParam = getGetVar ();
		$page = new PageNormal ( $count ['total'], $pageNum, 10, $pageParam );
		$arr = $mm->field ( "id,o_id,r_income,r_pay,DATE_FORMAT(r_date,'%Y-%m-%d %H:%i') as r_date,r_overage,u_id,u_name,u_company,u_province,u_city,r_type,r_desc" )->where ( $where )->order ( 'r_date desc' )->limit ( $page->P ['start'] . "," . $page->P ['end'] )->select ();
		// var_dump($mm->getLastSql());
		// var_dump($arr);
		// exit();
		$this->assign ( "recordArr", $arr );
		$this->assign ( "page", $page );
		$this->display ();
	}
	/**
	 * 资金变动记录明细
	 */
	public function showInfo() {
		if ($_GET ['type'] == 0) {
			$ch = new RecordModel ();
			$rsCh = $ch->field ( "o_id" )->where ( "id = {$_GET['id']}" )->find ();
			if ($rsCh ["o_id"] != "") {
				//$mm = new OrderModel ();
				//$rs = $mm->where ( "id = {$rsCh["o_id"]}" )->find ();
				$oid = $rsCh["o_id"];
				$odM = new OrderModel ();
				import ( "@.Library.Class.Check" );
				$orderInfo = $odM->where ( "id = {$oid}" )->find ();
				$odfM = new OrderFullModel ();
				$rs = $odfM->where ( "order_id = {$oid}" )->find ();
				$orderInfo['statusInfo'] = Check::checkOrderStatus ( $orderInfo['o_status'] );
				
				// $mmCar = new ShopCarModel ();
				// $ofArr = array ();
				$row = Check::getOrderFullInfoByOrderId($oid);
				$this->assign ( "orderInfo", $orderInfo );
				$this->assign ( "row", $row );
				$this->display ( 'orderPanel' );
				exit();
			}
		} else {
			$ch = new RecordModel ();
			$rsCh = $ch->field ( "re_id" )->where ( "id = {$_GET['id']}" )->find ();
			$mm = new ViewReChargeModel ();
			$rs = $mm->where ( "R.id = {$rsCh["re_id"]}" )->find ();
			// var_dump($mm->getLastSql());
			// exit();
			$this->assign ( "rs", $rs );
			$this->display ( 'showCharge' );
			exit();
		}
		//$this->assign ( "rs", $rs );
		//$this->display ();
	}
	
}