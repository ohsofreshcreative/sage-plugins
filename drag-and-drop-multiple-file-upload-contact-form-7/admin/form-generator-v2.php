<?php
	add_action('admin_footer', function(){
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wpcf7' ) {
			echo '<style type="text/css">
				.control-box.dnd-file-upload legend { float: left; width: 160px; }
				.control-box.dnd-file-upload fieldset { margin-block: 4px!important; }
			</style>';
		}
	});
?>

<header class="description-box">
	<h3>
		<?php echo esc_html( $field_types['mfile']['heading'] ); ?>
	</h3>
	<p>
		<?php
			echo wp_kses(
				$field_types['mfile']['description'],
				array(
					'a' => array( 'href' => true ),
					'strong' => array(),
				),
				array( 'http', 'https' )
			);
		?>
	</p>
</header>

<div class="control-box dnd-file-upload">

	<?php
		$tgg->print( 'field_type', array(
			'with_required' => true,
			'select_options' => array(
				'mfile' => $field_types['mfile']['display_name'],
			),
		) );

		// Fieldname
		$tgg->print( 'field_name' );

		$server_limit = wp_max_upload_size();
		$threshold    = 50 * 1024 * 1024; // 100 MB in bytes
	?>

	<fieldset style="margin-block: 4px!important;">
		<legend id="<?php echo esc_attr( $tgg->ref( 'limit-option-legend' ) ); ?>" style="float: left; width: 160px;">
			<?php
				esc_html_e( 'File size limit (bytes)', 'drag-and-drop-multiple-file-upload-contact-form-7' );
			?>
		</legend>
		<label>
			<?php
				echo sprintf(
					'<input %s />',
					wpcf7_format_atts( array(
						'type' => 'text',
						'data-tag-part' => 'option',
						'data-tag-option' => 'limit:',
						'placeholder' => 10485760
					) )
				);
			?>

			<!-- Only show if max upload size is less than 100 MB -->
			<?php if ( $server_limit < $threshold ) : ?>
				<br>
				<span>Your server max upload size: <strong><?php echo size_format( $server_limit ); ?></strong>,
				🔥 <a href="https://www.codedropz.com/drag-drop-multiple-file-upload-for-contact-form-7/" target="_blank">Pro</a> enables large uploads using chunked uploads.</span>
			<?php endif; ?>
		</label>
	</fieldset>

	<fieldset style="margin-block: 4px!important;">
		<legend id="<?php echo esc_attr( $tgg->ref( 'filetypes-option-legend' ) ); ?>" style="float: left; width: 160px;">
			<?php
				esc_html_e( 'Acceptable file types', 'drag-and-drop-multiple-file-upload-contact-form-7' );
			?>
		</legend>
		<label>
			<?php
				echo sprintf(
					'<input %s />',
					wpcf7_format_atts( array(
						'type' => 'text',
						'data-tag-part' => 'option',
						'data-tag-option' => 'filetypes:',
						'placeholder' => 'jpeg|png|jpg|gif',
					) )
				);
			?>
		</label>
	</fieldset>

	<fieldset style="margin-block: 4px!important;">
		<legend id="<?php echo esc_attr( $tgg->ref( 'blacklist-types-option-legend' ) ); ?>" style="float: left; width: 160px;">
			<?php
				esc_html_e( 'Blacklist file types', 'drag-and-drop-multiple-file-upload-contact-form-7' );
			?>
		</legend>
		<label>
			<?php
				echo sprintf(
					'<input %s />',
					wpcf7_format_atts( array(
						'type' => 'text',
						'data-tag-part' => 'option',
						'data-tag-option' => 'blacklist-types:',
						'placeholder' => 'exe|bat|com',
					) )
				);
			?>
		</label>
	</fieldset>

	<fieldset style="margin-block: 4px!important;">
		<legend id="<?php echo esc_attr( $tgg->ref( 'min-file-option-legend' ) ); ?>" style="float: left; width: 160px;">
			<?php
				esc_html_e( 'Minimum File Upload', 'drag-and-drop-multiple-file-upload-contact-form-7' );
			?>
		</legend>
		<label>
			<?php
				echo sprintf(
					'<input %s />',
					wpcf7_format_atts( array(
						'type' => 'text',
						'data-tag-part' => 'option',
						'data-tag-option' => 'min-file:',
						'placeholder' => 5
					) )
				);
			?>
		</label>
	</fieldset>

	<fieldset style="margin-block: 4px!important;">
		<legend id="<?php echo esc_attr( $tgg->ref( 'max-file-option-legend' ) ); ?>" style="float: left; width: 160px;">
			<?php
				esc_html_e( 'Maximum File Upload', 'drag-and-drop-multiple-file-upload-contact-form-7' );
			?>
		</legend>
		<label>
			<?php
				echo sprintf(
					'<input %s />',
					wpcf7_format_atts( array(
						'type' => 'text',
						'data-tag-part' => 'option',
						'data-tag-option' => 'max-file:',
						'placeholder' => 10
					) )
				);
			?>
		</label>
	</fieldset>

	<?php
		// Class Name
		$tgg->print( 'class_attr' );

		// ID name
		$tgg->print( 'id_attr' );
	?>

</div>

<footer class="insert-box">
	<?php
		$tgg->print( 'insert_box_content' );
	?>
</footer>