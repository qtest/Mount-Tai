<?php
class IndexAction extends Action {
	public function __construct() {
		// import ( "@.Library.Class.PermissionCheck" );
		// PermissionCheck::checkIsLogin ();
	}
	
	public function index() {
		//$mm = new ShopCarModel ();
		//$ar = $mm->field ( "count(*) as ct" )->where ( "user_id = {$_SESSION['cmp']['id']}" )->find ();
		//var_dump($_SESSION['cmp']);exit();
		$odM = new OrderModel();
		$str = getMyOrderInfoStatus();
		$rs = $odM->field("count(*) AS total")->where("o_userId = {$_SESSION['cmp']['id']} AND o_status IN ({$str})")->find();
		$mm = new ViewProductModel ();
		$proArr = $mm->where ( "p_status = 1" )->order ( "p_index" )->select ();
		$maney = new UserManeyModel();
		$lastManey = $maney->where("user_id = {$_SESSION['cmp']['id']}")->find();
		//$this->assign ( "amount", $ar ['ct'] );
		$this->assign ( "lastManey", $lastManey['curr_maney']);
		$this->assign ( "proArr", $proArr );
		$this->assign ( "myOrder", $rs['total']);
		$this->display ();
	}
	
	public function getMyOrder(){
		$odM = new ViewOrderModel();// OrderModel();
		$str = getMyOrderInfoStatus();
		$idArr = $odM->field("order_id,o_status")->where("o_userId = {$_SESSION['cmp']['id']} AND o_status IN ({$str})")->select();
		import ( "@.Library.Class.Check" );
		$ofArr = array();
		foreach ( $idArr as $row ) {
			$rs = Check::getOrderFullInfoByOrderId($row['order_id']);
			$rs['statusInfo'] = Check::checkOrderStatus($row['o_status']);
			array_push ( $ofArr, $rs );
		}
		
		$this->assign("ofArr",$ofArr);
		$this->display('myOrder');
	}
	
	public function getMyManeyInfo(){
		$maney = new UserManeyModel();
		$rs = $maney->where("user_id = {$_SESSION['cmp']['id']}")->find();
		//echo '<table style="width:150px;"><tr><td>账户余额：</td><td>'.$rs['curr_maney'].'</td></tr></table>';
		$str = '<p style="padding:3px 20px;">账户余额：'.round($rs['curr_maney']).' 元</p>';
		$str .= '<p style="padding:3px 20px;">可用余额：'.round($rs['avai_maney']).' 元</p>';
		$str .= '<p style="padding:3px 20px;">定金比例：'.round($rs['front_maney_percent']).' %</p>';
		echo $str;
	}
	
	public function freshCarAmount() {
		$mm = new ShopCarModel ();
		$ar = $mm->field ( "count(*) as ct" )->where ( "user_id = {$_SESSION['cmp']['id']}" )->find ();
		echo $ar ['ct'];
	}
	/**
	 * 购物车物品列表
	 */
	public function myShoppingCar() {
		$mmCar = new ShopCarModel ();
		$ofM = new OfferModel ();
		$idArr = $mmCar->where ( "user_id = {$_SESSION['cmp']['id']}" )->select ();
		$ofArr = array ();
		$count = 0;
		foreach ( $idArr as $row ) {
			$attrM = new AttributeModel ();
			$rs = $ofM->where ( "id = {$row['offer_id']}" )->find ();
			$s = str_replace("_is", "", $rs['o_attr']);
			$attrArr = $attrM->where ( "id IN ({$s})" )->select ();
			$attrValue = explode ( ";", $rs ['o_attr_info'] );
			$attrInfo = "";
			for($i = 0; $i < count ( $attrArr ); $i ++) :
				$info = $attrArr [$i] ['a_name'] . " : " . $attrValue [$i];
				$attrInfo .= $attrInfo == "" ? $info : " " . $info;
			endfor;
			
			$procArr = explode ( ",", $rs ['o_process'] );
			$procValue = explode ( ";", $rs ['o_pro_attr'] );
			$processInfo = "";
			for($i = 0; $i < count ( $procArr ); $i ++) {
				$str = "";
				if ($procArr [$i] == $procValue [$i]) {
					$str = $procArr [$i];
				} else {
					$str = $procArr [$i] . "(" . $procValue [$i] . ")";
				}
				
				$processInfo .= $processInfo == "" ? $str : "," . $str;
			}
			
			$rs ['attrInfo'] = $attrInfo;
			$rs ['processInfo'] = $processInfo;
			//只显示文件名
			$fArr = explode("/", $row['o_fileName']);
			$rs ['o_fileName'] = $fArr[count($fArr)-1];
			$rs ['o_filePath'] = $row['o_fileName'];
			$count += $rs ['o_price'];
			array_push ( $ofArr, $rs );
		}
		// var_dump($ofArr);
		$this->assign ( "count", $count );
		$this->assign ( "ofArr", $ofArr );
		$this->display ();
	}
}