<?php
class MyUserAction extends ProtectedAction {
	public function is_email($value) {
		return eregi ( '^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$', $value );
	}
	public function index() {
		$mm = new ManagerModel ();
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
			//$m = new ManagerModel ();
			$data = array();
			$data ['id'] = $_POST ['id'];
			if ($_POST ['isedit'] == "on" && !empty( $_POST ['city'] )) {
				$data ['u_province'] = $_POST ['province'];
				$data ['u_city'] = $_POST ['city'];
			}
			$data ['u_company'] = $_POST ['company'];
			$data ['u_tel'] = $_POST ['tel'];
			$data ['u_manager'] = $_POST ['manager'];
			$data ['u_phone'] = $_POST ['phone'];
			$data ['u_address'] = $_POST ['addr'];
			$data ['u_email'] = $_POST ['email'];
			$u = $mm->save($data);
			if ($u === false) {
				//var_dump($mm->getLastSql());
				$this->ajaxReturn ( "fail", "操作失败！", 0 );
			} else {
				$this->ajaxReturn ( "success", "修改成功！正在跳转中....", 1 );
			}
		}
		$rs = $mm->where ( "id = {$_SESSION['cmp']['id']}" )->find ();
		$this->assign ( "rs", $rs );
		$this->display ();
	}
	/**
	 * 充值记录
	 */
	public function recharge() {
		import ( "@.Library.Class.PageNormal" );
		$where = "1 = 1 AND u_id = {$_SESSION['cmp']['id']}";
		$w_date = "";
		if ($_GET ['dstart']) {
			$dstart = $_GET ['dstart'] == "" ? "" : $_GET ['dstart'] . " 00:00:00";
			$dend = $_GET ['dend'] == "" ? "" : $_GET ['dend'] . " 23:59:59";
			$w_date = " AND (re_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND re_date >= '{$dstart}'";
			}
		}
		$mm = new RechargeModel ();
		$where .= $w_date;
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
		$this->display ();
	}
	public function show() {
		if ($_GET ['type']) {
			$ch = new RecordModel ();
			$rsCh = $ch->field ( "o_id" )->where ( "id = {$_GET['id']}" )->find ();
			if ($rsCh ["o_id"] != "") {
				$mm = new OrderModel ();
				$rs = $mm->where ( "id = {$rsCh["o_id"]}" )->find ();
			}
		} else {
			$ch = new RecordModel ();
			$rsCh = $ch->field ( "re_id" )->where ( "id = {$_GET['id']}" )->find ();
			if ($rsCh ["re_id"] != "") {
				$mm = new RechargeModel ();
				$rs = $mm->where ( "id = {$rsCh["re_id"]}" )->find ();
			}
		}
		$this->assign ( "rs", $rs );
		$this->display ();
	}
	public function charge() {
		if ($_POST) {
			$data = array ();
			$data ['u_id'] = $_SESSION ['cmp'] ['id'];
			$bank_type = trim($_POST ['bank_type']);
			$account = trim($_POST ['account']);
			$data ['re_bankInfo'] = $bank_type. "(" . $account . ")";
			$data ['re_myBank'] =  trim($_POST ['my_account']);
			$data ['re_money'] =  trim($_POST ['money']);
			$data ['re_date'] = $_POST ['date'];
			$data ['re_subDate'] = date ( 'Y-m-d H:i:s' );
			$data ['re_desc'] = $_POST ['desc'];
			$manM = new UserManeyModel();
			$rs = $manM->where("user_id = {$data ['u_id']}")->find();
			$data ['re_overage'] = $rs['curr_maney'] == "" ? 0 : $rs['curr_maney'];
			if(empty($data ['re_date'])){
				$this->ajaxReturn ( "fail", "请填写 <strong>汇款日期</strong>", 0 );
			}
			if(empty($bank_type)){
				$this->ajaxReturn ( "fail", "请填写 <strong>账户类型</strong>", 0 );
			}
			if(empty($account)){
				$this->ajaxReturn ( "fail", "请填写 <strong>账户</strong>", 0 );
			}
			if(empty($data ['re_money'])){
				$this->ajaxReturn ( "fail", "请填写 <strong>充值金额</strong>", 0 );
			}
			$mm = new RechargeModel ();
			$d = $mm->add ( $data );
			if ($d !== false) {
				$this->ajaxReturn ( "success", "提交成功，请等待管理员审核。", 1 );
			} else {
				$this->ajaxReturn ( "fail", "提交失败。", 0 );
			}
		}
		$bmm = new BankInfoModel ();
		$brr = $bmm->where ( 'b_main = 1' )->select ();
		$this->assign ( "brr", $brr );
		$this->display ( 'panel' );
	}
	
	public function accountInfo(){
		$uid = $_SESSION['cmp']['id'];
		$mm = new ViewUserModel ();
		$rs = $mm->where ( "id = {$uid}" )->find ();
		
		$orderM = new OrderModel();
		$odTotalOver = $orderM->field("o_userId,Count(pt_order.id) AS total,Sum(pt_order.o_price) AS sumManey")->where("o_userId = {$uid} AND o_status = 'over'")->find();
		$odTotalIng = $orderM->field("o_userId,Count(pt_order.id) AS total,Sum(pt_order.o_price) AS sumManey")->where("o_userId = {$uid} AND o_status <> 'over' AND o_status <> 'cancel'")->find();
		$offerM = new OfferModel();
		$ofTotal = $offerM->field("u_id,Count(pt_offer.id) AS total")->where("u_id = {$uid}")->find();
		$this->assign ( "odTotalOver", $odTotalOver );
		$this->assign ( "odTotalIng", $odTotalIng );
		$this->assign ( "ofTotal", $ofTotal );
		$this->assign ( "rs", $rs );
		$this->display();
	}
}