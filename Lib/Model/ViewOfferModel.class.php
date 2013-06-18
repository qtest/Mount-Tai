<?php
class ViewOfferModel extends ViewModel{
	protected $viewFields = array(
			'Offer' => array (
					'id',
					'p_id',
					'p_name',
					'u_id',
					'o_amount',
					'o_size',
					'o_attr',
					'o_attr_info',
					'o_process',
					'o_pro_attr',
					'o_papePrice',
					'o_price',
					'o_cost',
					'o_pro_price',
					'o_printPrice',
					'o_truePrice',
					'o_number',
					'o_date',
					'o_desc',
					'_as' => 'O',
					'_type' => 'INNER'
			),
			'Manager' => array (
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
					'_on' => "O.u_id = M.id",
					'_type' => 'INNER' 
			),
			'UserGroup' => array (
					"g_name",
					"g_default",
					"g_shuoming",
					"g_status",
					"g_manager",
					"g_desc",
					'_as' => 'G',
					'_on' => 'M.u_group=G.id'
			),
	);
}