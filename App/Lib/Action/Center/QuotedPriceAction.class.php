<?php
/**
 * 报价操作
 * 
 * @author Administrator
 *
 */
class QuotedPriceAction extends ProtectedAction {
	public function index() {
		$type = $_GET ['type'];
		$productM = new ViewProductModel ();
		$productRs = $productM->where ( "id = {$_GET['id']}" )->find ();
		$attM = new ViewProductAttributeModel ();
		$arr = $attM->where ( "product_id = {$_GET['id']} AND a_status = 1" )->select ();
		$proM = new ViewProductProcessModel ();
		$proArr = $proM->where ( "P1.pp_product = {$_GET['id']} AND P1.pp_process <> 0 AND P2.p_status = 1" )->select ();
		// var_dump($proM->getLastSql());exit();
		$this->assign ( "attrArr", $arr );
		$this->assign ( "proArr", $proArr );
		$this->assign ( "rs", $productRs );
		$this->display ( 'viewPage' );
	}
	public function getAttrJsonData() {
		// var_dump($_GET);exit();type:007 id:5 proId:2
		$type = $_GET ['type'];
		$productId = $_GET ['proId'];
		$attM = new ViewProductAttributeModel ();
		$rs = $attM->where ( "product_id = {$productId} AND id = {$type}" )->find ();
		switch ($type) {
			case '1' :
				$sizeM = new PaperSizeModel ();
				$sizeArr = $sizeM->where ( "id IN ({$rs['pa_attr_attr']})" )->order ( 'id' )->select ();
				echo json_encode ( $sizeArr );
				break;
			case '37' :
				$paperM = new PaperModel ();
				$paperArr = $paperM->where ( "parent_id = 0 AND id IN ({$rs['pa_attr_attr']})" )->select ();
				echo json_encode ( $paperArr );
				break;
			case '007' :
				$paperM = new PaperModel ();
				$paperArr = $paperM->where ( "parent_id = {$_GET['id']} AND id IN ({$rs['pa_attr_attr']})" )->select ();
				echo json_encode ( $paperArr );
				break;
			default :
				$attMM = new AttributeModel ();
				$attArr = $attMM->where ( "id IN ({$rs['pa_attr_attr']})" )->select ();
				// echo $attMM->getLastSql();
				echo json_encode ( $attArr );
				break;
		}
	}
	public function getKeZhong() {
		$productId = $_GET ['proId'];
		$attM = new ViewProductAttributeModel ();
		$rs = $attM->where ( "product_id = {$productId} AND id = 37" )->find ();
		$mm = new ViewPaperAttrModel ();
		$papArr = $mm->where ( "P1.parent_id = {$_GET['id']} AND P1.m_isDelete = 0 AND P1.id IN ({$rs['pa_attr_attr']})" )->order ( 'm_name' )->select ();
		echo json_encode ( $papArr );
	}
	public function getProJsonData() {
		$type = $_GET ['type'];
		$productId = $_GET ['proId'];
		$attM = new ViewProductProcessModel ();
		$rs = $attM->where ( "P1.pp_product = {$productId} AND P2.id = {$type}" )->find ();
		
		// var_dump($attM->getLastSql());
		
		$proM = new ProcessModel ();
		$proArr = $proM->where ( "id IN ({$rs['pp_process_attr']})" )->select ();
		echo json_encode ( $proArr );
	}
	/**
	 * 项目报价
	 */
	public function stickerPrice() {
		if ($_POST) {
			$pri = new QuotePriceCheckAction ( $_POST );
			$data = $pri->getDataArray ();
			$mm = new OfferModel ();
			$res = array ();
			$res ['priceFinal'] = $pri->getFinalPrice ();//总价
			$res ['paperPrice'] = $pri->getPaperPrice ();//纸价
			$res ['kaishuNum'] = $pri->getKaishuNum ();//开数
			$res ['perJoinNum'] = $pri->getPerJoinsNum ();//联数
			$res ['lossPaper'] = $pri->getLossPaper ();//放张数
			$res ['paperAmount'] = $pri->getPaperAmount ();//全张纸数
			$res ['paperType'] = $pri->getPaperType();//纸张类型
			$res ['paperSize'] = $pri->getPaperSizeInfo ();//成品大小
			$res ['printWorks'] = $pri->getPrintWorks ();//印工数
			$res ['workFee'] = $pri->getPrintWorkFee ();//印工费
			$res ['processFee'] = $pri->getProcessFee ();//后加工费用
			$res ['versionCost'] = $pri->getVersionCost ();//版费
			$res ['printFee'] = $pri->getPrintWorkFee () + $pri->getVersionCost ();//印刷费
			$res ['numberProfit'] = $pri->getNumberProfit();//数量比例差价
			$res ['groupProfit'] = $pri->getGroupProfit();//会员组利润差价
			$rs = $pri->getPrinterInfo ();//印刷机
			$res ['printerInfo'] = $rs['m_name']." ".$rs['m_type']." ".$rs['m_color']." ".$rs['m_maxLength']."×".$rs['m_maxWidth']." 起印费:".$rs['m_price']." 版费:".$rs['m_versionCost'];
			//var_dump ( $rs );exit();
			$d = $mm->add ( $data );
			if ($d !== false) {
				$ofFulM = M('pt_offerfull');
				$res ['offer_id'] = $d;
				$ofFulM->add($res);
				$this->ajaxReturn ( "{$d}", "请稍等,正在计算。。。", 1 );
			} else {
				$this->ajaxReturn ( "fail", "报价失败 ." . $pri->getError (), 1 );
			}
			// var_dump($data);
		}
	}
	/**
	 * 查看报价结果
	 */
	public function result() {
		if ($_POST) {
			import ( "@.Library.Class.Check" );
			$ofM = new OfferModel ();
			$orderM = new OrderModel ();
			$orderFullM = new OrderFullModel ();
			//查找报价详细信息
			$rs = $ofM->where ( "id = {$_POST ['id']}" )->find ();
			//判断账户余额是否可以下本次订单
			$chec = Check::checkUserManey($_SESSION ['cmp'] ['id'], $rs ['o_price']);
			if($chec !== false){
				//实际扣除定金数额
				$orderArr ['o_rightPrice'] = $chec;
			}else{
				$this->ajaxReturn ( "fail", "账户余额不足，请先充值或缴费。", 0 );
				//强制退出，以免意外
				exit();
			}
			$orderArr ['o_number'] = "OD" . date ( 'YmdHis' );
			$orderArr ['o_userId'] = $_SESSION ['cmp'] ['id'];
			$orderArr ['o_date'] = date ( 'Y-m-d H:i:s' );
			$orderArr ['o_price'] = $rs ['o_price'];
			$orderArr ['o_desc'] = "";
			$i = $orderM->add ( $orderArr );
			if ($i !== false) {
				//$orderFullM = new OrderFullModel ();
				//$rs = $ofM->where ( "id = {$_POST ['id']}" )->find ();
				Check::checkOrderManey($_SESSION ['cmp'] ['id'], $rs ['o_price'],$i);
				//保存订单详细信息
				$rs ['order_id'] = $i;
				$rs ['offer_id'] = $_POST ['id'];
				$rs ['o_fileName'] = "";
				$d = $orderFullM->add ( $rs );
				if ($d !== false) {
					$this->ajaxReturn ( $i, "订单创建成功。", 1 );
				} else {
					$this->ajaxReturn ( "fail", "订单创建失败。", 0 );
				}
				//$orderArr ['id'] = $i;
				//$orderArr ['o_price'] = $rs ['o_price'];
				//$s = $orderM->save ( $orderArr );
				//if ($s !== false) {
				//	$this->ajaxReturn ( $i, "订单创建成功。", 1 );
				//} else {
				//	$this->ajaxReturn ( "fail", "订单创建失败。", 0 );
				//}
			} else {
				$this->ajaxReturn ( "fail", "订单创建失败。", 0 );
			}
		}
		$mm = new OfferModel ();
		$attrM = new AttributeModel ();
		$rs = $mm->where ( "id = {$_GET['id']}" )->find ();
		$s = str_replace ( "_is", "", $rs ['o_attr'] );
		$attrArr = $attrM->where ( "id IN ({$s})" )->select ();
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
		// $this->assign("printerInfo","");
		$this->assign ( 'attrValue', $attrValue );
		$this->assign ( 'processInfo', $processInfo );
		$this->display ();
	}
	/**
	 * 显示报价详细信息
	 */
	public function showAll(){
		if($_SESSION ['cmp'] ['g_manager'] == 1){
			$ofFulM = M('pt_offerfull');
			$rs = $ofFulM->where("offer_id = {$_GET['id']}")->find();
			//var_dump($rs);
			$this->assign("rs",$rs);
			$this->display('more');
			return;
		}
		echo '没有权限';
	}
	
	public function upFile() {
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
		$this->display ();
	}
	public function upFile_back() {
		$omm = new OfferModel ();
		$odfmm = new OrderFullModel ();
		if ($_POST) {
			import ( "@.Library.Class.UploadFile" );
			$upload = new UploadFile (); // 实例化上传类
			$upload->maxSize = 1024 * 1024 * 10; // 设置附件上传大小
			$upload->allowExts = array (
					'rar',
					'zip' 
			); // 设置附件上传类型
			$upload->saveRule = ""; // 不启用重命名
			$upload->savePath = C ( 'OFFER_UPLOAD_PATH' ) . 'OF' . $_POST ['id'] . "/"; // 设置附件上传目录(每个都分配一个目录存放，防止有相同的文件名)
			if ($upload->upload ()) { // 上传成功 获取上传文件信息
				$info = $upload->getUploadFileInfo ();
				$data ['id'] = $_POST ['id'];
				$data ['o_fileName'] = $upload->savePath . $info [0] ['savename']; // : $info [1] ['savename'];
				$i = $mmCar->save ( $data );
				// var_dump($mmCar->getLastSql());
				if ($i !== false) {
					$this->ajaxReturn ( "success", "提交成功。", 1 );
				} else {
					unlink ( $upload->savePath . $info [0] ['savename'] );
					$this->ajaxReturn ( "fail", "提交失败。", 0 );
				}
			} else {
				// 上传错误提示错误信息
				$error = $upload->getErrorMsg () == "" ? "上传仅限于 *.rar,*.zip 格式，大小10M以内。" : $upload->getErrorMsg ();
				$this->ajaxReturn ( "fail", $error, 0 );
			}
		}
		// 查找购物车里的内容
		$ids = $omm->where ( "id = {$_GET['id']}" )->find ();
		// 查找对应订单项目信息
		$rs = $odfmm->where ( "order_id = {$_GET['id']}" )->find ();
		$s = str_replace ( "_is", "", $rs ['o_attr'] );
		// 查找以选择的属性
		$attrM = new AttributeModel ();
		$attrArr = $attrM->where ( "id IN ({$s})" )->select ();
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
		$this->assign ( "printerInfo", "" );
		$this->assign ( 'attrValue', $attrValue );
		$this->assign ( 'processInfo', $processInfo );
		$this->display ();
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
					$rs ['o_fileName'] = $row ['o_fileName'];
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
				} else {
					$orderFullM->rollback ();
					$this->ajaxReturn ( "fail", "订单创建失败。", 0 );
				}
			}
		}
	}
}