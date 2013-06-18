<?php
class PreferAction extends ProtectedAction {
	public function __construct() {
		import ( "@.Library.Class.PermissionCheck" );
		PermissionCheck::checkIsLogin ();
	}
	public function getAllPM4Json() {
		$mm = new ViewProductMachineModel ();
		$count = $mm->field ( "COUNT(*) AS total" )->where ( "P.id = {$_GET['id']}" )->find ();
		$pageNum = isset ( $_POST ['page'] ) ? $_POST ['page'] : 1;
		$rows = $_POST ['rows'];
		$startP = $pageNum != 1 ? ($pageNum - 1) * $rows : 0;
		$endP = $rows;
		$arr = $mm->where ( "P.id = {$_GET['id']}" )->limit ( "{$startP},{$endP}" )->select ();
		// var_dump($mm->getLastSql());exit();
		$res ['total'] = $count ['total'];
		$res ['rows'] = $count ['total'] == 0 ? array () : $arr;
		echo json_encode ( $res );
	}
	/**
	 * 报价项目设置
	 */
	public function index() {
		$proM = new ProductModel();
		$mm = new ServiceModel ();
		$plm = new ProductLossModel ();
		$pnM = M ( 'pt_product_profit' );
		if ($_POST) {
			if (! empty ( $_POST ['service'] )) {
				$proid = $_POST ['id'];
				$d = $mm->where ( "product_id = {$proid}" )->delete ();
				if ($d !== false) {
					$data ['product_id'] = $proid;
					$data ['s_service'] = $_POST ['service'];
					$data ['s_shipTime'] = $_POST ['shipTime'];
					$c = $mm->add ( $data );
					if ($c > 0) {
						// $this->ajaxReturn("success","操作成功！",1);
					} else {
						// $this->ajaxReturn("fail","操作失败！",0);
					}
				} else {
					// $this->ajaxReturn("fail","数据库连接异常！",0);
				}
			}
			if (! empty ( $_POST ['service_Loss'] )) {
				$proid = $_POST ['id_Loss'];
				$d = $plm->where ( "product_id = {$proid}" )->delete ();
				if ($d !== false) {
					$data ['product_id'] = $proid;
					$data ['pl_min'] = $_POST ['pl_min'];
					$data ['pl_papers'] = $_POST ['baseNum'];
					$data ['pl_loss'] = $_POST ['baseLoss'];
					$data ['pl_colorNum'] = $_POST ['baseColor'];
					$c = $plm->add ( $data );
					if ($c > 0) {
						// $this->ajaxReturn("success","操作成功！",1);
					} else {
						// $this->ajaxReturn("fail","操作失败！",0);
					}
				} else {
					// $this->ajaxReturn("fail","数据库连接异常！",0);
				}
			}
			if (! empty ( $_POST ['plNum'] )) {
				$proid = $_POST ['id_Num'];
				$d = $pnM->where ( "product_id = {$proid}" )->delete ();
				if ($d !== false) {
					$data ['product_id'] = $proid;
					$data ['pl_min'] = $_POST ['pl_min'];
					$data ['pl_papers'] = $_POST ['baseNum'];
					$data ['pl_loss'] = $_POST ['baseLoss'];
					$data ['pl_colorNum'] = $_POST ['baseColor'];
					$c = $plm->add ( $data );
					if ($c > 0) {
						// $this->ajaxReturn("success","操作成功！",1);
					} else {
						// $this->ajaxReturn("fail","操作失败！",0);
					}
				} else {
					// $this->ajaxReturn("fail","数据库连接异常！",0);
				}
			}
		}
		$proRs = $proM->where("id = {$_GET['id']}")->find();
		$rs = $mm->where ( "product_id = {$_GET['id']}" )->find ();
		$rsLoss = $plm->where ( "product_id = {$_GET['id']}" )->find ();
		$numArr = $pnM->where ( "product_id = {$_GET['id']}" )->select ();
		$this->assign("proRs",$proRs);
		$this->assign ( "rs", $rs );
		$this->assign ( "rsLoss", $rsLoss );
		$this->assign ( "numArr", $numArr );
		$this->display ();
	}
	
	public function updataPinter() {
		if ($_POST) {
			$pm = new ProductMachineModel ();
			$type = $_POST ['type'];
			switch ($type) {
				case 'add' :
					if (isset ( $_POST ['idStr'] )) {
						$idArr = explode ( ",", $_POST ['idStr'] );
						$pm->startTrans ();
						for($i = 0; $i < count ( $idArr ); $i ++) {
							$rs = $pm->where ( "pm_product = {$_POST ['id']} AND pm_machine = {$idArr [$i]}" )->find ();
							if (! is_array ( $rs )) {
								$data = array ();
								$data ['pm_product'] = $_POST ['id'];
								$data ['pm_machine'] = $idArr [$i];
								$in = $pm->add ( $data );
								if ($in !== false) {
									if ($in > 0) {
									} else {
										$pm->rollback ();
										$this->ajaxReturn ( "fail", "操作失败！", 0 );
									}
								} else {
									$pm->rollback ();
									$this->ajaxReturn ( "fail", "数据库连接异常！", 0 );
								}
							}
						}
						$pm->commit ();
						$this->ajaxReturn ( "success", "操作成功！", 1 );
					}
					break;
				case 'del' :
					$d = $pm->where ( "pm_product = {$_POST['id']} AND pm_machine IN ({$_POST['idStr']})" )->delete ();
					if ($d !== false) {
						if ($d > 0) {
							$this->ajaxReturn ( "success", "操作成功！", 1 );
						} else {
							$this->ajaxReturn ( "fail", "操作失败！", 0 );
						}
					} else {
						$this->ajaxReturn ( "fail", "数据库连接异常！", 0 );
					}
					break;
				default :
					break;
			}
		}
		$this->display ( 'printerSelect' );
	}
	
	public function profitNumber(){
		$mm = M('pt_product_profit');
		if ($_POST) {
			$mm->startTrans ();
			$d = $mm->where ( "product_id = {$_POST['id_Num']}" )->delete();
			//$arr = $mm->where ( "group_id = {$_POST['id']}" )->select ();
			for($i = 0; $i < 5; $i ++) {
				$a = array();
				$a ['product_id'] = $_POST ['id_Num'];
				$a ['fi_min'] = $_POST ['min_' . $i];
				$a ['fi_max'] = $_POST ['max_' . $i];
				$a ['fi_percent'] = $_POST ['pes_' . $i];
				$a ['fi_value'] = $_POST ['val_' . $i];
				//$a ['fi_isNumber'] = 1;
				$d = $mm->add ( $a );
				if ($d === false) {
					$mm->rollback ();
					$this->ajaxReturn ( "fail", "操作中途出现错误！", 0 );
				}
			}
			$mm->commit ();
			$this->ajaxReturn ( "success", "保存成功！", 1 );
		}
		
		//$profitArr = $mm->where ( "group_id = {$_GET['id']}" )->select ();
		//$this->assign ( "profitArr", $profitArr );
		//$this->display ( "profitPanel" );
	}
}