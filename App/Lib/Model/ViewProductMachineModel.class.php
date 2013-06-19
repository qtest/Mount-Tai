<?php
class ViewProductMachineModel extends ViewModel{
	public $viewFields = array (
			/*SELECT
			pt_machine.id,
			pt_machine.m_name,
			pt_machine.m_type,
			pt_machine.m_color,
			pt_machine.m_maxLength,
			pt_machine.m_maxWidth,
			pt_machine.m_maxThick,
			pt_machine.m_minThick,
			pt_machine.m_price,
			pt_machine.m_single,
			pt_machine.m_minWork,
			pt_machine.m_minWorkPrice,
			pt_machine.m_overWorkPrice,
			pt_machine.m_status,
			pt_machine.m_lastDate,
			pt_machine.m_isDelete,
			pt_machine.m_desc,
			pt_product.id AS product_id
			FROM
			pt_product
			INNER JOIN pt_product_machine ON pt_product.id = pt_product_machine.pm_product
			INNER JOIN pt_machine ON pt_product_machine.pm_machine = pt_machine.id
*/
			'Product' => array (
					'id' => 'product_id',
					'_as' => 'P',
					'_type' => 'INNER'
			),
			'ProductMachine' =>array(
					'_as' => 'PM',
					'_on' => 'P.id=PM.pm_product',
					'_type' => 'INNER'
			),
			'Machine' => array (
					'id' ,
					'm_name',
					'm_type',
					'm_color',
					'm_maxLength',
					'm_maxWidth',
					'm_maxThick',
					'm_minThick',
					'm_price',
					'm_versionCost',
					'm_single',
					'm_minWork',
					'm_minWorkPrice',
					'm_overWorkPrice',
					'm_status',
					'm_lastDate',
					'm_isDelete',
					'm_desc',
					'_as' => 'M',
					'_on' => 'PM.pm_machine=M.id AND M.m_isDelete = 0'
			)
	);
}