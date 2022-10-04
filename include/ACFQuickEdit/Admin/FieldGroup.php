<?php

namespace ACFQuickEdit\Admin;

use ACFQuickEdit\Core;
use ACFQuickEdit\Fields;

if ( ! defined( 'ABSPATH' ) )
	die('Nope.');

class FieldGroup extends Core\Singleton {

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {

		$types = Fields\Field::get_types();
		/*
		ACF > 6 should call
		*/
		foreach ( $types as $type => $supports ) {
			if ( $supports[ 'column' ] || $supports[ 'quickedit' ] || $supports[ 'bulkedit' ] ) {
				add_action( "acf/render_field_presentation_settings/type={$type}", [ $this, 'render_headline' ] );
			}
			if ( $supports[ 'column' ] ) {
				add_action( "acf/render_field_presentation_settings/type={$type}", [ $this, 'render_column_settings' ] );
			}
			if ( $supports[ 'quickedit' ] ) {
				add_action( "acf/render_field_presentation_settings/type={$type}", [ $this, 'render_quickedit_settings' ] );
			}
			if ( $supports[ 'bulkedit' ] ) {
				add_action( "acf/render_field_presentation_settings/type={$type}", [ $this, 'render_bulkedit_settings' ] );
			}
		}

		parent::__construct();

	}

	/**
	 *	@action acf/render_field_settings/type={$type}
	 */
	public function render_headline( $field ) {

		acf_render_field_setting( $field, [
			'label'			=> __('List Table Settings','acf-quickedit-fields'),
			'instructions'	=> '',
			'type'			=> 'message',
			'name'			=> 'list-table-settings-heaadline',
		]);
	}


	/**
	 *	@action acf/render_field_settings/type={$type}
	 */
	public function render_column_settings( $field ) {

		// render_field_settings()
		$field_object = Fields\Field::getFieldObject( $field );

		acf_render_field_setting( $field, [
			'label'			=> __('Show Column','acf-quickedit-fields'),
			'instructions'	=> __("Show a column in the posts list table", 'acf-quickedit-fields'),
			'type'			=> 'true_false',
			'name'			=> 'show_column',
			'ui'			=> 1,
			'message'		=> '',//__("Show column in list tables", 'acf-quickedit-fields'),
			'prefix'		=> $field['prefix'],
			'wrapper'		=> [ 'width' => 34, ],
			// 'value'			=> $field['show_column'],
		]);
		if ( $field_object->is_sortable() ) {
			acf_render_field_setting( $field, [
				'label'			=> __('Sortable Column','acf-quickedit-fields'),
				'instructions'	=> __("Make this column sortable", 'acf-quickedit-fields'),
				'type'			=> 'true_false',
				'name'			=> 'show_column_sortable',
				'ui'			=> 1,
				'message'		=> '',//__("Make this column sortable", 'acf-quickedit-fields'),
				'prefix'		=> $field['prefix'],
				'wrapper'		=> [ 'width' => 33, ],
				// 'value'			=> $field['show_column_sortable'],
				'conditional_logic' => [[[ 'field'=> 'show_column', 'operator' => '==', 'value' => 1, ]]],
			]);
		}
		acf_render_field_setting( $field, [
			'label'			=> __('Column Weight','acf-quickedit-fields'),
			'instructions'	=> __('Columns with a higher weight will be pushed to the right. The leftmost WordPress column has a weight of <em>0</em>, the next one <em>100</em> and so on. Leave empty to place a column to the rightmost position.','acf-quickedit-fields'),
			'type'			=> 'number',
			'name'			=> 'show_column_weight',
			'message'		=> '',//__("Column Weight", 'acf-quickedit-fields'),
			'default_value'	=> '1000',
			'min'			=> '-10000',
			'max'			=> '10000',
			'step'			=> '1',
			'placeholder'	=> '',
			'wrapper'		=> [ 'width' => 33, ],
			'conditional_logic' => [[[ 'field'=> 'show_column', 'operator' => '==', 'value' => 1, ]]],
		]);
	}

	/**
	 *	@action acf/render_field_settings/type={$type}
	 */
	public function render_quickedit_settings( $field ) {

		acf_render_field_setting( $field, [
			'label'			=> __( 'Enable QuickEdit', 'acf-quickedit-fields' ),
			// 'instructions'	=> '',
			'type'			=> 'true_false',
			'name'			=> 'allow_quickedit',
			'ui'			=> 1,
			'wrapper'		=> [ 'width' => 34, ],
		]);
	}

	/**
	 *	@action acf/render_field_settings/type={$type}
	 */
	public function render_bulkedit_settings( $field ) {

		acf_render_field_setting( $field, [
			'label'			=> __( 'Enable Bulk Edit', 'acf-quickedit-fields' ),
			'type'			=> 'true_false',
			'name'			=> 'allow_bulkedit',
			'ui'			=> 1,
			'wrapper'		=> [ 'width' => 33, ],
		]);
	}

}
