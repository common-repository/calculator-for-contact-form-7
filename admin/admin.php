<?php
add_action( 'wpcf7_admin_init', 'cfc_custom_add_form_tag_myCustomField' );
function cfc_custom_add_form_tag_myCustomField() {
    $tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'calculator_field', __( 'calculator_field', 'contact-form-7' ),'cfc_custom_myCustomField_form_tag_admin' );
}
add_action( 'wpcf7_init', 'cfc_custom_add_form_tag_myCustomFieldfront' );
function cfc_custom_add_form_tag_myCustomFieldfront() {
	wpcf7_add_form_tag( array( 'calculator_field', 'calculator_field*' ),'cfc_custom_myCustomField_form_tag_handler', array( 'name-attr' => true) );
}

function cfc_custom_myCustomField_form_tag_admin( $contact_form, $args = '' ) {
	$wpcf7_contact_form = WPCF7_ContactForm::get_current();
	$contact_form_tags = $wpcf7_contact_form->scan_form_tags();
	$calculator_args = wp_parse_args( $args, array() );
	$calculator_type = 'calculator_field';
	$calculationcf7_tag = array();
	foreach ($contact_form_tags as $contact_form_tag) {
		if ( $contact_form_tag['type'] == 'number' || $contact_form_tag['type'] == 'number*' || $contact_form_tag['type'] == 'radio' || $contact_form_tag['type'] == 'select' || $contact_form_tag['type'] == 'select*' || $contact_form_tag['type'] == 'text*' || $contact_form_tag['type'] == 'text' || $contact_form_tag['type'] == 'checkbox' || $contact_form_tag['type'] == 'checkbox*'){
			$calculationcf7_tag[] = $contact_form_tag['name'];
		}
	} 
	?>
	<div class="control-box">
		<fieldset>
			<table class="form-table">
				<tbody>

					<tr>
						<th>
							<label for="<?php echo esc_attr( $calculator_args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $calculator_args['content'] . '-name' ); ?>" />
						</td>
					</tr>

					<tr>
						<th>
							<label for="<?php echo esc_attr( $calculator_args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $calculator_args['content'] . '-id' ); ?>" />
						</td>
					</tr>

					<tr>
						<th>
							<label for="<?php echo esc_attr( $calculator_args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $calculator_args['content'] . '-class' ); ?>" />
						</td>
					</tr>
					
					<tr>
						<th>
							<label for="<?php echo esc_attr( $calculator_args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Equation', 'contact-form-7' ) ); ?>
							</label>
						</th>
						<td>
						
							<p><?php echo esc_attr(implode(' , ', $calculationcf7_tag)); ?></p>
							<textarea rows="3" class="large-text" name="values" id="<?php echo esc_attr( $calculator_args['content'] . '-values' ); ?>"></textarea> 
							Ex: radio-1 + checkbox-2 + ( number-8 + number-9 ) / 2 <br>
						</td>
					</tr>

					

				</tbody>
			</table>
		</fieldset>
	</div>

	<div class="insert-box">
		<input type="text" name="<?php echo esc_attr($calculator_type); ?>" class="tag" readonly="readonly" onfocus="this.select()" />
		<div class="submitbox">
			<input type="button" class="button button-primary insert-tag" value="Insert Tag'" />
		</div>
	</div>
	<?php
}
function cfc_custom_myCustomField_form_tag_handler( $tag ) {
	if ( empty( $tag->name ) ) {
		return '';
	}

	$calculator_atts = array();
	
	$calculator_validation_error = wpcf7_get_validation_error( $tag->name );
	$calculator_class = wpcf7_form_controls_class( $tag->type );
	$calculator_class .= ' wpcf7-validates-as-calculator_field';
	$calculator_atts['id'] = $tag->get_id_option();
	$calculator_atts['class'] = $tag->get_class_option( $calculator_class );
	$calculator_atts['readonly'] = 'readonly';
	$calculator_value = (string) reset( $tag->values );
	$calculator_value = $tag->get_default_option( $calculator_value );
	$calculator_value = wpcf7_get_hangover( $tag->name, $calculator_value );
	
	
	$calculator_atts['type'] = 'text';

	$calculator_atts['name'] = $tag->name;

	$calculator_atts['class'] .= " calculator_field-total";
	$calculator_atts['value'] = 0;

	$calculator_atts = wpcf7_format_atts( $calculator_atts );

	
	$calculator_html = sprintf(
	'<span class="wpcf7-form-control-wrap %1$s"><input %2$s %4$s />%3$s</span>',
	sanitize_html_class( $tag->name ), $calculator_atts, $calculator_validation_error, 'data-equation="'.$calculator_value.'"' );
	return $calculator_html;
}

