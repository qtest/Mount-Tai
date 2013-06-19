<?php
/**
 * Author: wutong
 * Date: 11-8-11 下午12:42
 */

class PageNormal {
	public $P;
	private $recordCount;
	private $pageCount;
	private $pageCurrent=1;
	private $pageSize=20;
	private $pageUrl;
	private $pageName="page";
	private $pageParam;
	private $pageParamStr;
	private $pageSeparator="&";
	private $pageConnector="=";
	private $pageInitAskor="?";
	
	//-----------------------------------------
	//  总记录，当前页，每页记录，传递的参数
	//-----------------------------------------
	/**
	 * 总记录，当前页，每页记录，传递的参数
	 * 
	 * @param 总记录 $recordCount
	 * @param 当前页 $pageCurrent
	 * @param 每页记录 $pageSize
	 * @param 传递的参数 $pageParam
	 */
	public function __construct($recordCount=20,$pageCurrent=1,$pageSize=20,$pageParam=NULL){
		$this->recordCount=$recordCount;
		$this->pageCurrent=(int)$pageCurrent>=1 ? $pageCurrent : 1;
		$this->pageSize=$pageSize;
		$this->pageCount=ceil($this->recordCount/$this->pageSize);
		$this->pageUrl=$_SERVER['SCRIPT_NAME'];
		$this->pageParam=$pageParam;
		$this->setPageParams();
		$this->setSplitParams();
	}
    //设置跳转页面的参数
    public function setPageParams(){
        if(!is_array($this->pageParam))return;
        $link_str="";
        foreach($this->pageParam as $key=>$value){
            $link_str.=$key.$this->pageConnector.$value.$this->pageSeparator;
        }
        $this->pageParamStr=$link_str;
    }
    public function setSplitParams(){
        $start=($this->pageCurrent-1)*$this->pageSize;
        $this->P=array(
			"start"=>$start,
			"end"=>$this->pageSize,
			"pageCount"=>$this->pageCount,
			"recordCount"=>$this->recordCount,
		);
    }
    //一般分页
    public function pageNormal(){
        if($this->pageCurrent==1){
            $this->printHome(false);
            $this->printPrevious(false);
        }else{
            $this->printHome(true);
            $this->printPrevious(true);
        }
        //echo $this->record_count."----".$this->page_record;
        if($this->pageCurrent>=$this->pageCount){
            $this->printNext(false);
            $this->printLast(false);
        }else{
            $this->printNext(true);
            $this->printLast(true);
        }
    }
    //输出带下拉菜单
    public function printDropMenu(){
        echo '<select class="page-select" onchange="location=\''. $this->pageUrl .$this->pageInitAskor. $this->pageParamStr.$this->pageName.$this->pageConnector.'\'+this.value" >';
        for($i=1;$i<=$this->pageCount;$i++){
            if($this->pageCurrent!=$i){
                echo '<option value="'. $i .'" >'. $i .'</option>';
            }else{
                echo '<option value="'. $i .'" selected="true" >'. $i .'</option>';
            }

        }
        echo '</select>';
    }
    //输出首页
    public function printCount(){
    	/*if($link){
    		echo '<a class="page-btn" href="'. $this->pageUrl.$this->pageInitAskor.$this->pageParamStr .$this->pageName.$this->pageConnector.'1" >首页</a> ';
    	}else{
    		echo '<span  class="page-btn-disabled">首页</span> ';
    	}*/
    	echo "<span class='page-btn-disabled'>[总记录：{$this->recordCount} 条 ]</span> ";
    }
    //输出首页
    public function printHome($link){
        if($link){
            echo '<a class="page-btn" href="'. $this->pageUrl.$this->pageInitAskor.$this->pageParamStr .$this->pageName.$this->pageConnector.'1" >首页</a> ';
        }else{
            echo '<span  class="page-btn-disabled">首页</span> ';
        }
    }
    //输出上一页
    public function printPrevious($link){
        if($link){
            echo '<a class="page-btn" href="'. $this->pageUrl.$this->pageInitAskor. $this->pageParamStr.$this->pageName.$this->pageConnector. ($this->pageCurrent-1) .'">上一页</a> ';
        }else{
            echo '<span class="page-btn-disabled">上一页</span> ';
        }
    }
    //输出下一页
    public function printNext($link){
        if($link){
            echo '<a class="page-btn" href="'. $this->pageUrl.$this->pageInitAskor. $this->pageParamStr.$this->pageName.$this->pageConnector. ($this->pageCurrent+1) .'">下一页</a> ';
        }else{
            echo '<span class="page-btn-disabled">下一页</span> ';
        }
    }
    //输出末页
    public function printLast($link){
        if($link){
            echo '<a class="page-btn" href="'. $this->pageUrl.$this->pageInitAskor . $this->pageParamStr.$this->pageName.$this->pageConnector. $this->pageCount .'">末页</a> ';
        }else{
            echo '<span class="page-btn-disabled">末页</span> ';
        }
    }
    //获取问号后面的参数
    public function getParams(){
        if(!is_array($this->page_params))return;
        $link_str="";
        foreach($this->page_params as $key=>$value){
            $link_str.=$key."=".$value."&";
        }
        return $link_str;
    }
    /**
     * @static
     * @param  $rs_count int 总记录数
     * @param  $rs_page int 每页记录数
     * @param  $curr_page int 当前页
     * @return array
     */
    public function getSplitParams($rs_count,$rs_page,$curr_page){
		$curr_page= $curr_page>=1 ? $curr_page : 1;
        $page_count=ceil($rs_count/$rs_page);
        $limit_start=($curr_page-1)*$rs_page;
        return array(
            "limit_start"=>$limit_start,
            "limit_end"=>$rs_page,
			"curr_page"=>$curr_page,
			"rs_count"=>$rs_count,
			"rs_page"=>$rs_page,
			"page_count"=>$page_count
        );
    }
}