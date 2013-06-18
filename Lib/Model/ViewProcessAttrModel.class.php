<?php
class ViewProcessAttrModel extends ViewModel{
	public $viewFields = array (
			/*SELECT
			PC1.id,
			PC1.parent_id,
			PC1.combo_name,
			PC1.sizeDIY,
			PC1.numDIY,
			PC1.ismust,
			PC1.type_id,
			PC1.p_name,
			PC1.p_price,
			PC1.p_status,
			PC1.p_lastDate,
			PC1.p_isDelete,
			PC1.p_desc,
			PC2.p_name AS parent_name
			FROM
			pt_process AS PC1
			LEFT JOIN pt_process AS PC2 ON PC1.parent_id = PC2.id*/
			'Process' => array (
					'id' ,
					'parent_id',
					'combo_name',
					'sizeDIY',
					'numDIY',
					'ismust',
					'type_id',
					'p_name',
					'p_price',
					'p_minFee',
					'p_otherFee',
					'p_status',
					'p_lastDate',
					'p_isDelete',
					'p_desc',
					'_as' => 'P1',
					'_type' => 'LEFT'
			) ,
			'Process2' => array (
					"_table" => "pt_process",
					'p_name' => 'parent_name',
					'_as' => 'P2',
					'_on' => 'P1.parent_id=P2.id AND P2.p_isDelete = 0'
			)
	);
}