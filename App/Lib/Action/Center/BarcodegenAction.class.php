<?php
class BarcodegenAction extends ProtectedAction{
	public function index(){
		ob_clean();
		$_text = $_GET['num'];
		//获取rfid关联信息
		//$rfid_id=$_GET['rfid_id'];
		//$view_rfid=new ViewProductRfidModel();
		//$rs=$view_rfid->where("PR.id={$rfid_id}")->find();
		//if($_GET['act']=="format"){
		//	$_text=$rs['product_format'];
		//}else{
		//	$_text=$rs['product_code'];
		//}
		//导入一维码类文件
		import("@.Library.BarCodeGen.class.BCGFontFile",NULL,".php");
		import("@.Library.BarCodeGen.class.BCGColor",NULL,".php");
		import("@.Library.BarCodeGen.class.BCGDrawing",NULL,".php");
		//导入一维码类型
		import("@.Library.BarCodeGen.class.BCGcode128#barcode",NULL,".php");
		//设置参数
		$colorFront = new BCGColor(0, 0, 0);
		$colorBack = new BCGColor(255, 255, 255);
		//$font = new BCGFontFile('Lib/Library/BarCodeGen/class/font/Arial.ttf', 9);
		//$font = new BCGFontFile(null);
		//配置条形码信息
		$code = new BCGcode128(); // Or another class name from the manual
		$code->setScale(2); // Resolution
		$code->setThickness(30); // Thickness
		$code->setForegroundColor($colorFront); // Color of bars
		$code->setBackgroundColor($colorBack); // Color of spaces
		$code->setFont(0); // Font (or 0)
		$code->parse($_text); // Text
		//画出条形码
		$drawing = new BCGDrawing('', $colorBack);
		$drawing->setBarcode($code);
		$drawing->draw();
		
		//打印出来
		header('Content-Type: image/png');
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
	}
}