<?php 
class ViewRecordModel extends ViewModel{
	public $viewFields = array(
		"Record"=>array(
			"id",
			"o_id",
			"re_id",
			"r_income",
			"r_pay",
			"r_type",
			"r_date",
			"r_overage",
			"u_id",
			"r_desc",
			"_as"=>"R",
			"_type"=>"LEFT",
		),
		"Manager"=>array(
			"u_name",
			"u_company",
			"u_province",
			"u_city",
			"_as"=>"M",
			"_on"=>"R.u_id = M.id",
		),
	);
}