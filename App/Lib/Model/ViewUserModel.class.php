<?php
class ViewUserModel extends ViewModel {
	/*public $viewFields = array (
			'Manager' => array (
					'id',
					'u_name',
					'u_pwd',
					'u_email',
					'u_company',
					'u_industry',
					'u_province',
					'u_city',
					'u_tel',
					'u_address',
					'u_manager',
					'u_phone',
					'u_qq',
					'u_isVIP',
					'u_group',
					'u_status',
					'u_regDate',
					'u_logTime',
					'u_allow',
					'_as' => 'M',
					'_type' => 'LEFT' 
			),
			'UserGroup' => array (
					"g_name",
					"g_default",
					"g_shuoming",
					"g_status",
					"g_manager",
					"g_desc",
					'_as' => 'G',
					'_on' => 'M.u_group=G.id',
					'_type' => 'LEFT' 
			),
			'Offer' => array (
					'count(O.id)' => 'total',
					'_as' => "O",
					'_on' => "O.u_id = M.id" 
			) 
	);*/
	protected $trueTableName = "view_manager";
}