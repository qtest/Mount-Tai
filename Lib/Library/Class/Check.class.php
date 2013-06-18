<?php
class Check {
	/**
	 * 获取订单详细内容
	 * 
	 * @param int  $oid 订单id
	 * @return string
	 */
	public static function getOrderFullInfoByOrderId($oid){
		$odfM = new OrderFullModel ();
		$rs = $odfM->where ( "order_id = {$oid}" )->find ();
		//$ofArr = array ();
		$count = 0;
		$attrM = new AttributeModel ();
		$s = str_replace ( "_is", "", $rs ['o_attr'] );
		$attrArr = $attrM->where ( "id IN ({$s})" )->select ();
		$attrValue = explode ( ";", $rs ['o_attr_info'] );
		$attrInfo = "";
		for($i = 0; $i < count ( $attrArr ); $i ++) {
			$info = $attrArr [$i] ['a_name'] . ": " . $attrValue [$i];
			$attrInfo .= $attrInfo == "" ? $info : " ;  " . $info;
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
		$rs ['o_filePath'] = $rs ['o_fileName'];
		// 只显示文件名
		$fArr = explode ( "/", $rs ['o_fileName'] );
		$rs ['o_fileName'] = $fArr [count ( $fArr ) - 1];
		$rs ['o_date'] = strToDateTime($rs ['o_date']);
		$ofFulM = M('pt_offerfull');
		$ff = $ofFulM->where("offer_id = {$rs ['offer_id']}")->find();
		$rs ['paper_amount'] = $ff['paperAmount'];
		
		$proM = new ViewProductModel();
		$proRs = $proM->where("id = {$rs['p_id']}")->find();
		$rs ['unit_name'] = $proRs['unit_name'];
		//$count += $rs ['o_price'];
		//array_push ( $ofArr, $rs );
		return $rs;
	}
	
	public static function getMyOrderInfoStatus(){
		return "'confirming','checking','check_fail','check_success','producing','produced','delivery'";//
	}
	
	/**
	 * 所有订单状态
	 * 
	 * @return array status,display
	 */
	public static function getAllStatus(){
		$res = array();
		array_push($res, array('status'=>'confirming','display'=>'等待确认'));
		array_push($res, array('status'=>'checking','display'=>'审核中'));
		array_push($res, array('status'=>'check_fail','display'=>'审核不通过'));
		array_push($res, array('status'=>'check_success','display'=>'审核通过'));
		//array_push($res, array('status'=>'allow_fail','display'=>'取消生产'));
		//array_push($res, array('status'=>'allow_success','display'=>'允许生产'));
		array_push($res, array('status'=>'producing','display'=>'生产中'));
		array_push($res, array('status'=>'produced','display'=>'生产完成'));
		array_push($res, array('status'=>'delivery','display'=>'送货中'));
		array_push($res, array('status'=>'over','display'=>'已完成'));
		array_push($res, array('status'=>'cancel','display'=>'已取消'));
		return $res;
	}
	
	public static function getStatusList(){
		return "'confirming','checking','check_fail'";//,'produced','delivery','over'
	}
	
	/**
	 * 加工单打印页面
	 * 
	 * @return array status,display
	 */
	public static function getProduceStatus(){
		$res = array();
		//array_push($res, array('status'=>'confirming','display'=>'等待确认'));
		//array_push($res, array('status'=>'checking','display'=>'审核中'));
		//array_push($res, array('status'=>'check_fail','display'=>'审核不通过'));
		array_push($res, array('status'=>'check_success','display'=>'审核通过'));
		//array_push($res, array('status'=>'allow_fail','display'=>'取消生产'));
		//array_push($res, array('status'=>'allow_success','display'=>'允许生产'));
		array_push($res, array('status'=>'producing','display'=>'生产中'));
		//array_push($res, array('status'=>'produced','display'=>'生产完成'));
		//array_push($res, array('status'=>'delivery','display'=>'送货中'));
		//array_push($res, array('status'=>'over','display'=>'已完成'));
		//array_push($res, array('status'=>'cancel','display'=>'已取消'));
		return $res;
	}
	
	public static function getProduceStatusList(){
		return "'check_success','producing'";//,'produced','delivery','over'
	} 
	
	/**
	 * 送货单打印页面
	 * 
	 * @return array status,display
	 */
	public static function getDeliveryStatus(){
		$res = array();
		//array_push($res, array('status'=>'confirming','display'=>'等待确认'));
		//array_push($res, array('status'=>'checking','display'=>'审核中'));
		//array_push($res, array('status'=>'check_fail','display'=>'审核不通过'));
		//array_push($res, array('status'=>'check_success','display'=>'审核通过'));
		//array_push($res, array('status'=>'allow_fail','display'=>'取消生产'));
		//array_push($res, array('status'=>'allow_success','display'=>'允许生产'));
		//array_push($res, array('status'=>'producing','display'=>'生产中'));
		array_push($res, array('status'=>'produced','display'=>'生产完成'));
		array_push($res, array('status'=>'delivery','display'=>'送货中'));
		array_push($res, array('status'=>'over','display'=>'已完成'));
		return $res;
	}
	
	public static function getDeliveryStatusList(){
		return "'produced','delivery','over'";
	}
	
	public static function checkDeliveryStatus($sta){
		$res = '<font color="#02B11F">送货中</font>';
		if(empty($sta)){
			return $res;
		}
		//confirming checking check_fail check_success producing produced delivery over
		switch ($sta){
			case "delivery":
				$res = '<font color="#02B11F">送货中</font>';
				break;
			case "over":
				$res = '<font color="#1C19D7">已完成</font>';
				break;
			case "cancel":
				$res = '<font color="#666">已取消</font>';
				break;
			default:
				break;
		}
		return $res;
	}
	
	/**
	 * 解释订单状态
	 * 
	 * @param string $sta
	 * @return string
	 */
	public static function checkOrderStatus($sta){
		$res = '<font color="#D7BA00">等待确认</font>';
		if(empty($sta)){
			return $res;
		}
		//confirming checking check_fail check_success producing produced delivery over
		switch ($sta){
			case "confirming":
				$res = '<font color="#D7BA00">等待确认</font>';
				break;
			case "checking":
				$res = '<font color="#D7BA00">审核中</font>';
				break;
			case "check_fail":
				$res = '<font color="#ff0000">审核不通过</font>';
				break;
			case "check_success":
				$res = '<font color="#63903C">审核通过</font>->等待生产';
				break;
			case "allow_fail":
				$res = '<font color="#ff0000">取消生产</font>';
				break;
			case "allow_success":
				$res = '<font color="#45AF6C">允许生产</font>';
				break;
			case "producing":
				$res = '<font color="#02B11F">生产中</font>';
				break;
			case "produced":
				$res = '<font color="#02B11F">生产完成</font>->等待送货';
				break;
			case "delivery":
				$res = '<font color="#02B11F">送货中</font>';
				break;
			case "over":
				$res = '<font color="#1C19D7">已完成</font>';
				break;
			case "cancel":
				$res = '<font color="#666">已取消</font>';
				break;
			default:
				break;
		}
		return $res;
	}
	
	/**
	 * 订单状态修改记录
	 * 
	 * @param int $order_id 订单id
	 * @param string $oldStatus 原状态
	 * @param string $newStatus 新状态
	 * @return boolean
	 */
	public static function updateOrderStatusRecord($order_id,$oldStatus,$newStatus){
		$mm = M('pt_order_status');
		$data['u_id'] = $_SESSION ['cmp']['id'];
		$data['order_id'] = $order_id;
		$data['check_time'] = date('Y-m-d H:i:s');
		$data['status_old'] = $oldStatus;
		$data['status_new'] = $newStatus;
		$d = $mm->add($data);
		if($d !== false){
			return true;
		}
		return false;
	}
	
	/**
	 * 会员金额扣除
	 * 会员下单，扣除可用额度，保持账户余额不变。
	 * 
	 * 注：会员下单后收取定金，扣除账户余额，帐户余额不够扣除，提示并拒绝下单
	 * 
	 * @param int $userId
	 * @param float $cost
	 * @return boolean|number 拒绝下单，返回false，成功扣除，返回定金数额
	 */
	public static function checkOrderManey($userId,$cost,$order_id){
		$mm = new UserManeyModel();
		$rs = $mm->where("user_id = {$userId}")->find();
		//没有账户记录
		if(!is_array($rs))return false;
		//if($rs['min_maney'] == 0)return true;
		//计算定金数额
		$front_maney = ceil($rs['front_maney_percent'] / 100 * $cost);
		if($rs['min_maney'] != 0){
			//账户余额是否够扣除定金
			if($front_maney > $rs['curr_maney']){
				return false;
			}
			//可用额度小于订单金额
			if($rs['avai_maney'] < $cost){
				return false;
			}
		}
		$data['id'] = $rs['id'];
		$data['avai_maney'] = $rs['avai_maney'] - $cost;//修改可用余额
		$data['curr_maney'] = $rs['curr_maney'] - $front_maney;//修改账户余额，仅扣除定金数额
		$u = $mm->save($data);
		if($u !== false){
			self::maneyChangeRecord($userId,"out",$order_id,$front_maney,$data['curr_maney'],"订单生成，扣除定金");
			return $front_maney;
		}else{
			return false;
		}
	}
	
	public function checkUserManey($userId,$cost){
		$mm = new UserManeyModel();
		$rs = $mm->where("user_id = {$userId}")->find();
		//没有账户记录
		if(!is_array($rs))return false;
		//if($rs['min_maney'] == 0)return true;
		//计算定金数额
		$front_maney = ceil($rs['front_maney_percent'] / 100 * $cost);
		if($rs['min_maney'] != 0){
			//账户余额是否够扣除定金
			if($front_maney > $rs['curr_maney']){
				return false;
			}
			//可用额度小于订单金额
			if($rs['avai_maney'] < $cost){
				return false;
			}
		}
		return $front_maney;
	}
	
	/**
	 * 订单取消
	 * 
	 * 订单取消，增加可用额度；增加定金到账户余额
	 * 
	 * 
	 * @param int $userId 会员id
	 * @param float $cost 金额
	 */
	public static function checkOrderBackManey($userId,$cost,$front_maney,$order_id){
		$mm = new UserManeyModel();
		$rs = $mm->where("user_id = {$userId}")->find();
		//没有账户记录
		if(!is_array($rs))return false;
		/*pt_user_maney.id,
			pt_user_maney.user_id,
			pt_user_maney.front_maney_percent,
			pt_user_maney.curr_maney,
			pt_user_maney.avai_maney,
			pt_user_maney.min_maney
		*/
		$data['id'] = $rs['id'];
		$data['avai_maney'] = $rs['avai_maney'] + $cost;//修改可用额度
		$data['curr_maney'] = $rs['curr_maney'] + $front_maney;//修改账户余额
		$u = $mm->save($data);
		if($u !== false){
			self::maneyChangeRecord($userId,"in",$order_id,$front_maney,$data['curr_maney'],"订单取消，归还定金");
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 订单完成
	 * 
	 * 扣除账户余额，金额为：实际订单金额-定金
	 * 
	 * @param int $userId 用户id
	 * @param float $cost 订单金额
	 * @param float $front_maney 已收定金
	 * @return boolean
	 */
	public static function checkOrderOverManey($userId,$cost,$front_maney,$order_id){
		$mm = new UserManeyModel();
		$rs = $mm->where("user_id = {$userId}")->find();
		//没有账户记录
		if(!is_array($rs))return false;
		$data['id'] = $rs['id'];
		$price = $cost - $front_maney;
		//$data['avai_maney'] = $rs['avai_maney'] + $cost;//修改可用额度
		$data['curr_maney'] = $rs['curr_maney'] - $price;//修改账户余额
		$u = $mm->save($data);
		if($u !== false){
			self::maneyChangeRecord($userId,"out",$order_id,$price,$data['curr_maney'],"订单完成,扣除余款");
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 会员充值
	 * 
	 * 增加账户和可用额度
	 * 
	 * @param int $userId
	 * @param float $maney
	 */
	public static function rechargeManey($userId,$maney,$re_id){
		$mm = new UserManeyModel();
		$rs = $mm->where("user_id = {$userId}")->find();
		//没有账户记录
		//if(!is_array($rs))return false;
		//没有账户记录
		if(!is_array($rs)){
			$res = self::addUserManeyRecord($userId,$maney,1000,10);
			return $res;
			//$rs = $mm->where("user_id = {$userId}")->find();
		}
		$data['id'] = $rs['id'];
		$data['avai_maney'] = $rs['avai_maney'] + $maney;//修改可用额度
		$data['curr_maney'] = $rs['curr_maney'] + $maney;//修改账户余额
		$u = $mm->save($data);
		if($u !== false){
			self::maneyChangeRecord($userId,"in",$re_id,$maney,$data['curr_maney'],"账户充值");
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 修改会员账户信息
	 * 
	 * 修改后可用额度为：原可用额度减去原有信用额度，加上现可用额度（结果为负数，设可用额度为0）
	 * 
	 * @param int $userId 会员id
	 * @param float $maney 账户余额
	 * @param float $creditManey 信用额度
	 * @param float $front_maney_percent 定金百分比
	 * @return boolean
	 */
	public static function updateCreditManey($userId,$creditManey,$front_maney_percent,$tip_percent){
		$mm = new UserManeyModel();
		$rs = $mm->where("user_id = {$userId}")->find();
		//没有账户记录
		if(!is_array($rs)){
			 $res = $this->addUserManeyRecord($userId,0,$creditManey,$front_maney_percent,$tip_percent);
			 return $res;
			//$rs = $mm->where("user_id = {$userId}")->find();
		}
		$newCM = $rs['avai_maney'] - $rs['min_maney'] + $creditManey;
		$newCM = $newCM > 0 ? $newCM : 0;
		$data['user_id'] = $userId;
		$data['front_maney_percent'] = $front_maney_percent;
		//$data['curr_maney'] = $maney;
		$data['tip_percent'] = $tip_percent;
		$data['avai_maney'] = $newCM;//修改可用额度
		$data['min_maney'] = $creditManey;//修改信用额度
		//var_dump($data);
		$u = $mm->where("user_id = {$userId}")->save($data);
		//var_dump($mm->getLastSql());
		if($u !== false){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 添加会员账户信息
	 * @param int $userId 会员id
	 * @param float $maney 账户余额
	 * @param float $creditManey 信用额度
	 * @param float $front_maney_percent 定金百分比
	 * @return boolean
	 */
	private static function addUserManeyRecord($userId,$maney,$creditManey = 1000,$front_maney_percent = 10,$tip_percent = 0.5){
		$mm = new UserManeyModel();
		$data['user_id'] = $userId;
		$data['front_maney_percent'] = $front_maney_percent;
		$data['curr_maney'] = $maney;
		$data['avai_maney'] = $maney + $creditManey;//修改可用额度
		$data['min_maney'] = $creditManey;//修改信用额度
		$u = $mm->add($data);
		if($u !== false){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 会员资金变动记录添加
	 * 
	 * @param int $userId 用户id
	 * @param string $type 类型
	 * @param int $fullId 对应记录id
	 * @param float $cost 费用
	 * @param float $lastManey 结后余额（账户余额）
	 * @param string $desc 说明
	 * @return boolean
	 */
	public static function maneyChangeRecord($userId,$type = "in",$fullId,$cost,$lastManey,$desc){
		$mm = new RecordModel();
		$data['u_id'] = $userId;
		if($type == "in"){
			//收入
			$data['r_type'] = 1;
			$data['re_id'] = $fullId;
			$data['r_income'] = $cost;
		}else if($type == "out"){
			//支出
			$data['r_type'] = 0;
			$data['o_id'] = $fullId;
			$data['r_pay'] = $cost;
		}else{
			return false;
		}
		$data['r_overage'] = $lastManey;
		$data['r_date'] = date('Y-m-d H:i:s');
		$data['r_desc'] = $desc;
		$d = $mm->add($data);
		if($d !== false){
			return true;
		}else{
			return false;
		}
	}
	
}