<?php
class StatisticsAction extends ProtectedAction {
	public function index() {
		$this->offerList ();
	}
	private function getUsers() {
		$mm = new ViewUserModel ();
		$Uarr = $mm->select (); // ->where("G.g_manager = 0")
		return $Uarr;
	}
	public function offerList() {
		$Uarr = $this->getUsers ();
		$this->assign ( "Uarr", $Uarr );
		$this->display ();
	}
	public function getOfferListJson() {
		$mm = new ViewOfferModel ();
		$where = "1=1"; // u_id = {$_SESSION['cmp']['id']}";
		$w_date = "";
		if ($_POST ['dstart']) {
			$dstart = $_POST ['dstart'] == "" ? "" : $_POST ['dstart'] . " 00:00:00";
			$dend = $_POST ['dend'] == "" ? "" : $_POST ['dend'] . " 23:59:59";
			$w_date = " AND (O.o_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND O.o_date >= '{$dstart}'";
			}
		}
		$w_user = $_POST ['uName'] == "" ? "" : " AND O.u_id = '{$_POST ['uName']}'";
		$where .= $w_date . $w_user;
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->order ( 'O.o_date desc' )->limit ( "{$startP},{$endP}" )->select ();
		// var_dump($mm->getLastSql());
		for($i = 0; $i < count ( $arr ); $i ++) {
			$arr [$i] ['userInfo'] = $arr [$i] ['u_name'] . " (" . $arr [$i] ['g_name'] . ")";
			$arr [$i] ['o_perPrice'] = getCheckNum4Float ( $arr [$i] ['o_price'] / $arr [$i] ['o_amount'] );
			$arr [$i] ['o_date'] = date ( "Y-m-d H:i", strtotime ( $arr [$i] ['o_date'] ) );
		}
		$res = array ();
		// $count = count($arr);
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	
	public function orderList() {
		$Uarr = $this->getUsers ();
		$this->assign ( "Uarr", $Uarr );
		$this->display ();
	}
	
	public function getOrderListJson() {
		$mm = new ViewOrderModel ();
		$where = "1=1 AND O_status = 'over'"; // ->where("g_manager = 0")
		$dstart = date ( "Y-m-d", strtotime ( "-30 day" ) );
		$w_date = "AND o_date >= '{$dstart}'";
		if ($_POST ['dstart']) {
			$dstart = $_POST ['dstart'] == "" ? "" : $_POST ['dstart'] . " 00:00:00";
			$dend = $_POST ['dend'] == "" ? "" : $_POST ['dend'] . " 23:59:59";
			$w_date = " AND (o_date between '{$dstart}' AND '{$dend}')";
			if ($dend == "") {
				$w_date = " AND o_date >= '{$dstart}'";
			}
		}
		$w_user = $_POST ['uName'] == "" ? "" : " AND o_userId = '{$_POST ['uName']}'";
		$where .= $w_date . $w_user;
		$count = $mm->field ( "COUNT(*) AS total" )->where ( $where )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( $where )->limit ( "{$startP},{$endP}" )->select ();
		import ( "@.Library.Class.Check" );
		for($i = 0; $i < count ( $arr ); $i ++) {
			$arr [$i] ['userInfo'] = $arr [$i] ['u_name'] . " (" . $arr [$i] ['g_name'] . ")";
			$arr [$i] ['o_status'] = $arr [$i] ['o_isDelete'] == 1 ? '<font color="#666">已取消</font>' : Check::checkOrderStatus ( $arr [$i] ['o_status'] );
			$arr [$i] ['o_date'] = date ( "Y-m-d H:i", strtotime ( $arr [$i] ['o_date'] ) );
		}
		$res = array ();
		// $count = count ( $arr );
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	
	
	
	public function chartAdmin() {
		$this->display ();
	}
}