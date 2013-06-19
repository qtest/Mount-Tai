<?php
class ViewPaperAttrModel extends ViewModel{
	public $viewFields = array (
			'Paper' => array (
					'id' ,
					'parent_id',
					'type_id',
					'm_name',
					'm_price',
					'm_dPrice',
					'm_unit',
					'm_status',
					'm_lastDate',
					'm_isDelete',
					'm_desc',
					'_as' => 'P1',
					'_type' => 'LEFT'
			) ,
			'Paper2' => array (
					"_table" => "pt_paper",
					'm_name' => 'parent_name',
					'_as' => 'P2',
					'_on' => 'P1.parent_id=P2.id AND P2.m_isDelete = 0'
			)
	);
}