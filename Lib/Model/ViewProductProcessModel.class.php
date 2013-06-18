<?php
class ViewProductProcessModel extends ViewModel {
	public $viewFields = array (
			/*SELECT
				pt_process.id,
				pt_process.parent_id,
				pt_process.combo_name,
				pt_process.sizeDIY,
				pt_process.numDIY,
				pt_process.ismust,
				pt_process.type_id,
				pt_process.p_name,
				pt_process.p_price,
				pt_process.p_status,
				pt_process.p_lastDate,
				pt_process.p_isDelete,
				pt_process.p_desc,
				pt_product_process.pp_process_attr,
				pt_product_process.pp_product AS product_id,
				pt_product_process.pp_process
				FROM
				pt_product_process
				INNER JOIN pt_process ON pt_product_process.pp_process = pt_process.id*/
			"ProductProcess" => array (
					'pp_process',
					"pp_product" => "product_id",
					'pp_process_attr',
					'_as' => 'P1',
					'_type' => 'LEFT' 
			),
			'Process' => array (
					'id' ,
					'parent_id',
					'combo_name',
					'sizeDIY',
					'numDIY',
					'ismust',
					'hasDesc',
					'type_id',
					'p_name',
					'p_price',
					'p_minFee',
					'p_otherFee',
					'p_status',
					'p_lastDate',
					'p_isDelete',
					'p_desc',
					'_as' => 'P2',
					'_on' => 'P2.id = P1.pp_process AND P2.p_isDelete = 0' 
			) 
	);
}