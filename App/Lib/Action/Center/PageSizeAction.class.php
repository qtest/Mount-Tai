<?php
class PageSizeAction extends ProtectedAction{

	public function __construct() {
		import ( "@.Library.Class.PermissionCheck" );
		PermissionCheck::checkIsLogin ();
	}
	public function getAllPaperSize4Json() {
		$mm = new PaperSizeModel ();
		$arr = $mm->select ();
		//$res['total'] = count($arr);
		//$res['rows'] = $arr;
		echo json_encode($arr);
	}
	
	public function getPaperSize4Json($idStr) {
		$mm = new PaperSizeModel ();
		$arr = $mm->where("id IN {'$idStr'}")->select ();
		//$res['total'] = count($arr);
		//$res['rows'] = $arr;
		echo json_encode($arr);
	}
	
	public function index(){
		$this->display();
	}
}