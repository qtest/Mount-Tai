<?php
class InvoiceManagerAction extends ProtectedAction {
	private function getUsers() {
		$mm = new ViewUserModel ();
		//$Uarr = $mm->where ( "G.g_manager = 0" )->group ( "M.id" )->select (); // ->where("G.g_manager = 0")
		$Uarr = $mm->select (); // ->where("G.g_manager = 0")
		return $Uarr;
	}
	
	private function getProducts() {
		$mm = new ProductModel();
		$Parr = $mm->select (); // ->where("G.g_manager = 0")
		return $Parr;
	}
	
	public function index() {
		import ( "@.Library.Class.Check" );
		if ($_POST) {
			$mm = new OrderModel ();
			$data ['id'] = $_POST ['id'];
			$data ['o_status'] = "checking";
			$info = "确认";
			if ($_POST ['type'] == "cel") {
				$data ['o_status'] = "cancel";
				$data ['o_isDelete'] = 1;
				$info = "取消";
			}
			if ($mm->save ( $data ) !== false) {
				Check::updateOrderStatusRecord ( $_POST ['id'], "confirming", $data ['o_status'] );
				if($_POST['type'] == "cel"){
					$rs = $mm->where("id = {$_POST ['id']}")->find();
					Check::checkOrderBackManey($rs['o_userId'], $rs['o_price'], $rs['o_rightPrice'],$_POST ['id']);
				}
				$this->ajaxReturn ( "success", "订单" . $info . "成功。", 1 );
			} else {
				$this->ajaxReturn ( "fail", "订单" . $info . "失败。", 0 );
			}
		}
		$staArr = Check::getAllStatus ();
		$Uarr = $this->getUsers ();
		$this->assign ( "staArr", $staArr );
		$this->assign ( "Uarr", $Uarr );
		$this->display ();
	}
	/**
	 * 订单审核页面，包含所有订单
	 */
	public function getOrderListJson() {
		import ( "@.Library.Class.Check" );
		$mm = new ViewOrderModel ();
		$where = "1=1"; // ->where("g_manager = 0")
		$dstart = date ( "Y-m-d", strtotime ( "-30 day" ) );
		$w_date = " AND o_date >= '{$dstart}'";
		if ($_POST ['dstart']) {
			$dstart = $_POST ['dstart'] == "" ? "" : $_POST ['dstart'] . " 00:00:00";
			$dend = $_POST ['dend'] == "" ? "" : $_POST ['dend'] . " 23:59:59";
			$w_date = " AND (o_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND o_date >= '{$dstart}'";
			}
		}
		$staStr = Check::getStatusList();
		$w_type = $_POST['type'] == '' ? "" : " AND o_status IN ({$staStr})";
		$w_status = $_POST ['status'] == "" ? "" : " AND o_status = '{$_POST['status']}'";
		$w_user = $_POST ['uName'] == "" ? "" : " AND o_userId = '{$_POST ['uName']}'";
		$where .= $w_date . $w_user . $w_status.$w_type;
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->order ( 'o_date desc' )->limit ( "{$startP},{$endP}" )->select ();
		for($i = 0; $i < count ( $arr ); $i ++) {
			$arr [$i] ['userInfo'] = $arr [$i] ['u_manager'] . " (" . $arr [$i] ['g_name'] . ")";
			$arr [$i] ['status'] = Check::checkOrderStatus ( $arr [$i] ['o_status'] );
			// $arr [$i] ['o_status'] = Check::checkOrderStatus ( $arr [$i] ['o_status'] );
			// $arr [$i] ['statusInfo'] = Check::checkOrderStatus ( $arr [$i] ['o_status'] );
			$arr [$i] ['o_date'] = strToDateTime ( $arr [$i] ['o_date'] ); // date ( "Y-m-d H:i", strtotime ( $arr [$i] ['o_date'] ) );
		}
		$res = array ();
		// $count = count ( $arr );
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	/**
	 * 详细信息查看、订单审核提交
	 */
	public function showOrder() {
		import ( "@.Library.Class.Check" );
		if ($_POST) {
			$mm = new OrderModel ();
			$data ['id'] = $_POST ['id'];
			$data ['o_status'] = "check_success";
			if ($_POST ['type'] == 0) {
				$data ['o_status'] = "check_fail";
			}
			if ($mm->save ( $data ) !== false) {
				Check::updateOrderStatusRecord ( $_POST ['id'], "checking", $data ['o_status'] );
				$this->ajaxReturn ( "success", "订单审核成功。", 1 );
			} else {
				$this->ajaxReturn ( "fail", "订单审核失败。", 0 );
			}
		}
		$odM = new OrderModel ();
		$orderInfo = $odM->where ( "id = {$_GET['id']}" )->find ();
		$orderInfo ['statusInfo'] = Check::checkOrderStatus ( $orderInfo ['o_status'] );
		// /////////////////////////////////////////////
		$row = Check::getOrderFullInfoByOrderId ( $_GET ['id'] );
		// /////////////////////////////////////////////
		// var_dump($ofArr);
		$this->assign ( "orderInfo", $orderInfo );
		// $this->assign ( "count", $count );
		$this->assign ( "row", $row );
		$this->display ( 'orderPanel' );
	}
	
	/**
	 * 加工单打印页面，包含已审核通过的订单
	 */
	public function produceingLabelList() {
		import ( "@.Library.Class.Check" );
		$staArr = Check::getProduceStatus ();
		$Uarr = $this->getUsers ();
		$this->assign ( "staArr", $staArr );
		$this->assign ( "Uarr", $Uarr );
		$this->display ();
	}
	/**
	 * 包含已审核通过的订单
	 * 订单状态 审核通过，取消生产，允许生产，生产中
	 */
	public function getProduceLabelListJson() {
		import ( "@.Library.Class.Check" );
		$mm = new ViewOrderModel ();
		$statStr = Check::getProduceStatusList();
		$where = "o_status IN ({$statStr}) "; // ->where("g_manager = 0")
		$dstart = date ( "Y-m-d", strtotime ( "-30 day" ) );
		$w_date = " AND o_date >= '{$dstart}'";
		if ($_POST ['dstart']) {
			$dstart = $_POST ['dstart'] == "" ? "" : $_POST ['dstart'] . " 00:00:00";
			$dend = $_POST ['dend'] == "" ? "" : $_POST ['dend'] . " 23:59:59";
			$w_date = " AND (o_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND o_date >= '{$dstart}'";
			}
		}
		$w_status = $_POST ['status'] == "" ? "" : " AND o_status = '{$_POST['status']}'";
		$w_user = $_POST ['uName'] == "" ? "" : " AND o_userId = '{$_POST ['uName']}'";
		$where .= $w_date . $w_user . $w_status;
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->order ( 'o_date desc' )->limit ( "{$startP},{$endP}" )->select ();
		$owM = new OrderWorkModel ();
		for($i = 0; $i < count ( $arr ); $i ++) {
			$owRs = $owM->where ( "order_id = {$arr [$i] ['id']}" )->find ();
			$arr [$i] ['hasWOrder'] = is_array ( $owRs ) ? 1 : 0;
			$arr [$i] ['userInfo'] = $arr [$i] ['u_manager'] . " (" . $arr [$i] ['g_name'] . ")";
			$arr [$i] ['statusInfo'] = Check::checkOrderStatus ( $arr [$i] ['o_status'] );
			//$arr [$i] ['status'] = Check::checkOrderStatus ( $arr [$i] ['o_status'] );
			$arr [$i] ['o_date'] = strToDateTime ( $arr [$i] ['o_date'] ); // date ( "Y-m-d H:i", strtotime ( $arr [$i] ['o_date'] ) );
		}
		$res = array ();
		// $count = count ( $arr );
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	/**
	 * 生成加工单，提交确定之后修改状态为生产中
	 */
	public function getLabel() {
		$mm = new OrderWorkModel ();
		if ($_POST) {
			$omm = new OrderModel ();
			$data ['id'] = $_POST ['oid'];
			$data ['o_status'] = "producing";
			if ($omm->save ( $data ) !== false) {
				$mm->save ( array (
						"id" => $_POST ['id'],
						"is_print" => 1 
				) );
				import ( "@.Library.Class.Check" );
				Check::updateOrderStatusRecord ( $_POST ['oid'], "check_success", $data ['o_status'] );
				$this->ajaxReturn ( "success", "设置为生产中。", 1 );
			} else {
				$this->ajaxReturn ( "fail", "订单状态设置失败。", 0 );
			}
		}
		$row = $mm->where ( "order_id = {$_GET['id']}" )->find ();
		$this->assign ( "row", $row );
		$this->display ( "workOrder" );
	}
	

	/**
	 * 设定订单状态为加工完成
	 */
	public function setStatusProduced() {
		if ($_POST) {
			$omm = new OrderModel ();
			$data ['id'] = $_POST ['id'];
			$data ['o_status'] = "produced";
			if ($omm->save ( $data ) !== false) {
				$mm = new OrderWorkModel ();
				$dt['end_user'] = $_SESSION['cmp']['id'];
				$dt['end_date'] = date('Y-m-d H:i:s');
				$dt['is_over'] = 1;
				$mm->where("order_id = {$_POST ['id']}")->save($dt);
				import ( "@.Library.Class.Check" );
				Check::updateOrderStatusRecord ( $_POST ['id'], "producing", $data ['o_status'] );
				$this->ajaxReturn ( "success", "设置为生产完成成功。", 1 );
			} else {
				$this->ajaxReturn ( "fail", "设置为生产完成失败。", 0 );
			}
		}
	}
	
	/**
	 * 创建或修改加工单
	 */
	public function createOrderWorkOrder() {
		$order_id = $_GET ['id'];
		$mm = new OrderWorkModel ();
		if (IS_POST) {
			$_POST ['create_user'] = $_SESSION ['cmp'] ['id'];
			$_POST ['create_date'] = date ( 'Y-m-d' );
			if ($_POST ['type'] == "edit") {
				$c = $mm->save ( $_POST );
			} else {
				$c = $mm->add ( $_POST );
			}
			if ($c !== false) {
				$this->ajaxReturn ( "success", "操作成功", 1 );
			} else {
				$this->ajaxReturn ( "fail", "操作失败。", 0 );
			}
		}
		$rs = $mm->where ( "order_id = {$order_id}" )->find ();
		if ($rs) {
			$this->assign ( "row", $rs );
		} else {
			import ( "@.Library.Class.Check" );
			$odM = new OrderModel ();
			$orderInfo = $odM->where ( "id = {$order_id}" )->find ();
			// $orderInfo['statusInfo'] = Check::checkOrderStatus ( $orderInfo['o_status'] );
			$row = Check::getOrderFullInfoByOrderId ( $order_id );
			$uM = new ManagerModel ();
			$userRs = $uM->where ( "id = {$orderInfo['o_userId']}" )->find ();
			$row ['comment'] = $orderInfo ['o_desc'];
			$row ['customer'] = $userRs ['u_company'];
			// $this->assign ( "orderInfo", $orderInfo );
			// $this->assign ( "userRs", $userRs );
			$this->assign ( "row", $row );
		}
		$this->display ( "workOrderPanel" );
	}
	/**
	 * 加工单作废
	 */
	public function delOrderWorkOrder(){
		
	}
	
	/**
	 * 送货单打印页面，包含已加工完成的订单
	 */
	public function deliveryList() {
		//import ( "@.Library.Class.Check" );
		//$staArr = Check::getDeliveryStatus ();
		//$Uarr = $this->getUsers ();
		//$this->assign ( "staArr", $staArr );
		$uM = new ManagerModel();
		$Uarr = $uM->select ();
		$this->assign ( "Uarr", $Uarr );
		$this->display ();
	}
	
	/**
	 * 送货单打印，包含已加工完成的订单,列表显示
	 */
	public function getDeliveryListJson() {
		import ( "@.Library.Class.Check" );
		$mm = new ViewDeliveryModel ();
		//$staStr = Check::getDeliveryStatusList();
		$where = "1=1 "; // ->where("g_manager = 0")
		$dstart = date ( "Y-m-d", strtotime ( "-30 day" ) );
		$w_date = " AND create_date >= '{$dstart}'";
		if ($_POST ['dstart']) {
			$dstart = $_POST ['dstart'] == "" ? "" : $_POST ['dstart'] . " 00:00:00";
			$dend = $_POST ['dend'] == "" ? "" : $_POST ['dend'] . " 23:59:59";
			$w_date = " AND (create_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND create_date >= '{$dstart}'";
			}
		}
		$w_type = $_POST['type'] == '' ? "" : " AND d_status = 'delivery'";
		$w_user = $_POST ['uName'] == "" ? "" : " AND customer_id = '{$_POST ['uName']}'";
		$where .= $w_date . $w_user.$w_type;
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->limit ( "{$startP},{$endP}" )->select ();
		for($i = 0; $i < count ( $arr ); $i ++) {
			//$arr [$i] ['userInfo'] = $arr [$i] ['u_manager'] . " (" . $arr [$i] ['g_name'] . ")";
			$arr [$i] ['status'] = Check::checkDeliveryStatus($arr [$i] ['d_status']);// :checkOrderStatus ( $arr [$i] ['o_status'] );
			$arr [$i] ['create_date'] =  strToDateTime($arr [$i] ['create_date']);//date ( "Y-m-d H:i", strtotime ( $arr [$i] ['o_date'] ) );
		}
		$res = array ();
		// $count = count ( $arr );
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	/**
	 * 生成出货单
	 */
	public function makeDeliveryLabel(){
		if(IS_POST){
			import ( "@.Library.Class.Check" );
			//先添加出货单记录，修改订单所属出货单，计算用户帐户余额，修改出货单信息
			$idStr = $_POST['idStr'];
			$cus_id = $_POST['uName'];
			$price = 0;//总价，用于计算会员余额
			$data = array();
			
			$uManeyM = new UserManeyModel();
			//$vpoM = new ViewProduceOrderModel();
			$orderM = new OrderModel();
			//添加出货单记录
			$data['u_id'] = $_SESSION['cmp']['id'];
			$data['u_name'] = $_SESSION['cmp']['u_manager'];
			$data['number'] = date('YmdHi');
			$data['create_date'] = date('Y-m-d H:i:s');
			$data['customer_id'] = $cus_id;
			$data['produces'] =$idStr;
			$data['do_desc'] = "";
			$doM = new DeliveryOrderModel();
			$d = $doM->add($data);
			if($d !== false){
				//修改订单所属出货单
				$o = $orderM->where("id IN ({$idStr})")->save(array("o_devery_id"=>$d,'o_status'=>'delivery'));
				$data = array();
				//出货单创建成功
				$arr = $orderM->where("id IN ({$idStr})")->select();
				foreach($arr as $v){
					$price += $v['o_price'];//累计总价格
					Check::updateOrderStatusRecord ( $v ['id'], "produced", 'delivery' );
				}

				if($o === false){
					$this->ajaxReturn("fail","送货单创建失败",0);
				}
				//查询用户余额信息
				$umRs = $uManeyM->where("user_id = {$cus_id}")->find();
				$us = $umRs['curr_maney'] - $price;
				$data['last_maney'] = $us <= 0 ? 0 : $us;//本期结余（账户余额）
				$data['now_maney'] = $umRs['avai_maney'];//本期余额（可用额度）
				//修改会员帐户余额
				$u = $uManeyM->where("user_id = {$cus_id}")->save(array("curr_maney" => $data['last_maney']));
				if($u === false){
					$this->ajaxReturn("fail","送货单创建失败",0);
				}
				//修改出库单记录的用户额的
				$c = $doM->where("id = {$d}")->save($data);
				if($c !== false){
					$this->ajaxReturn($d,"送货单创建成功",1);
				}else{
					$this->ajaxReturn("fail","送货单创建失败",0);
				}
			}else{
				$this->ajaxReturn("fail","送货单创建失败",0);
			}
		}
		$uM = new ManagerModel();
		$Uarr = $uM->select (); // ->where("G.g_manager = 0")
		$Parr = $this->getProducts();
		$this->assign("Parr",$Parr);
		$this->assign("Uarr",$Uarr);
		$this->display("deliveryAdd");
	}
	/**
	 * 送货单作废
	 */
	public function deliveryLabelDel(){
		if ($_POST) {
			$mm = new DeliveryOrderModel();
			$rs = $mm->where("id = {$_POST['id']}")->find();
			$data ['id'] = $_POST ['id'];
			if(!empty($_POST['type']) && $_POST['type'] == 'del'){
				$data ['d_status'] = 'cancel';
			}else if(!empty($_POST['type']) && $_POST['type'] == 'over'){
				$data ['d_status'] = "over";
			}
			if ($mm->save ( $data ) !== false) {
				$orderM = new OrderModel();
				if(!empty($_POST['type']) && $_POST['type'] == 'over'){
					$orderM->where("id IN ({$rs['produces']})")->save(array('o_status'=>'over'));
					import ( "@.Library.Class.Check" );
					$idArr = explode(",", $rs['produces']);
					for ($i=0;$i<count($idArr);$i++){
						Check::updateOrderStatusRecord ( $idArr[$i], "delivery", $data ['d_status'] );
						$rs = $orderM->where("id = {$idArr[$i]}")->find();
						Check::checkOrderOverManey($rs['o_userId'], $rs['o_price'], $rs['o_rightPrice'],$rs['id']);
					}
				}
				if(!empty($_POST['type']) && $_POST['type'] == 'del'){
					$orderM->where("id IN ({$rs['produces']})")->save(array('o_devery_id'=>0));
				}
				$this->ajaxReturn ( "success", "修改送货单状态成功。", 1 );
			} else {
				$this->ajaxReturn ( "fail", "修改送货单状态失败。", 0 );
			}
		}
	}
	
	/**
	 * 已完成生产的订单
	 */
	public function allProdecedOrder4Select(){
		import ( "@.Library.Class.Check" );
		$mm = new ViewProduceOrderModel ();
		$where = "is_over = 1 AND o_status = 'produced'";
		$dstart = date ( "Y-m-d", strtotime ( "-30 day" ) );
		$w_date = " AND o_date >= '{$dstart}'";
		if ($_POST ['dstart']) {
			$dstart = $_POST ['dstart'] == "" ? "" : $_POST ['dstart'] . " 00:00:00";
			$dend = $_POST ['dend'] == "" ? "" : $_POST ['dend'] . " 23:59:59";
			$w_date = " AND (o_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND o_date >= '{$dstart}'";
			}
		}
		$w_product = $_POST ['product'] == "" ? "" : " AND p_id = '{$_POST['product']}'";
		$w_user = $_POST ['uName'] == "" ? "" : " AND o_userId = '{$_POST ['uName']}'";
		$where .= $w_date . $w_user . $w_product;
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->order ( 'o_date desc' )->limit ( "{$startP},{$endP}" )->select ();
		$uM = new ManagerModel();
		for($i = 0; $i < count ( $arr ); $i ++) {
			$uRs = $uM->where ( "id = {$arr [$i] ['o_userId']}" )->find ();
			$arr [$i] ['userInfo'] = $uRs ['u_company'];//$uRs ['u_manager'];
			$arr [$i] ['o_date'] = date ( "Y年m月d日", strtotime ( $arr [$i] ['o_date'] ) );//strToDateTime ( $arr [$i] ['o_date'] ); 
			$arr [$i] ['end_date'] = date ( "Y年m月d日", strtotime ( $arr [$i] ['end_date'] ) );
		}
		$res = array ();
		// $count = count ( $arr );
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	
	public function showDeliveryOrder(){
		//var_dump($_GET);
		$uManeyM = new UserManeyModel();
		//是否为预览
		if($_GET['type'] == "view"){
			$uid = $_GET['uid'];
			$otherInfo['makeUser'] = $_SESSION['cmp']['u_manager'];
			$idStr = $_GET['id'];
			$otherInfo['create_date'] = date('Y年m月d日');
			$otherInfo['number'] = date('YmdHi');
			$umRs = $uManeyM->where("user_id = {$uid}")->find();
			
		}else{
			$doM = new DeliveryOrderModel();
			$rs = $doM->where("id = {$_GET['id']}")->find();
			$otherInfo['makeUser'] = $rs['u_name'];
			$idStr = $rs['produces'];
			$otherInfo['create_date'] = $rs['create_date'];
			$otherInfo['number'] = $rs['number'];
			$otherInfo['last_maney'] = $rs['last_maney'];//本期结余（账户余额）
			$otherInfo['now_maney'] = $rs['now_maney'];//本期余额（可用额度）
			$otherInfo['create_date'] = date('Y年m月d日',strtotime($rs['create_date']));//$rs['create_date'];
		}
		$vpoM = new ViewProduceOrderModel();
		$arr = $vpoM->where("order_id IN ({$idStr})")->select();
		//var_dump($arr);
		if(empty($otherInfo['last_maney'])){
			foreach($arr as $v){
				$price += $v['o_price'];//累计总价格
			}
			$us = $umRs['curr_maney'] - $price;
			$otherInfo['last_maney'] = $us <= 0 ? 0 : $us;//本期结余（账户余额）
			$otherInfo['now_maney'] = $umRs['avai_maney'];//本期余额（可用额度）
		}
		
		$uM = new ManagerModel();
		$uInfo = $uM->where("id = {$arr[0]['o_userId']}")->find();
		$otherInfo['customer'] = $uInfo['u_company'];
		$otherInfo['telPhone'] = $uInfo['u_tel'];
		$otherInfo['address'] = $uInfo['u_address'];
		$this->assign("otherInfo",$otherInfo);
		$this->assign("arr",$arr);
		$this->display("deliveryOrder");
	}
	
}