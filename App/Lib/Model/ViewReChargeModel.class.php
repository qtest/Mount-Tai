<?php 
class ViewReChargeModel extends ViewModel{
	public $viewFields = array(
		"Recharge"=>array(
			"id",
			"u_id",
			"re_subMan",
			"re_bankInfo",
			"re_myBank",
			"re_money",
			"re_overage",
			"re_date",
			"DATE_FORMAT(re_date,'%Y年%m月%d日')" => "re_date_format",
			"re_status",
			"re_subDate",
			"DATE_FORMAT(re_subDate,'%Y年%m月%d日 %H时%i分')" => 're_subDate_format',
			"re_desc",
			"_as"=>"R",
			"_type"=>"LEFT"
		),
		"Manager"=>array(
			"id" => "mid1",
			"u_name",
			"u_company",
			"u_province",
			"u_city",
			"_as"=>"M1",
			"_type"=>"LEFT",
			"_on"=>"R.u_id = M1.id"
		),
		"Manager2"=>array(
			"_table" => "pt_user",
			"id" => "mid2",
			"u_name" => 'sub_user',
			"u_manager" => 'sub_name',
			"_as"=>"M2",
			"_on"=>"R.re_subMan = M2.id"
		)
	);
}