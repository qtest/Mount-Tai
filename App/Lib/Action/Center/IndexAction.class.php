<?php
class IndexAction extends ProtectedAction {
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
	public function sysHome() {
		// 本月新注册会员数
		$uM = new ViewUserModel();// ManagerModel ();
		$mdate = mFristAndLast ();
		$to = $uM->field ( "count(*) AS zt" )->where ( "(u_regDate BETWEEN '{$mdate['firstday']}' AND '{$mdate['lastday']}' ) AND g_manager <> 1" )->find ();
		//var_dump($uM->getLastSql());
		//充值预付款
		$rcm = new RechargeModel();
		$rc = $rcm->field ( "count(*) AS zt" )->where ( "re_status = 0" )->find ();
		//购物车
		$mm = new ShopCarModel ();
		$ar = $mm->field ( "count(*) as ct" )->where ( "user_id = {$_SESSION['cmp']['id']}" )->find ();
		$this->assign ( "shCar", $ar ['ct'] );
		$this->assign ( "reCharg", $rc ['zt'] );
		$this->assign ( "regUser", $to ['zt'] );
		$this->display ();
	}
	/**
	 * 提交订单后的信息补充和附件上传
	 */
	public function subOrder() {
		$odM = new OrderModel ();
		$orderInfo = $odM->where ( "id = {$_GET['id']}" )->find ();
		$odfM = new OrderFullModel ();
		$idArr = $odfM->where ( "order_id = {$_GET['id']}" )->select ();
		if ($_POST) {
			import ( "@.Library.Class.UploadFile" );
			$upload = new UploadFile (); // 实例化上传类
			$upload->maxSize = 1024 * 1024 * 10; // 设置附件上传大小
			$upload->allowExts = array ('rar','zip'); // 设置附件上传类型
			$upload->saveRule = "";//不启用重命名
			$upload->savePath = C('ORDER_UPLOAD_PATH').'OD'.$_POST ['id']."/"; // 设置附件上传目录(每个订单都分配一个目录存放，防止有相同的文件名)
			if ($upload->upload ()) { // 上传成功 获取上传文件信息
				$info = $upload->getUploadFileInfo ();
			} else {
				// 上传错误提示错误信息
				$error = $upload->getErrorMsg () == "" ? "上传仅限于 *.rar,*.zip 格式，大小10M以内。" : $upload->getErrorMsg ();
				$this->ajaxReturn ( "fail", $error, 0 );
			}
			foreach ( $idArr as $row ) {
				$file = "";
				////原来没有上传，并且现在选择上传了
				//查找上传的文件名
				foreach ($info as $ro){
					//如果重新上传了文件，删除原来的文件，重新设置文件地址
					if($ro['key'] == 'file_' . $row ['id']){
						//删除原来的文件
						$oldFile = $row ['o_fileName'];
						if (file_exists ( $oldFile )) {
							unlink ( $oldFile );
						}
						//重新设置文件地址
						$file = $ro['savepath'].$ro['savename'];
					}
				}
				$data ['id'] = $row ['id'];
				//如果新设置的文件地址为空，表明没有重新上传，使用原来的文件
				$data ['o_fileName'] = empty($file) ? $row ['o_fileName'] : $file;
				//var_dump($data);
				$odfM->save ( $data );
			}
			//exit();
			$odA ['id'] = $_POST ['id'];
			$odA ['o_desc'] = $_POST ['o_desc'];
			$d = $odM->save ( $odA );
			if (is_array ( $info )) {
				//exit();
				$this->ajaxReturn ( "success", "提交成功。", 1 );
			}
		}
		// $mmCar = new ShopCarModel ();
		$ofArr = array ();
		$count = 0;
		foreach ( $idArr as $rs ) {
			$attrM = new AttributeModel ();
			// $rs = $odfM->where ( "id = {$row['id']}" )->find ();
			$s = str_replace("_is", "", $rs['o_attr']);
			$attrArr = $attrM->where ( "id IN ({$s})" )->select ();
			$attrValue = explode ( ";", $rs ['o_attr_info'] );
			$attrInfo = "";
			for($i = 0; $i < count ( $attrArr ); $i ++) {
				$info = $attrArr [$i] ['a_name'] . " : " . $attrValue [$i];
				$attrInfo .= $attrInfo == "" ? $info : " " . $info;
			}
			
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
			$rs ['o_filePath'] = $rs['o_fileName'];
			//只显示文件名
			$fArr = explode("/", $rs['o_fileName']);
			$rs ['o_fileName'] = $fArr[count($fArr)-1];
			$count += $rs ['o_price'];
			//var_dump($rs ['o_filePath']);
			array_push ( $ofArr, $rs );
		}
		// var_dump($ofArr);
		$this->assign ( "orderInfo", $orderInfo );
		$this->assign ( "count", $count );
		$this->assign ( "ofArr", $ofArr );
		$this->display ();
	}
	
	public function uploadFile() {
		// import ( "ORG.Net.UploadFile" );
		import ( "@.Library.Class.UploadFile" );
		
		$upload = new UploadFile (); // 实例化上传类
		
		$upload->maxSize = 10240; // 设置附件上传大小
		
		$upload->allowExts = array (
				'arr' 
		); // 设置附件上传类型
		
		$upload->savePath = 'Public/upload/customer'; // 设置附件上传目录
		
		if ($upload->upload ()) { // 上传成功 获取上传文件信息
			$info = $upload->getUploadFileInfo ();
			
			$file = $info [0] ['savepath'] . $info [0] ['savename'];
			// var_dump(urlencode( $file ));
			// var_dump(urldecode( $file ));
			// exit();
			// $file = urlencode($file);
			// unlink ( $file );
			var_dump ( $file );
			return true;
		} else {
			// 上传错误提示错误信息
			return $upload->getErrorMsg ();
		}
	}
	/**
	 * 生成订单
	 */
	public function addOrderInfo() {
		if ($_POST) {
			$orderArr ['o_number'] = "OD" . date ( 'YmdHis' );
			$orderArr ['o_userId'] = $_SESSION ['cmp'] ['id'];
			$orderArr ['o_date'] = date ( 'Y-m-d H:i:s' );
			$orderArr ['o_price'] = 0;
			$orderArr ['o_desc'] = "";
			$mmCar = new ShopCarModel ();
			$ofM = new OfferModel ();
			$orderM = new OrderModel ();
			$i = $orderM->add ( $orderArr );
			if ($i !== false) {
				$orderArr ['id'] = $i;
				$orderFullM = new OrderFullModel ();
				$orderFullM->startTrans ();
				// $ofArr = array();
				// 购物车里的订单内容id
				$idArr = $mmCar->where ( "user_id = {$_SESSION['cmp']['id']}" )->select ();
				foreach ( $idArr as $row ) {
					$rs = $ofM->where ( "id = {$row['offer_id']}" )->find ();
					$orderArr ['o_price'] += $rs ['o_price'];
					$rs ['order_id'] = $i;
					$rs ['o_fileName'] = $row['o_fileName'];
					$d = $orderFullM->add ( $rs );
					if ($d === false) {
						$orderFullM->rollback ();
						$this->ajaxReturn ( "fail", "订单创建失败。", 0 );
					}
				}
				$s = $orderM->save ( $orderArr );
				if ($s !== false) {
					if ($this->clearShoppingCar ()) {
						$orderFullM->commit ();
						$this->ajaxReturn ( $i, "订单创建成功。", 1 );
					}
				}else{
					$orderFullM->rollback ();
					$this->ajaxReturn ( "fail", "订单创建失败。", 0 );
				}
			}
		}
	}
	/**
	 * 清空购物车
	 * @return boolean
	 */
	public function clearShoppingCar() {
		$mmCar = new ShopCarModel ();
		$d = $mmCar->where ( "user_id = {$_SESSION['cmp']['id']}" )->delete ();
		if ($d !== false) {
			return true;
		}
		return false;
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
	/**
	 * 删除购物车里物品
	 */
	public function del() {
		// $id = $_POST['id'];
		$mmCar = new ShopCarModel ();
		$d = $mmCar->where ( "offer_id = {$_POST['id']} AND user_id = {$_SESSION['cmp']['id']}" )->delete ();
		if ($d !== false && $d > 0) {
			$this->ajaxReturn ( "success", "删除成功！", 1 );
		} else {
			$this->ajaxReturn ( "fail", "删除失败！", 0 );
		}
	}
}