<?php
/**
 * 系统用户信息操作
 * 
 * @author
 *
 */
class ManagerAction extends ProtectedAction {
	public function is_email($value) {
		return eregi ( '^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$', $value );
	}
	public function getGroupArr() {
		$man = new UGroupModel ();
		$select = $man->field ( "id,g_name" )->where ( "g_manager = 0" )->select ();
		return $select;
	}
	public function getUsers($field = "", $where = "") {
		$man = new ManagerModel ();
		$arr = $man->field ( $field )->where ( $where )->select ();
		return $arr;
	}
	
	// -------------------------------------------
	// 用户列表
	// -------------------------------------------
	public function index() {
		import ( "@.Library.Class.PageNormal" );
		$where = "g_manager = 0";
		$man = new ViewUserModel ();
		$userArr = $man->where ( $where )->order ( "u_name" )->select ();
		// var_dump($man->getLastSql());
		$ug_select = $this->getGroupArr ();
		$this->assign ( "ug_select", $ug_select );
		$this->assign ( "userArr", $userArr );
		$this->display ();
	}
	public function getDataGridData() {
		$mm = new ViewUserModel ();
		$where = "g_manager = 0"; // "1=1 ";
		$where .= $_POST ['u_name'] == "" ? "" : " AND id={$_POST['u_name']}";
		// $where .= $_POST ['u_company'] == "" ? "" : " AND u_company = {$_POST['cate_id']}";
		$where .= $_POST ['g_name'] == "" ? "" : " AND u_group = {$_POST['g_name']}";
		$where .= $_POST ['type'] == "" ? "" : " AND u_status = 0";
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->order ( 'u_logTime desc' )->limit ( $startP . "," . $endP )->select ();
		// var_dump($arr);
		for($i = 0; $i < count ( $arr ); $i ++) {
			$arr [$i] ['u_maney'] = $arr [$i] ['curr_maney'] . "/" . $arr [$i] ['avai_maney'];
			$arr [$i] ['u_where'] = $arr [$i] ['u_province'] . "/" . $arr [$i] ['u_city'];
		}
		// var_dump($mm->getLastSql());
		$res = array ();
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	public function checkUserInfo() {
		if (IS_POST) {
			$m = new ManagerModel ();
			$d = $m->save ( array (
					'id' => $_POST ['id'],
					'u_status' => ( int ) ($_POST ['type']) 
			) );
			// var_dump($m->getLastSql());
			if ($d !== false) {
				// 添加用户信用额度和账户余额初始记录
				$uMay = new UserManeyModel ();
				$umRs = $uMay->where ( "user_id = {$_POST ['id']}" )->find ();
				if (! is_array ( $umRs )) {
					$uMay->add ( array (
							"user_id" => $_POST ['id'] 
					) );
				}
				$this->ajaxReturn ( "success", "操作成功", 1 );
			} else {
				$this->ajaxReturn ( "fail", "操作失败", 0 );
			}
		}
		$mm = new ViewUserModel ();
		$rs = $mm->where ( "id = {$_GET['id']}" )->find ();
		$uid = $_GET ['id'];
		$orderM = new OrderModel ();
		$odTotalOver = $orderM->field ( "o_userId,Count(pt_order.id) AS total,Sum(pt_order.o_price) AS sumManey" )->where ( "o_userId = {$uid} AND o_status = 'over'" )->find ();
		$odTotalIng = $orderM->field ( "o_userId,Count(pt_order.id) AS total,Sum(pt_order.o_price) AS sumManey" )->where ( "o_userId = {$uid} AND o_status <> 'over' AND o_status <> 'cancel'" )->find ();
		$offerM = new OfferModel ();
		$ofTotal = $offerM->field ( "u_id,Count(pt_offer.id) AS total" )->where ( "u_id = {$uid}" )->find ();
		$this->assign ( "odTotalOver", $odTotalOver );
		$this->assign ( "odTotalIng", $odTotalIng );
		$this->assign ( "ofTotal", $ofTotal );
		$this->assign ( "rs", $rs );
		$this->display ( 'userPanel' );
	}
	public function userEdit() {
		// var_dump("asdvasd");
		// exit();
		$m = new ManagerModel ();
		if ($_POST) {
			if($this->is_email($_POST['email']) === false){
				$this->ajaxReturn ( "fail", "填写的<strong>邮箱</strong>格式有误！", 0 );
			}
			if(empty($_POST['company'])){
				$this->ajaxReturn ( "fail", "请填写 <strong>公司名称</strong>！", 0 );
			}
			if(empty($_POST['manager'])){
				$this->ajaxReturn ( "fail", "请填写 <strong>联系人姓名</strong>！", 0 );
			}
			$data ['id'] = $_POST ['id'];
			if ($_POST ['isedit'] == "on" && ! empty ( $_POST ['city'] )) {
				$data ['u_province'] = $_POST ['province'];
				$data ['u_city'] = $_POST ['city'];
			}
			$data ['u_company'] = $_POST ['company'];
			$data ['u_tel'] = $_POST ['tel'];
			$data ['u_manager'] = $_POST ['manager'];
			$data ['u_phone'] = $_POST ['phone'];
			$data ['u_address'] = $_POST ['addr'];
			$data ['u_email'] = $_POST ['email'];
			$data ['u_group'] = $_POST ['u_group'];
			$d = $m->save ( $data );
			if ($d !== false) {
				$this->ajaxReturn ( "success", "修改成功！", 1 );
			} else {
				$this->ajaxReturn ( "fail", "修改失败！", 0 );
			}
		} // $gArr
		$rs = $m->where ( "id = {$_GET['id']}" )->find ();
		$ug_select = $this->getGroupArr ();
		$this->assign ( "gArr", $ug_select );
		$this->assign ( "rs", $rs );
		$this->display ( 'userPanel' );
	}
	
	/**
	 * 修改用户账户信息
	 */
	public function userManeyChange() {
		if (IS_POST) {
			import ( "@.Library.Class.Check" );
			$res = Check::updateCreditManey ( $_POST ['user_id'], $_POST ['min_maney'], $_POST ['front_maney_percent'], $_POST ['tip_percent'] );
			if ($res) {
				$this->ajaxReturn ( "success", "修改成功！", 1 );
			} else {
				$this->ajaxReturn ( "fail", "修改失败！", 0 );
			}
		}
		$uid = $_GET ['id'];
		$vum = new ViewUserModel ();
		$rs = $vum->where ( "id = {$uid}" )->find ();
		$orderM = new OrderModel ();
		$odTotalOver = $orderM->field ( "o_userId,Count(pt_order.id) AS total,Sum(pt_order.o_price) AS sumManey" )->where ( "o_userId = {$uid} AND o_status = 'over'" )->find ();
		$odTotalIng = $orderM->field ( "o_userId,Count(pt_order.id) AS total,Sum(pt_order.o_price) AS sumManey" )->where ( "o_userId = {$uid} AND o_status <> 'over' AND o_status <> 'cancel'" )->find ();
		$offerM = new OfferModel ();
		$ofTotal = $offerM->field ( "u_id,Count(pt_offer.id) AS total" )->where ( "u_id = {$uid}" )->find ();
		$this->assign ( "odTotalOver", $odTotalOver );
		$this->assign ( "odTotalIng", $odTotalIng );
		$this->assign ( "ofTotal", $ofTotal );
		$this->assign ( "rs", $rs );
		$this->display ( 'userManeyPanel' );
	}
	public function userDel() {
		$m = new ManagerModel ();
		$d = $m->where ( "id = {$_GET['id']}" )->delete ();
		if ($d !== false) {
			$this->ajaxReturn ( "success", "删除成功！", 1 );
		} else {
			$this->ajaxReturn ( "fail", "删除失败！", 0 );
		}
	}
	
	/**
	 * 验证用户是否存在
	 *
	 * @param 称呼 $name        	
	 * @return boolean 不存在 => false,存在 => true
	 */
	protected function checkUser($name) {
		$m = new ManagerModel ();
		$user = $m->where ( " u_name = '{$name}'" )->find ();
		// var_dump($user);
		// exit();
		return $user ['u_name'] == "" ? false : true;
	}
	public function chargeManey() {
		import ( "@.Library.Class.PageNormal" );
		$where = "1 = 1";
		$w_date = "";
		if ($_GET ['dstart']) {
			$dstart = $_GET ['dstart'] == "" ? "" : $_GET ['dstart'] . " 00:00:00";
			$dend = $_GET ['dend'] == "" ? "" : $_GET ['dend'] . " 23:59:59";
			$w_date = " AND (re_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND re_date >= '{$dstart}'";
			}
		}
		$w_stat = $_GET ['stat'] == "" ? "" : " AND re_status = {$_GET['stat']}";
		$mm = new ViewReChargeModel ();
		$where .= $w_date . $w_stat;
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_GET ['page'] ) ? $_GET ['page'] : 1;
		$pageParam = getGetVar ();
		$page = new PageNormal ( $count ['total'], $pageNum, 10, $pageParam );
		$arr = $mm->where ( $where )->order ( 're_subDate desc' )->limit ( $page->P ['start'] . "," . $page->P ['end'] )->select ();
		// var_dump($mm->getLastSql());
		// var_dump($_SESSION['cmp']);
		// exit();
		$this->assign ( "recordArr", $arr );
		$this->assign ( "page", $page );
		$this->display ( 'chargeManeyList' );
	}
	public function showCharge() {
		$mm = new ViewReChargeModel ();
		if ($_POST) {
			$rem = new RechargeModel ();
			$rs = $rem->where ( "id = {$_POST ['id']}" )->find ();
			// if($rs ['re_status'] != "0"){
			// $this->ajaxReturn ( "success", "已经审核通过！", 0 );
			// }
			$data = array ();
			$data ['id'] = $_POST ['id'];
			$data ['re_subMan'] = $_SESSION ['cmp'] ['id'];
			if ($_POST ['type'] == "yes") {
				$data ['re_status'] = '1'; // 审核通过
			} else {
				$data ['re_status'] = '2'; // 审核不通过
			}
			$d = $rem->save ( $data );
			if ($d !== false) {
				import ( "@.Library.Class.Check" );
				Check::rechargeManey ( $rs ['u_id'], $rs ['re_money'],$_POST ['id']);
				$this->ajaxReturn ( "success", "操作成功！", 1 );
			} else {
				$this->ajaxReturn ( "fail", "操作失败！", 0 );
			}
		}
		$rs = $mm->where ( "R.id = {$_GET['id']}" )->find ();
		// var_dump($mm->getLastSql());
		// exit();
		$this->assign ( "rs", $rs );
		$this->display ();
	}
	public function charge() {
		if ($_POST) {
			if (empty ( $_POST ['date'] )) {
				$this->ajaxReturn ( "fail", "请填写 <strong>汇款日期</strong>", 0 );
			}
			if (empty ( $_POST ['bank_type'] )) {
				$this->ajaxReturn ( "fail", "请填写 <strong>账户类型</strong>", 0 );
			}
			if (empty ( $_POST ['account'] )) {
				$this->ajaxReturn ( "fail", "请填写 <strong>账户</strong>", 0 );
			}
			if (empty ( $_POST ['money'] )) {
				$this->ajaxReturn ( "fail", "请填写 <strong>充值金额</strong>", 0 );
			}
			$data = array ();
			$data ['u_id'] = $_POST ['uname'];
			$data ['re_bankInfo'] = $_POST ['bank_type'] . "(" . $_POST ['account'] . ")";
			$data ['re_myBank'] = $_POST ['my_account'];
			$data ['re_money'] = $_POST ['money'];
			$data ['re_date'] = $_POST ['date'];
			$data ['re_subDate'] = date ( 'Y-m-d H:i:s' );
			$data ['re_desc'] = $_POST ['desc'];
			$manM = new UserManeyModel ();
			$rs = $manM->where ( "user_id = {$data ['u_id']}" )->find ();
			$data ['re_overage'] = $rs ['curr_maney'] == "" ? 0 : $rs ['curr_maney'];
			$mm = new RechargeModel ();
			$mm->create ( $data );
			if ($mm->add () > 0) {
				$this->ajaxReturn ( "success", "提交成功，等待审核。", 1 );
			} else {
				$this->ajaxReturn ( "fail", "提交失败。", 0 );
			}
		}
		$bmm = new BankInfoModel ();
		$brr = $bmm->where ( 'b_main = 1' )->select ();
		$this->assign ( "uarr", $this->getUsers ( 'id,u_name,u_company', 'u_status = 1' ) );
		$this->assign ( "brr", $brr );
		$this->display ( 'chargePanel' );
	}
	public function uGroup() {
		import ( "@.Library.Class.PageNormal" );
		$where = "g_manager = 0";
		$man = new ViewUserGroupModel ();
		// $user = $man->order('name')->select();
		// $this->assign("manager",$user);
		$where .= $_GET ['name'] == "" ? "" : " AND id={$_GET['name']}";
		$count = $man->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_GET ['page'] ) ? $_GET ['page'] : 1;
		$pageParam = getGetVar ();
		$page = new PageNormal ( $count ['total'], $pageNum, 10, $pageParam );
		$group = $man->where ( $where )->order ( "id" )->limit ( $page->P ['start'] . "," . $page->P ['end'] )->select ();
		$select = $this->getGroupArr ();
		$this->assign ( "ug_select", $select );
		$this->assign ( "ugroup", $group );
		$this->assign ( "page", $page );
		$this->display ();
	}
	public function checkGroup() {
		$type = $_REQUEST ['type'];
		$mm = new UGroupModel ();
		switch ($type) {
			case 'add' :
				if ($_POST) {
					$rs = $mm->where ( "g_name = '{$_POST['g_name']}'" )->find ();
					// var_dump($op->getLastSql());
					// exit();
					if ($rs ['name'] != "") {
						$this->ajaxReturn ( "fail", "用户组名称已经存在！", 0 );
					}
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
					$rs = $mm->where ( "g_name = '{$_POST['g_name']}' AND id <> '{$_POST['id']}'" )->limit ( 1 )->find ();
					if ($rs ['name'] != "") {
						$this->ajaxReturn ( "fail", "用户组名称已经存在！", 0 );
					}
					$mm->create ( $_POST );
					if ($mm->save () > 0) {
						// var_dump($mm->getLastSql());exit();
						$this->ajaxReturn ( "success", "操作成功", 1 );
					} else {
						$this->ajaxReturn ( "fail", "操作失败", 0 );
					}
				}
				$rs = $mm->where ( "id = {$_REQUEST['id']}" )->find ();
				// var_dump($mm->getLastSql());exit();
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
		$this->display ( 'groupPanel' );
	}
	public function profit() {
		$mm = new GroupProfitModel ();
		if ($_POST) {
			$mm->startTrans ();
			// $arr = $mm->where ( "group_id = {$_POST['id']}" )->select ();
			$d = $mm->where ( "group_id = {$_POST['id']}" )->delete ();
			for($i = 0; $i < 5; $i ++) {
				$a = array ();
				$a ['group_id'] = $_POST ['id'];
				$a ['fi_min'] = $_POST ['min_' . $i];
				$a ['fi_max'] = $_POST ['max_' . $i];
				$a ['fi_percent'] = $_POST ['pes_' . $i];
				$a ['fi_value'] = $_POST ['val_' . $i];
				// $a ['fi_isNumber'] = 1;
				$d = $mm->add ( $a );
				if ($d === false) {
					$mm->rollback ();
					$this->ajaxReturn ( "fail", "操作中途出现错误！", 0 );
				}
			}
			$mm->commit ();
			$this->ajaxReturn ( "success", "保存成功！", 1 );
		}
		
		$profitArr = $mm->where ( "group_id = {$_GET['id']}" )->select ();
		$this->assign ( "profitArr", $profitArr );
		$this->display ( "profitPanel" );
	}
	
	// -------------------------------------------
	// 修改密码
	// -------------------------------------------
	public function editPwd() {
		// import ( "@.Library.Class.PermissionCheck" );
		// PermissionCheck::checkIsLogin ();
		if ($_POST) {
			if ($_POST ['newpass'] === $_POST ['newpass2']) {
				$uID = $_SESSION ['cmp'] ['id'];
				if ($this->checkPwd ( $uID, md5 ( addslashes ( $_POST ['oldpass'] ) ) )) {
					$m = new ManagerModel ();
					$data ['id'] = $uID;
					$data ['u_pwd'] = md5 ( $_POST ['newpass'] );
					$m->create ( $data );
					$c = $m->save ();
					if ($c > 0) {
						$this->ajaxReturn ( "success", "修改成功！", 1 );
					} else {
						$this->ajaxReturn ( "fail", "修改失败！", 0 );
					}
				} else {
					$this->ajaxReturn ( "fail", "初始密码错误！", 0 );
					
					// $this->ajaxReturn(array("fail"=>"原始密码错误"));
				}
			} else {
				$this->ajaxReturn ( "fail", "两次输入的密码不一致！", 0 );
			}
		}
		$this->display ( 'editWord' );
	}
	
	/**
	 * 验证密码
	 *
	 * @param 密码 $pwd        	
	 */
	protected function checkPwd($uID, $pwd) {
		$m = new ManagerModel ();
		$user = $m->where ( "id = {$uID} AND u_pwd = '{$pwd}'" )->find ();
		// var_dump($user);
		// exit();
		return $user ['u_name'] == "" ? false : true;
	}
	
	/**
	 * 系统用户列表
	 */
	public function managerList() {
		import ( "@.Library.Class.PageNormal" );
		$where = "g_manager = 1";
		$man = new ViewUserModel ();
		$managerArr = $man->where ( $where )->order ( "u_name" )->select ();
		// var_dump($man->getLastSql());
		$man = new UGroupModel ();
		$ug_select = $man->field ( "id,g_name" )->where ( "g_manager = 1" )->select ();
		$this->assign ( "ug_select", $ug_select );
		$this->assign ( "managerArr", $managerArr );
		$this->display ();
	}
	
	public function getDataGridData4ManagerList() {
		$mm = new ViewUserModel ();
		$where = "g_manager = 1"; // "1=1 ";
		$where .= $_POST ['u_name'] == "" ? "" : " AND id={$_POST['u_name']}";
		// $where .= $_POST ['u_company'] == "" ? "" : " AND u_company = {$_POST['cate_id']}";
		$where .= $_POST ['g_name'] == "" ? "" : " AND u_group = {$_POST['g_name']}";
		$where .= $_POST ['type'] == "" ? "" : " AND u_status = 0";
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->order ( 'u_logTime desc' )->limit ( $startP . "," . $endP )->select ();
		// var_dump($arr);
		for($i = 0; $i < count ( $arr ); $i ++) {
			$arr [$i] ['u_maney'] = $arr [$i] ['curr_maney'] . "/" . $arr [$i] ['avai_maney'];
			$arr [$i] ['u_where'] = $arr [$i] ['u_province'] . "/" . $arr [$i] ['u_city'];
		}
		// var_dump($mm->getLastSql());
		$res = array ();
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	
	public function managerEdit() {
		// var_dump("asdvasd");
		// exit();
		$m = new ManagerModel ();
		if ($_POST) {
			if($this->is_email($_POST['email']) === false){
				$this->ajaxReturn ( "fail", "填写的<strong>邮箱</strong>格式有误！", 0 );
			}
			if(empty($_POST['company'])){
				$this->ajaxReturn ( "fail", "请填写 <strong>公司名称</strong>！", 0 );
			}
			if(empty($_POST['manager'])){
				$this->ajaxReturn ( "fail", "请填写 <strong>联系人姓名</strong>！", 0 );
			}
			$data ['id'] = $_POST ['id'];
			$data ['u_name'] = $_POST ['name'];
			$data ['u_pwd'] = $_POST ['pwd'];
			if ($_POST ['isedit'] == "on" && ! empty ( $_POST ['city'] )) {
				$data ['u_province'] = $_POST ['province'];
				$data ['u_city'] = $_POST ['city'];
			}
			$data ['u_company'] = $_POST ['company'];
			$data ['u_tel'] = $_POST ['tel'];
			$data ['u_manager'] = $_POST ['manager'];
			$data ['u_phone'] = $_POST ['phone'];
			$data ['u_address'] = $_POST ['addr'];
			$data ['u_email'] = $_POST ['email'];
			$data ['u_group'] = $_POST ['u_group'];
			$d = $m->save ( $data );
			if ($d !== false) {
				$this->ajaxReturn ( "success", "修改成功！", 1 );
			} else {
				$this->ajaxReturn ( "fail", "修改失败！", 0 );
			}
		} // $gArr
		$rs = $m->where ( "id = {$_GET['id']}" )->find ();
		$man = new UGroupModel ();
		$ug_select = $man->field ( "id,g_name" )->where ( "g_manager = 1" )->select ();
		$this->assign ( "ug_select", $ug_select );
		$this->assign ( "gArr", $ug_select );
		$this->assign ( "rs", $rs );
		$this->display ( 'managerPanel' );
	}
	
	public function managerAdd() {
		// var_dump("asdvasd");
		// exit();
		$m = new ManagerModel ();
		if ($_POST) {
			if(empty($_POST['name'])){
				$this->ajaxReturn ( "fail", "请输入<strong>用户名</strong>！", 0 );
			}
			if(empty($_POST['pwd'])){
				$this->ajaxReturn ( "fail", "请输入<strong>密码</strong>！", 0 );
			}
			if(checkName($_POST ['name']) !== 1){
				$this->ajaxReturn ( "fail", "<strong>用户名</strong>应为5-20位，可以包含字母、数字、下划线！", 0 );
			}
			if(checkName($_POST ['pwd']) !== 1){
				$this->ajaxReturn ( "fail", "<strong>密码</strong>应为6-20位，可以包含字母、数字！", 0 );
			}
			if($this->is_email($_POST['email']) === false){
				$this->ajaxReturn ( "fail", "填写的<strong>邮箱</strong>格式有误！", 0 );
			}
			if(empty($_POST['company'])){
				$this->ajaxReturn ( "fail", "请填写 <strong>公司名称</strong>！", 0 );
			}
			if(empty($_POST['manager'])){
				$this->ajaxReturn ( "fail", "请填写 <strong>联系人姓名</strong>！", 0 );
			}
			$data ['id'] = $_POST ['id'];
			$data ['u_name'] = $_POST ['name'];
			$data ['u_pwd'] = $_POST ['pwd'];
			$data ['u_province'] = $_POST ['province'];
			$data ['u_city'] = $_POST ['city'];
			$data ['u_company'] = $_POST ['company'];
			$data ['u_tel'] = $_POST ['tel'];
			$data ['u_manager'] = $_POST ['manager'];
			$data ['u_phone'] = $_POST ['phone'];
			$data ['u_address'] = $_POST ['addr'];
			$data ['u_email'] = $_POST ['email'];
			$data ['u_group'] = $_POST ['u_group'];
			$data ['u_status'] = 1;
			$d = $m->add ( $data );
			if ($d !== false) {
				$this->ajaxReturn ( "success", "添加成功！", 1 );
			} else {
				$this->ajaxReturn ( "fail", "添加失败！", 0 );
			}
		} // $gArr
		$rs = $m->where ( "id = {$_GET['id']}" )->find ();
		$man = new UGroupModel ();
		$ug_select = $man->field ( "id,g_name" )->where ( "g_manager = 1" )->select ();
		$this->assign ( "ug_select", $ug_select );
		$this->assign ( "gArr", $ug_select );
		$this->assign ( "rs", $rs );
		$this->display ( 'managerPanel' );
	}
}
