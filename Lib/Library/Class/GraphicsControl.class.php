<?php
//================================
//
//       图片加文字、水印，缩放
//       版本：1.1
//       作者：武仝
//       日期：2011-04-11
//       更新记录：
//       V1.1   2012-08-29 重写了代码
//       V1.0   2011-04-11 第一版
//
//================================
class GraphicsControl{
	
	const UPPER_LEFT="upper_left";				//左上角
	const LOWER_LEFT="lower_left";				//左下角
	const UPPER_RIGHT="upper_right";			//右上角
	const LOWER_RIGHT="lower_right";			//右下角
	
	private $loadedPic;								//已经载入的图片
	public $picInfo=array();						//图片的信息
	public $position=array("x"=>0,"y"=>0);		//起始位置
	
	//================================
	//   功能：构造函数
	//   参数：$im resource#image
	//   返回：无
	//================================
	public function __construct($im){
		$this->loadedPic=$im;
		imagesavealpha($this->loadedPic,true);
		$this->getPicInfo();
	}
	
	//================================
	//   功能：获取并设置图片信息
	//   参数：无
	//   返回：无
	//================================
	private function getPicInfo(){
		$this->picInfo['width']=imagesx($this->loadedPic);
		$this->picInfo['height']=imagesy($this->loadedPic);
	}
	
	//================================
	//   功能：返回更改后的图片
	//   参数：无
	//   返回：resource image
	//================================
	public function getLoadedPic(){
		return $this->loadedPic;	
	}
	
	//================================
	//   功能：销毁图片
	//   参数：无
	//   返回：无
	//================================
	public function destroyPic(){
		@imagedestroy($this->loadedPic);	
	}
	
	//================================
	//   功能：添加文字
	//   参数：----------------
	//   $text   string    文字内容
	//   $size    int        字号
	//   $color  int        16进制
	//   返回：----------------
	//   array
	//================================
	public function setText($text="GraphicText.class.php",$size=9,$angle=0,$color=-0x00000000){
		$font=dirname(__FILE__)."./font/simsun.ttc";
		return imagettftext($this->loadedPic,$size,$angle,$this->position['x'],$this->position['y'],$color,$font,$text);
	}
	
	//================================
	//   功能：添加水印
	//   参数：----------------
	//   $im       resource    图片资源
	//   $alpha   int             透明度
	//   返回：----------------
	//   bool
	//================================
	public function setWaterMark($im){
		$imX=imagesx($im);
		$imY=imagesy($im);
		return imagecopyresampled($this->loadedPic,$im,$this->position['x'],$this->position['y'],0,0,$imX,$imY,$imX,$imY);
	}
	
	//================================
	//   功能：缩放图片
	//   参数：----------------
	//   $maxw  int    最大宽
	//   $maxh   int    最大高
	//   $scale   bool   是否等比例缩放
	//   返回：----------------
	//   bool
	//================================
	public function setThumb($maxw=200,$maxh=200,$scale=true){
		$width=$maxw;
		$height=$maxh;
		if($scale){
			if($this->picInfo['width']>$this->picInfo['height']){
				if($this->picInfo['width']>$maxw){
					$width=$maxw;
					$height=intval($this->picInfo['height']*$maxw/$this->picInfo['width']);
				}else{
					$width=$this->picInfo['width'];
					$height=$this->picInfo['height'];
				}
				if($height>$maxh){
					$width=intval($width*$maxh/$height);
					$height=$maxh;
				}
			}else{
				if($this->picInfo['height']>$maxh){
					$height=$maxh;
					$width=intval($this->picInfo['width']*$maxh/$this->picInfo['height']);
				}else{
					$width=$this->picInfo['width'];
					$height=$this->picInfo['height'];
				}
				if($width>$maxw){
					$height=intval($height*$maxw/$width);
					$width=$maxw;
				}
			}
		}
		$tmp=imagecreatetruecolor($width, $height);
		imagesavealpha($tmp,true);
		$status=imagecopyresampled($tmp, $this->loadedPic, 0, 0, 0, 0, $width, $height, $this->picInfo['width'], $this->picInfo['height']);
		$this->loadedPic=$tmp;
		return $status;
	}
	
	//================================
	//   功能：设置位置
	//   参数：----------------
	//   $type  string  类型，左上，右上，左下，右下
	//   $x       int       X 轴的边距
	//   $y       int       Y 轴的边距
	//   返回：----------------
	//   void
	//================================
	public function setPosition($type,$x,$y){
		switch($type):
			case self::UPPER_LEFT:
				$this->position['x']=$x;
				$this->position['y']=$y;
				break;
			case self::LOWER_LEFT:
				$this->position['x']=$x;
				$this->position['y']=$this->picInfo['height']-$y;
				break;
			case self::UPPER_RIGHT:
				$this->position['x']=$this->picInfo['width']-$x;
				$this->position['y']=$y;
				break;
			case self::LOWER_RIGHT:
				$this->position['x']=$this->picInfo['width']-$x;
				$this->position['y']=$this->picInfo['height']-$y;
				break;
			default:
				break;
		endswitch;
	}
	//================================
	//   功能：返回设置后DPI的图像字符串数据【仅针对JPG】
	//   参数：----------------
	//   $im  resource  图片
	//   $dpi  int  DPI
	//   返回：----------------
	//   string image
	//================================
	static public function setDPI($im,$dpi){
		ob_start();
		imagejpeg($im, null, 100);
		$bin = ob_get_contents();
		ob_end_clean();
		return (substr_replace($bin, pack("Cnn", 0x01, $dpi, $dpi), 13, 5));
	}
	
	//================================
	//   功能：提供地址返回图像资源
	//   参数：----------------
	//   $fileName  string  图片地址
	//   返回：----------------
	//   resource image
	//================================
	static public function loadPicture($fileName){
		$picString=(file_get_contents($fileName));
		return imagecreatefromstring($picString);
	}
}

//=========================================================================================
/*
header("Content-type: image/png");
//初始化类并载入图片
$im=new GraphicText(GraphicText::loadPicture('file:///C:/Users/Administrator/Desktop/B.png'));
//设置位置
$im->setPosition(GraphicText::LOWER_RIGHT,220,30);
//设置文本
$im->setText("无锡扬晟科技有限公司是一家提供专业物联网应用解决方案的高科技企业");
//设置位置
$im->setPosition(GraphicText::LOWER_RIGHT,100,100);
//载入图片
$im2=GraphicText::loadPicture ('file:///C:/Users/Administrator/Desktop/qq.png');
//设置水印
$im->setWaterMark($im2);
$im->setThumb(600,300,false);
//显示图片
imagepng($im->getLoadedPic());
//销毁图片
$im->destroyPic();
*/