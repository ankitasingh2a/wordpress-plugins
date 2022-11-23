<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_60247bf44b088',
	'title' => 'Add Devices',
	'fields' => array(
		array(
			'key' => 'field_60247c006442a',
			'label' => 'Add Sub Devices',
			'name' => 'add_sub_device',
			'type' => 'select',
			'instructions' => 'Add a sub-device by selecting the device.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 1,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
		),

	),
	'location' => array(
		array(
			array(
				'param' => 'page_type',
				'operator' => '==',
				'value' => 'parent',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'left',
	'instruction_placement' => 'field',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;