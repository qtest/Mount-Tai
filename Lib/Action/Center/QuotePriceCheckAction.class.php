<?php
/**
 * 价格计算
 * 
 * @author Administrator
 *
 */
class QuotePriceCheckAction extends ProtectedAction {
	/**
	 * 报价项目ID
	 *
	 * @var int
	 */
	protected $product_id = 0;
	/**
	 * 最终价格
	 *
	 * @var float
	 */
	protected $finalPrice = 0;
	/**
	 * 用全张纸数量
	 *
	 * @var int
	 */
	protected $paperAmount = 0;
	/**
	 * 用户要求数量
	 *
	 * @var int
	 */
	protected $customerNumber = 0;
	/**
	 * 成品单双面
	 *
	 * @var int
	 */
	protected $sides = 0;
	/**
	 * 印刷颜色
	 *
	 * @var int
	 */
	protected $colors = 0;
	/**
	 * 每本页数
	 *
	 * @var int
	 */
	protected $perPages = 0;
	/**
	 * 每本页数是否自定义
	 *
	 * @var int
	 */
	protected $perPagesIsDiy = false;
	/**
	 * 联数
	 *
	 * @var int
	 */
	protected $perJoins = 0;
	/**
	 * 计算得出的最优开数
	 *
	 * @var int
	 */
	protected $kaishu = 0;
	/**
	 * 全张纸类型(大度/正度)
	 *
	 * @var string
	 */
	protected $paperType = "";
	/**
	 * 所选材料信息
	 *
	 * @var array
	 */
	protected $paperInfo = array ();
	/**
	 * 选用印刷机信息
	 * 
	 * @var array
	 */
	protected $printerInfo = array();
	/**
	 * 成品长度
	 *
	 * @var int
	 */
	protected $paperLength = 0;
	/**
	 * 成品大小（长×宽）
	 *
	 * @var string
	 */
	protected $paperSize = "";
	/**
	 * 成品宽度
	 *
	 * @var int
	 */
	protected $paperWidth = 0;
	/**
	 * 放张数
	 *
	 * @var int
	 */
	protected $lossPaper = 0;
	/**
	 * 每张纸浪费
	 *
	 * @var int
	 */
	protected $lossPerPaper = 0;
	/**
	 * 报价项目基础信息
	 *
	 * @var array
	 */
	protected $productInfo = array ();
	/**
	 * 后加工费用
	 *
	 * @var float
	 */
	protected $processPrice = 0;
	/**
	 * 后加工最低费用
	 *
	 * @var float
	 */
	protected $processMinFee = 0;
	/**
	 * 纸张价格
	 *
	 * @var float
	 */
	protected $paperPrice = 0;
	/**
	 * 印工
	 *
	 * @var int
	 */
	protected $printWorks = 0;
	/**
	 * 印工费
	 *
	 * @var float
	 */
	protected $printWorkFee = 0;
	/**
	 * 版费
	 *
	 * @var float
	 */
	protected $versionCost = 0;
	/**
	 * 项目数量利润比例差价
	 * 
	 * @var float
	 */
	protected $numberProfit = 0;
	/**
	 * 会员组利润值差价
	 * 
	 * @var float
	 */
	protected $groupProfit = 0;
	/**
	 * post原始数据
	 *
	 * @var array
	 */
	protected $post = array ();
	/**
	 * 报价项目基础属性
	 *
	 * @var array
	 */
	protected $attrArr = array ();
	/**
	 * 报价项目基础后加工
	 *
	 * @var array
	 */
	protected $prcArr = array ();
	/**
	 * 存储数据用的数组
	 * @var array
	 */
	protected $dataSave = array();
	/**
	 * 最近错误信息
	 *
	 * @var string
	 */
	protected $error = '';
	public function __construct($post = '') {
		if (empty ( $post )) {
			$post = $_POST;
		} elseif (is_object ( $post )) {
			$post = get_object_vars ( $post );
		}
		// 验证数据
		if (empty ( $post ) || ! is_array ( $post )) {
			$this->error = '数据无效';
			return false;
		}
		$this->product_id = $post ['id'];
		$pmm = new ViewProductModel ();
		$this->productInfo = $pmm->where ( "id={$post['id']}" )->find ();
		if (! is_array ( $this->productInfo )) {
			$this->error = "获取报价项目信息失败";
			return false;
		}
		$this->post = $post;
		// 查找绑定的所有基本属性
		$pa = new ProductAttributeModel ();
		$this->attrArr = $pa->where ( "pa_product = {$this->product_id}" )->select ();
		// 查找绑定的后加工工序
		$ppro = new ProductProcessModel ();
		$this->prcArr = $ppro->where ( "pp_product = {$this->product_id} AND pp_process <> 0" )->select (); 
		// 数量
		$this->customerNumber = $post ['2']; 
		$paperM = new PaperModel ();
		$weP = "id = {$post['37']}";
		if (! empty ( $post ['kz_37'] )) {
			$weP = "id = {$post['kz_37']}";
		}
		$this->paperInfo = $paperM->where ( $weP )->find ();
		
		$attrM = new AttributeModel ();
		// 单双面
		if (! empty ( $post ['3'] )) {
			$attrRs = $attrM->where ( "id = {$post['3']}" )->find ();
			$this->sides = $attrRs ['a_value'];
		}
		// 印刷颜色,3色及以上统一为4色
		if (! empty ( $post ['4'] )) {
			$attrRs = $attrM->where ( "id = {$post['4']}" )->find ();
			$this->colors = $attrRs ['a_value'] >= 3 ? 4 : $attrRs ['a_value'];
		}
		// 每本页数
		if (! empty ( $post ['10'] )) {
			$attrRs = $attrM->where ( "id = {$post['10']}" )->find ();
			$this->perPages = $attrRs ['a_value'];
		}elseif($post['isdiy_10'] == 1){
			$this->perPages = $post['num_10'];
			$this->perPagesIsDiy = true;
		}
		// 联数
		if (! empty ( $post ['11'] )) {
			$attrRs = $attrM->where ( "id = {$post['11']}" )->find ();
			$this->perJoins = $attrRs ['a_value'];
		}
		//验证相关数据是否为空
		$attrM = new AttributeModel ();
		foreach ( $this->attrArr as $rs ) {
			$res = true;
			$attrid = $rs['pa_attribute'];
			$name = $attrM->where ( "id = {$attrid}" )->find ();
			if (empty ( $post ['' . $attrid] )) {
				//尺寸是否为自定义，判断自定义的长宽是否为空，为空跳出并提示
				if($name ['a_name'] == "尺寸"){
					if ($this->post ['isdiy'] == 1 && !empty($this->post ['xx']) && !empty($this->post ['yy'])) {
						$res = false;
					}
					//不干胶
					if ($this->productInfo ['p_type'] == 2 && ! empty ( $post ["length"] ) && ! empty ( $post ["width"] )) {
						$res = false;
					}
				}
				//无碳，自定义的是否为空
				if($name ['a_name'] == "每本页数" && $this->perPagesIsDiy === true && !empty($this->perPages)){
					$res = false;
				}
				if($res){
					$this->ajaxReturn ( "fail", "<b>" . $name ['a_name'] . "</b> 不能为空", 0 );
				}
			}
			if($this->productInfo['p_type'] == 1 && strpos($name['a_name'],'纸张') !== false && empty($post['kz_'.$name['id']])){
				$this->ajaxReturn ( "fail", "请选择 <b>纸张克重</b>", 0 );
			}
		}
		$this->getPaperSize ();
		$this->getRealMachine();
		switch ($this->productInfo ['p_type']) {
			case 1 :
				$this->getPriceNormal ();
				break;
			case 2 :
				$this->getPriceBuganjiao ();
				break;
			case 3 :
				//$this->getPriceWutan ();
				$this->getPriceWutan();
				break;
			case 4 :
				break;
			default :
				break;
		}
		$this->getData4Sql();
		// 后道费用和印刷机有最低最低消费额或开机费，应放在最后来计算
		// $this->getProcessPrice ();
		// return $post;
	}
	public function init() {
	}
	/**
	 * 纸张材料价格
	 * 
	 * @return unknown
	 */
	public function getPaperPrice() {
		return $this->paperPrice;
	}
	/**
	 * 全张纸数量
	 * 
	 * @return unknown
	 */
	public function getPaperAmount() {
		return $this->paperAmount;
	}
	/**
	 * 放张数
	 * 
	 * @return unknown
	 */
	public function getLossPaper() {
		return $this->lossPaper;
	}
	/**
	 * 报价项目ID
	 * 
	 * @return unknown
	 */
	public function getProductId() {
		return $this->product_id;
	}
	/**
	 * 最终价格
	 * 
	 * @return unknown
	 */
	public function getFinalPrice() {
		return $this->finalPrice;
	}
	/**
	 * 后加工费用
	 * 
	 * @return unknown
	 */
	public function getProcessFee() {
		return $this->processPrice;
	}
	/**
	 * 印工数
	 * @return unknown
	 */
	public function getPrintWorks() {
		return $this->printWorks;
	}
	/**
	 * 印工费
	 * @return unknown
	 */
	public function getPrintWorkFee() {
		return $this->printWorkFee;
	}
	/**
	 * 版费
	 * @return unknown
	 */
	public function getVersionCost() {
		return $this->versionCost;
	}
	/**
	 * 开数
	 * @return unknown
	 */
	public function getKaishuNum() {
		return $this->kaishu;
	}
	/**
	 * 联数
	 * 
	 * @return number
	 */
	public function getPerJoinsNum(){
		return $this->perJoins;
	}
	/**
	 * 项目数量利润比例差价
	 * 
	 * @return number
	 */
	public function getNumberProfit(){
		return $this->numberProfit;
	}
	/**
	 * 会员组利润值差价
	 * 
	 * @return number
	 */
	public function getGroupProfit(){
		return $this->groupProfit;
	}
	/**
	 * 全张纸类型(大度/正度)
	 * 
	 * @return string
	 */
	public function getPaperType(){
		return $this->paperType;
	}
	
	/**
	 * 成品尺寸
	 * @return unknown
	 */
	public function getPaperSizeInfo() {
		return $this->paperSize;
	}
	/**
	 * 选用印刷机信息
	 * @return array
	 */
	public function getPrinterInfo() {
		return $this->printerInfo;
	}
	
	/**
	 * 组合数组
	 * @return unknown
	 */
	public function getDataArray(){
		return $this->dataSave;
	}

	/**
	 * 返回模型的错误信息
	 * @return unknown
	 */
	public function getError() {
		return $this->error;
	}
	
	/**
	 * 普通类型的报价计算
	 * 纸张金额 + 印工费用 + 版费 + 后加工
	 */
	protected function getPriceNormal() {
		$this->getAmountPaperNumber ();
		$this->getFormerPrice ();
		$this->comPrintWork ();
		$this->comVersionCost ();
		$this->getProcessPrice ();
		$this->finalPrice = $this->paperPrice + $this->printWorkFee + $this->versionCost + $this->processPrice;
	}
	/**
	 * 不干胶类型的报价计算
	 */
	protected function getPriceBuganjiao() {
		// $this->getPaperSize ();
	}
	/**
	 * 无碳类型的报价计算
	 */
	protected function getPriceWutan() {
		$this->getAmountPaperNumber ();
		$this->getWuTanPaperPrice();
		$this->comPrintWork ();
		$this->comVersionCost ();
		$this->getProcessPrice ();
		$this->finalPrice = $this->paperPrice + $this->printWorkFee + $this->versionCost + $this->processPrice;
	}
	/**
	 * 计算印工及费用,印工：印刷机每千印/色/单面/联为一个印工
	 * 单色、双色1个印工 = 1000张/色 1个印工 = 15元 (印工与纸张数量、尺寸、联数、颜色、单双面有关)
	 * 四色印工，1个印工为40元
	 *
	 * 多少张8开 / 1000 = 多少印工
	 */
	protected function comPrintWork() {
		// $p = $this->post;
		$pages = $this->paperAmount - $this->lossPaper;
		// 印工数
		$works = $this->colors >= 3 ? ceil($pages * 8 / 1000) : ceil($pages / $this->perJoins * 8 / 1000);
		// 单双面
		if (! empty ( $this->sides )) {
			$works = $works * $this->sides;
		}
		// 颜色
		if(! empty ( $this->colors ) && $this->colors < 3){
			$works = $works * $this->colors;
		}
		// 联数
		if (! empty ( $this->perJoins ) && $this->colors < 3) {
			$works = $works * $this->perJoins;
		}
		if(! empty ( $this->colors ) && $this->colors >= 3){
			$works = $works <= 3 ? 0 : $works - 3;
		}
		$this->printWorks = $works;
		$this->printWorkFee = $this->colors >= 3 ? $works * 40 : $works * 15;
	}
	/**
	 * 计算版费
	 *
	 * 版费金额=（单双面 + 印刷颜色 + 联数） * 10元/张版费
	 *
	 * 印刷颜色超过双色（不含）即为四色印刷，四色印刷另加固定版费
	 *
	 * 单色、双色印刷的一块版子为10元/色 (版费与印刷颜色、联数、单双面有关)
	 * 四色印刷的版费为：250元
	 */
	protected function comVersionCost() {
		$a = $this->sides == 1 ? 0 : $this->sides;
		$b = $this->colors == 1 ? 0 : $this->colors;
		$this->versionCost = $this->sides * $this->colors * $this->perJoins * 10;
		if ($this->colors >= 3) {
			$this->versionCost = 250;
		}else if($this->colors == 2){
			//双色额外加25改色费
			$this->versionCost += 25;
		}
	}
	
	/**
	 * 计算后道费用
	 */
	protected function getProcessPrice() {
		//$prFin = 0;//$this->paperPrice + $this->printWorkFee + $this->versionCost;
		$processMinFee = 0; // 后加工最低费用
		$price = 0; // 后加工费用
		$p = $this->post;
		$mm = new ProcessModel ();
		$proA = $this->prcArr;
		// 遍历系统设置的初始信息
		foreach ( $proA as $row ) {
			//$prFin += $price;
			$pnow = 0;
			// $rs = $mm->where ( "id = {$row['pp_process']}" )->find ();
			// 生成网页元素对应id
			$ptName = "pro_" . $row ['pp_process'];
			// /if (! empty ( $rs ['combo_name'] )) {
			// $ptName = "pro_" . $rs ['combo_name'];
			// }
			// 获取网页提交的内容
			$id = $p ["{$ptName}"];
			// 判断是否选中
			if (! empty ( $id )) {
				// 查找对应id完整信息
				$prcRs = $mm->where ( "id = {$id}" )->find ();
				//var_dump($this->colors);
				if($prcRs['p_name'] == "打号码" && $this->colors <= 2){
					continue;
				}
				// 查找对应id子属性信息
				$prcAttrRs = $mm->where ( "parent_id = {$id}" )->select ();
				// 如果子属性存在
				if (is_array ( $prcAttrRs ) && ! empty ( $p ['pro_attr_' . $prcRs ['id']] )) {
					// 使用选择的子属性价格设置计算
					// 单价
					//$pnow += ceil($this->paperAmount / 1000) * $prcAttrRs ['p_price']; 
					$pnow += $this->checkProcess4Unit($prcAttrRs);//$this->customerNumber * $prcAttrRs ['p_price'];
					// 其他费用
					$pnow += $prcAttrRs ['p_otherFee']; 
					//判断最低消费
					if ($pnow < $prcAttrRs ['p_minFee']) {
						//低于最低，按最低计算
						$pnow = $prcAttrRs ['p_minFee'];
					}
				} else {
					// 单价（单位：元/千张）
					//$pnow += ceil($this->paperAmount / 1000) * $prcRs ['p_price'];
					$pnow += $this->checkProcess4Unit($prcRs);// $this->customerNumber * $prcRs ['p_price'];
					// 其他费用
					$pnow += $prcRs ['p_otherFee'];
					//判断最低消费
					if ($pnow < $prcRs ['p_minFee']) {
						//低于最低，按最低计算
						$pnow = $prcRs ['p_minFee'];
					}
				}
			}
			$price += $pnow;
		}
		$this->processPrice = $price;
		//$this->processMinFee = $processMinFee;
	}
	/**
	 * 根据单位计算后加工价格
	 * 
	 * @param array $rs 后加工信息
	 * @return number
	 */
	protected function checkProcess4Unit($rs){
		$price = 0;
		//印 千印 本 平米(㎡)
		switch($rs['p_unit']){
			case '印':
				$price = $this->paperAmount * $rs ['p_price'];
				break;
			case '千印':
				$price = $this->paperAmount / 1000 * $rs ['p_price'];
				break;
			case '本':
				$price = $this->customerNumber * $rs ['p_price'];
				break;
			case '平米(㎡)':
				$square = $this->paperLength * $this->paperWidth / 10000;
				$price = $square * $rs ['p_price'];
				break;
		}
		return $price;
	}
	
	/**
	 * 获得用纸信息
	 *
	 * @param string $attrArr        	
	 * @return array
	 */
	protected function getPaperSize($attrArr = '') {
		if (empty ( $attrArr )) {
			// $attrArr = $this->attrArr;
		}
		$res = array ();
		$papSize = "";
		$type = "";
		$length = 0;
		$width = 0;
		if (! empty ( $this->post ['1'] )) {
			$pam = new PaperSizeModel ();
			$rs = $pam->where ( "id = {$this->post['1']}" )->find ();
			$papSize = $rs ['s_name'];
			$type = $rs ['s_paperType'];
			$num = $rs ['s_num'];
			$length = $rs ['s_length'];
			$width = $rs ['s_width'];
		}
		if ($this->post ['isdiy'] == 1) {
			$papSize = $this->post ['xx'] . "x" . $this->post ['yy'];
			$length = $this->post ['xx'];
			$width = $this->post ['yy'];
			$tnArr = $this->checkPaperSize ( $length, $width );
			$type = $tnArr ['type'];
			$num = $tnArr ['num'];
		}
		if (! empty ( $this->post ['length'] ) && ! empty ( $this->post ['width'] )) {
			$papSize = $this->post ['length'] . "x" . $this->post ['width'];
			$length = $this->post ['length'];
			$width = $this->post ['width'];
			$type = "不干胶";
			// $this->checkPaperSize($length, $width);
		}
		$this->paperSize = $papSize;
		$this->kaishu = $num;
		$this->paperType = $type;
		$this->paperLength = $length;
		$this->paperWidth = $width;
		
		$res ['papSize'] = $papSize;
		$res ['type'] = $type;
		$res ['num'] = $num;
		$res ['length'] = $length;
		$res ['width'] = $width;
		return $res;
	}
	
	/**
	 * 获得应该使用的印刷机
	 * 
	 * 先查找绑定的机器，如没有，查找设定的通用印刷机。
	 * 
	 * 先根据尺寸选择印刷机，再通过颜色来判别
	 * 
	 */
	protected function getRealMachine(){
		$col = $this->colors > 2 ? "彩色" : "单色";
		$pm = new ViewProductMachineModel ();
		$arr = $pm->where ( "p.id = {$this->product_id} AND M.m_color = '{$col}'" )->select ();
		$n = 0;
		$maRs = array();
		$realS = $this->paperLength * $this->paperWidth;
		
		if(is_array($arr)){
			//已绑定印刷机
			foreach($arr as $k=>$v){
				$s = $v['m_maxLength'] * $v['m_maxWidth'];
				if($s > $realS){
					if($n == 0){
						$n = $s;
						$maRs = $v;
						continue;
					}
					//取最小值
					//var_dump($maRs);
					if($n != min($n,$s)){
						$n = min($n,$s);
						$maRs = $v;
					}
				}
			}
		}else{
			$mm = new MachineModel();
			$arr = $mm->where("m_color = '{$col}'")->select();
			foreach($arr as $k=>$v){
				$s = $v['m_maxLength'] * $v['m_maxWidth'];
				if($s > $realS){
					if($n == 0){
						$n = $s;
						$maRs = $v;
						continue;
					}
					//取最小值
					if($n != min($n,$s)){
						$n = min($n,$s);
						$maRs = $v;
					}
				}
			}
		}
		$this->printerInfo = $maRs;
	}
	
	/**
	 * 计算用全张纸数量【通用】
	 *
	 * 基础用纸数 + 放张数
	 *
	 * 基础用纸数 = 数量÷开数 【小数进1】
	 * 放张数 = 1.取整(基础用纸数 / 放张基数) x 放张数 + (基础用纸数 % 放张基数) x 放张数 ÷ 放张基数
	 * 2.颜色比例放张 = （颜色数-1）x 颜色放张比例基数
	 */
	protected function getAmountPaperNumber() {
		$mm = new ProductLossModel ();
		$rs = $mm->where ( "product_id = {$this->product_id}" )->find ();
		$attrM = new AttributeModel ();
		// $pageRs = $attrM->where("id = {$this->perPages}")->find();
		$cusNum = empty ( $this->perPages ) ? $this->customerNumber : $this->customerNumber * $this->perPages;
		//$cusNum = empty ( $this->perJoins ) ? $cusNum : $cusNum / $this->perJoins;
		$baseNum = ceil ( $cusNum / $this->kaishu ); // 理论纸张数量，不足进1
		if($baseNum < $rs ['pl_papers']){
			$lossA1 = 0;
			$lossA2 = $baseNum * $rs ['pl_loss'] / $rs ['pl_papers']; 
		}else{
			$lossA1 = floor ( $baseNum / $rs ['pl_papers'] ) * $rs ['pl_loss'];
			// $lossA2 = $baseNum - $baseNum % $rs['pl_papers'] == 0 ? 0 : $rs['pl_papers'];//多出来的加一份放张
			$lossA2 = ($baseNum % $rs ['pl_papers']) * $rs ['pl_loss'] / $rs ['pl_papers']; // 按比例计算多余放张
		}
		//$lossA1 = floor ( $baseNum / $rs ['pl_papers'] ) * $rs ['pl_loss'];
		//var_dump($lossA1);
		// $lossA2 = $baseNum - $baseNum % $rs['pl_papers'] == 0 ? 0 : $rs['pl_papers'];//多出来的加一份放张
		//$lossA2 = ($baseNum % $rs ['pl_papers']) * $rs ['pl_loss'] / $rs ['pl_papers']; // 按比例计算多余放张
		$lossA = $lossA1 + ceil ( $lossA2 ); // 报价项目基础放张计算
		$lossB = 0;
		// 颜色数
		if (! empty ( $this->colors )) {
			//$attM = new AttributeModel ();
			//$attRs = $attM->where ( "parent_id = 4 AND id = {$this->colors}" )->find ();
			$lossB = ($this->colors - 1) * $rs ['pl_colorNum'];
		}
		$l = $lossA + $lossB;
		$this->lossPaper = $l < $rs ['pl_min'] ? $rs ['pl_min'] : $l;//最低放张数比较
		$this->paperAmount = $baseNum + $this->lossPaper;
	}
	
	/**
	 * 纸价【通用】
	 * 计算：单张纸价格×纸张数量
	 * 单张纸价格=全张长(mm)x全张宽(mm)÷1000000x克重x吨价÷1000000
	 *
	 * @return number
	 */
	protected function getFormerPrice() {
		//默认为正度纸
		$normal = getZhengduSize ();
		$pPrice = $this->paperInfo ['m_price'];
		//如果选用的大度纸
		if($this->paperType == "大度"){
			$normal = getDaduSize ();
			//两种价格是否相同同，则选用正度价格，否则选用大度价格
			$pPrice = $this->paperInfo ['m_dPrice'] == "" ? $this->paperInfo ['m_price'] : $this->paperInfo ['m_dPrice'];
		}
		//$normal = $this->paperType == "大度" ? getDaduSize () : getZhengduSize ();
		$area = $normal ['length'] * $normal ['width'] / 1000000; // 单张纸面积（平方米）
		$paperNum = $this->paperAmount;
		// var_dump($paperNum);
		$dunMin = $pPrice / 1000000; // 吨价转化为克价格
		$this->paperPrice = $area * $this->paperInfo ['m_name'] * $dunMin * $paperNum;
	}
	
	/**
	 * 无碳纸价格计算
	 * 公式:（本数*页数/联数+放张）/500*纸张令价
	 * 要分别乘以上中下纸价，如两联*上下，三联*上中下，四联*上中中下，五联*上中中中下之和
	 */
	protected function getWuTanPaperPrice(){
		//默认两联 
		$this->perJoins = $this->perJoins == "" ? 2 : $this->perJoins;
		$type = $this->paperType == "大度" ? true : false;
		//查找无碳的纸信息
		$paperM = new PaperModel ();
		$arr = $paperM->where("type_id = 3")->select();
		//var_dump($arr);
		$upPrice = 0;$cenPriceN = 0;$lowPrice = 0;
		foreach ($arr as $rs){
			if(strpos($rs['m_name'],'上') !== false){
				$upPrice = $this->paperType == "大度" ? $rs['m_dPrice'] : $rs['m_price'];
			}else if(strpos($rs['m_name'],'中') !== false){
				$cenPriceN = $this->paperType == "大度" ? $rs['m_dPrice'] : $rs['m_price'];
			}else if(strpos($rs['m_name'],'下') !== false){
				$lowPrice = $this->paperType == "大度" ? $rs['m_dPrice'] : $rs['m_price'];
			}
		}
		//上纸价格
		$upPrice = $this->paperAmount / $this->perJoins / 500 * $upPrice;
		//下纸价格
		$lowPrice = $this->paperAmount / $this->perJoins / 500 * $lowPrice;
		$cenPrice = 0;
		for ($i = 1;$i < ($this->perJoins - 2 + 1);$i++){
			$cenPrice += $this->paperAmount / $this->perJoins / 500 * $cenPriceN;
		}
		
		$this->paperPrice = $upPrice + $lowPrice + $cenPrice;
	}
	
	/**
	 * 先计算竖排方式的成品开数=(大纸宽度/成品的宽度)X(大纸长度/成品长度)，
	 * 再计算横排方式的成品开数=(大纸宽度/成品的长度)X(大纸长度/成品宽度),
	 * 然后取横排和竖排结果中的最大数为大纸最优开数。
	 * 按以上方法分别计算大度和正度尺寸的大纸最优开数，
	 * 然后对比取出纸张浪费率最小的大纸最优开数就是最优的成品开数了。
	 * 纸张浪费率：纸张浪费率＝(（大纸宽度X大纸长度）-（成品宽度X成品长度）X成品开数) X100%
	 *
	 * @param 成品长 $length        	
	 * @param 成品宽 $width        	
	 */
	protected function checkPaperSize($length, $width) {
		$arr = array ();
		$zhengdu = getZhengduSize ();
		$dadu = getDaduSize ();
		// 大度纵向
		$daduVertical = $this->getKaishu ( $dadu ['length'], $length, $dadu ['width'], $width );
		$lossa = $zhengdu ['length'] * $zhengdu ['width'] - $width * $length * $daduVertical;
		$res = array (
				"type" => "大度",
				"num" => $daduVertical,
				"loss" => $lossa 
		);
		array_push ( $arr, $res );
		// 大度横向
		$daduHorizontal = $this->getKaishu ( $dadu ['length'], $width, $dadu ['width'], $length );
		$loss = $zhengdu ['length'] * $zhengdu ['width'] - $width * $length * $daduHorizontal;
		array_push ( $arr, array (
				"type" => "大度",
				"num" => $daduHorizontal,
				"loss" => $loss 
		) );
		// 正度纵向
		$zhengduVertical = $this->getKaishu ( $zhengdu ['length'], $length, $zhengdu ['width'], $width );
		$loss = $zhengdu ['length'] * $zhengdu ['width'] - $width * $length * $zhengduVertical;
		array_push ( $arr, array (
				"type" => "正度",
				"num" => $zhengduVertical,
				"loss" => $loss 
		) );
		// 正度横向
		$zhengduHorizontal = $this->getKaishu ( $zhengdu ['length'], $width, $zhengdu ['width'], $length );
		$loss = $zhengdu ['length'] * $zhengdu ['width'] - $width * $length * $zhengduHorizontal;
		array_push ( $arr, array (
				"type" => "正度",
				"num" => $zhengduHorizontal,
				"loss" => $loss 
		) );
		$a = $lossa;
		$temp = $daduVertical;
		foreach ( $arr as $rs ) {
			if ($temp < $rs ['num']) {
				$a = $rs ['loss'];
				$temp = $rs ['num'];
				$res = $rs;
			} else if ($temp == $rs ['num']) {
				if ($a > $rs ['loss']) {
					$a = $rs ['loss'];
					$temp = $rs ['num'];
					$res = $rs;
				}
			}
		}
		return $res;
	}
	/**
	 * 计算开数
	 *
	 * @param 全张纸 $ma        	
	 * @param 全张纸 $mb        	
	 * @param 成品纸 $a        	
	 * @param 成品纸 $b        	
	 * @return number
	 */
	protected function getKaishu($ma, $a, $mb, $b) {
		$n = $ma / $a;
		$m = $mb / $b;
		$res = floor ( $n ) * floor ( $m );
		return $res;
	}
	/**
	 * 组合用于存储数据库的数据
	 */
	protected function getData4Sql(){
		$post = $this->post;
		$pid = $this->product_id;
		//$prodM = new ProductModel ();
		$prodRs = $this->productInfo;
		//$pa = new ProductAttributeModel ();
		$attrArr = $this->attrArr;// $pa->where ( "pa_product = {$pid} " )->select (); // 查找绑定的所有基本属性
		$attrM = new AttributeModel ();
		$paperSize = "";
		$number = 0;
		$attrStr = "";
		$attrVal = "";
		$diyS = "";
		foreach ( $attrArr as $rs ) {
			//$res = true;
			$attrid = $rs['pa_attribute'];
			$name = $attrM->where ( "id = {$attrid}" )->find ();
			/*if (empty ( $post ['' . $attrid] )) {
				//尺寸是否为自定义，判断自定义的长宽是否为空，为空跳出并提示
				if($name ['a_name'] == "尺寸"){
					if ($this->post ['isdiy'] == 1 && !empty($this->post ['xx']) && !empty($this->post ['yy'])) {
						$res = false;
					}
					//不干胶
					if ($prodRs ['p_type'] == 2 && ! empty ( $post ["length"] ) && ! empty ( $post ["width"] )) {
						$res = false;
					}
				}
				//无碳，自定义的是否为空
				if($name ['a_name'] == "每本页数" && $this->perPagesIsDiy === true && !empty($this->perPages)){
					$res = false;
				}
				if($res){
					$this->ajaxReturn ( "fail", "<b>" . $name ['a_name'] . "</b> 不能为空", 0 );
				}
			}*/
			switch ($name ['a_name']) {
				case '尺寸' :
					/*$attrStr .= $attrStr == "" ? $attrid : "," . $attrid;
					if($this->post ['isdiy'] == 1){
						//$diyS .= $diyS == "" ? $attrid : ":" . $attrid;
						$attrStr .= "_is";
						$attrVal .= $attrVal == "" ? $this->paperSize : ";" . $this->paperSize;
					}else{
						//$attrStr .= $attrStr == "" ? $attrid : "," . $attrid;
						$attrrs = $attrM->where("id = {$post ['' . $attrid]}")->find();
						$attrVal .= $attrVal == "" ? $attrrs ['a_name'] : ";" . $attrrs ['a_name'];
					}*/
					break;
				case '数量' :
					$number = $post ['' . $attrid];
					break;
				case '材料' :
					$attrStr .= $attrStr == "" ? $attrid : "," . $attrid;
					$pam = new PaperModel();
					$paper = $pam->where("id = {$post ['' . $attrid]}")->find();
					$atv = $paper['m_name'];
					if($prodRs['p_type'] == 1 && !empty($post ['kz_' . $attrid])){
						$kz = $pam->where("id = {$post ['kz_' . $attrid]}")->find();
						$atv .= " ".$kz['m_name']."克";
					}
					$attrVal .= $attrVal == "" ? $atv : ";" . $atv;
					break;
				case '每本页数' :
					$attrStr .= $attrStr == "" ? $attrid : "," . $attrid;
					if($this->perPagesIsDiy){
						//$diyS .= $diyS == "" ? $attrid : ":" . $attrid;
						$attrStr .= "_is";
						$attrVal .= $attrVal == "" ? $this->perPages : ";" . $this->perPages;
					}else{
						//$attrStr .= $attrStr == "" ? $attrid : "," . $attrid;
						$attrrs = $attrM->where("id = {$post ['' . $attrid]}")->find();
						$attrVal .= $attrVal == "" ? $attrrs ['a_name'] : ";" . $attrrs ['a_name'];
					}
					break;
				default :
					$attrStr .= $attrStr == "" ? $attrid : "," . $attrid;
					$attrrs = $attrM->where("id = {$post ['' . $attrid]}")->find();
					$attrVal .= $attrVal == "" ? $attrrs ['a_name'] : ";" . $attrrs ['a_name'];
					break;
			}
		}
		$procStr = "";
		$procVal = "";
		$inpNameO = "";
		$procM = new ProcessModel ();
		//$ppro = new ProductProcessModel ();
		$proArr = $this->prcArr;// $ppro->where ( "pp_product = {$pid} AND pp_process <> 0" )->select (); // 查找绑定的后加工工序
		foreach ( $proArr as $row ) {
			$prcId = $row ['pp_process'];
			$proRs = $procM->where ( "id = {$prcId}" )->find ();
			if(! empty ( $proRs['combo_name'] )){
				$inpName = 'pro_' . $proRs['combo_name'];
				//判断是否重复
				if($inpName == $inpNameO){
					continue;
				}else{
					$inpNameO = $inpName;
				}
			}else{
				$inpName = 'pro_' . $prcId;
			}
			//是否已经被选中
			if (! empty ( $post [''.$inpName] )) {
				$proRs = $procM->where ( "id = {$post [''.$inpName]}" )->find ();
				//选中，查找对应详细信息
				//$proRs = $procM->where ( "id = {$prcId}" )->find ();
				//记录选择的后道
				$procStr .= $procStr == "" ? $proRs ['p_name'] : "," . $proRs ['p_name'];
				$value = $proRs ['p_name'];
				if (! empty ( $post ['pro_attr_' . $prcId] )) {
					$proAttrRs = $procM->where ( "id = {$post ['pro_attr_' . $prcId]}" )->find ();
					$value = $proAttrRs ['p_name'] == "" ? $post ['pro_attr_' . $prcId] : $proAttrRs ['p_name'];
				}
				if ($proRs ['sizeDIY'] == 1) {
					$sizePro = $post [$prcId . "_x"] . "×" . $post [$prcId . "_y"];
					$value = $sizePro;
				}
				if ($proRs ['numDIY'] == 1) {
					$value = $value == $proRs ['p_name'] ? $post ["pro_nm_" . $prcId] : $value . "," . $post ["pro_nm_" . $prcId];
				}
				if ($proRs ['hasDesc'] == 1) {
					$value = $value == $proRs ['p_name'] ? $post ["pro_desc_" . $prcId] : $value . "," . $post ["pro_desc_" . $prcId];
				}
				$procVal .= $procVal == "" ? $value : ";" . $value;
			}
		}
			
		//$pri = new QuotePriceCheckAction ( $post );
		$data = array ();
		$data ['p_id'] = $pid;
		$data ['p_name'] = $prodRs ['p_name'];
		$data ['u_id'] = $_SESSION ['cmp'] ['id'];
		$data ['o_amount'] = $number;
		$data ['o_size'] = $this->paperType." ".$this->paperSize;
		$data ['o_attr'] = $attrStr;
		$data ['o_attr_info'] = $attrVal;
		$data ['o_process'] = $procStr;
		$data ['o_pro_attr'] = $procVal;
		$data ['o_papePrice'] = getCheckNum4Float($this->paperPrice);
		$pri = $this->checkPriceByNumber($this->finalPrice);
		//订单价格，小数进1
		//$data ['o_price'] = getCheckNum4Float($this->checkPriceByGroup ($pri));
		$data ['o_price'] = ceil(getCheckNum4Float($this->checkPriceByGroup ($pri)));
		// 成本价（未经利润设置）
		$data ['o_cost'] = getCheckNum4Float($this->finalPrice);
		$data ['o_pro_price'] = getCheckNum4Float($this->processPrice);
		$data ['o_truePrice'] = 0;
		$data ['o_number'] = date("YmdHis");
		$data ['o_date'] = date("Y-m-d H:i:s");
		$data ['o_desc'] = "";

		$data ['o_printPrice'] = $this->printWorkFee + $this->versionCost;// $pri->getPrintWorkFee () + $pri->getVersionCost ();
		//return $data;
		$this->dataSave = $data;
	}
	
	/**
	 * 会员组利润叠加
	 *
	 * @param unknown $price
	 * @return unknown
	 */
	protected function checkPriceByGroup($price) {
		$groupid = $_SESSION ['cmp'] ['u_group'];
		$profit = $price;
		//管理员或内部人员例外
		if ($groupid != 1 && $groupid != 2) {
			$mm = new GroupProfitModel ();
			$arr = $mm->where ( "group_id = {$groupid}" )->select ();
			foreach ($arr as $row){
				if($price >= $row['fi_min'] && $price <= $row['fi_max']){
					$price = (100 + $row['fi_percent'])/100*$price + $row['fi_value'];
					break;
				}
			}
		}
		$this->groupProfit = $profit - $price;
		return $price;
	}
	
	/**
	 * 数量利润叠加
	 *
	 * @param unknown $price
	 * @return unknown
	 */
	protected function checkPriceByNumber($price) {
		$productId = $this->product_id;
		$mm = M('pt_product_profit');
		$arr = $mm->where ( "product_id = {$productId}" )->select ();
		$numPrice = $price;
		foreach ($arr as $row){
			if($this->customerNumber >= $row['fi_min'] && $this->customerNumber <= $row['fi_max']){
				$price = (100 + $row['fi_percent'])/100*$price + $row['fi_value'];
				break;
			}
		}
		$this->numberProfit = $numPrice - $price;
		return $price;
	}
}