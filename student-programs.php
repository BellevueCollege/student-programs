<?php
/*
Plugin Name: Student Programs
Plugin URI: https://github.com/BellevueCollege/student-programs/
Description: This plugin provides the user to create custom post for student programs for Bellevue college
Author: Bellevue College Information Technology Services
Version: 1.0
Author URI: http://www.bellevuecollege.edu
*/

add_action( 'init', 'create_programs_post_type' );

function create_programs_post_type() {
	register_post_type( 'programs',
		array(
			'labels' => array(
				'name' => __( 'Student Programs' ),
				'singular_name' => __( 'Student Program' ) ,
				'add_new' => 'Add New Student Program',
				'add_new_item' => 'Add New Student Program',
				'edit_item' => 'Edit Student Program',
				'menu_name' => 'Student Program Archive',
			),
			'public' => true,
			'supports' => array( 'title', 'editor', 'comments'),
			'has_archive' => 'programs',
			'capability_type' => 'page',
			'rewrite' => array( 'slug' => "programs" ),
		)
	);
}

function programs_rewrite_flush() {
	/* First, we "add" the custom post type via the above written function.
	 * Note: "add" is written with quotes, as CPTs don't get added to the DB,
	 * They are only referenced in the post_type column with a post entry,
	 * when you add a post of this CPT.
	 */

	create_programs_post_type();

	/* ATTENTION: This is *only* done during plugin activation hook in this example!
	 *You should *NEVER EVER* do this on every page load!!
	 */
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'programs_rewrite_flush' );

// Add the Meta Box
add_action('add_meta_boxes', 'add_program_custom_meta_box');

function add_program_custom_meta_box() {
	add_meta_box(
		'custom_meta_box', // $id
		'Program Information', // $title
		'show_program_custom_meta_box', // $callback
		'programs', // $page
		'normal', // $context
		'high'); // $priority
}

//$prefix = 'custom_';
$custom_program_meta_fields = array(
	array(
		'label'=> 'Program Contact Name',
		'desc' => '',
		'name' => 'program_contact_name',
		'id' => 'program_contact_name',
		'type'  => 'text'
	),
	array(
		'label'=> 'Program Contact Email',
		'desc' => '',
		'name' => 'program_contact_email',
		'id' => 'program_contact_email',
		'type' => 'text'
	),
	array(
		'label' => 'Program Contact Phone Number',
		'desc' => '',
		'name' => 'program_contact_phone',
		'id' => 'program_contact_phone',
		'type' => 'text'
	),
	array(
		'label'=> 'Office Location',
		'desc' => '',
		'name' => 'Office_location',
		'id' => 'Office_location',
		'type' => 'text'
	),
	array(
		'label'=> 'Office Hours',
		'desc'  => '',
		'name' => 'office_hours',
		'id' => 'office_hours',
		'type' => 'text'
	),
	array(
		'label'=> 'URL',
		'desc' => 'Program Website',
		'name' => 'program_url',
		'id' => 'program_url',
		'type' => 'text'
	),
	array(
		'label'=> 'Budget Document Link',
		'desc'  => '',
		'name'  => 'budget_document_link',
		'id'    => 'budget_document_link',
		'type'  => 'text'
	),
);

// The Callback
function show_program_custom_meta_box() {
	global $custom_program_meta_fields, $post;

	// Use nonce for verification
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($custom_program_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);
		// begin a table row with
		echo '<tr>
			<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
			<td>';
		switch($field['type']) {
			// text
			case 'text':
				echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
					<br /><span class="description">'.$field['desc'].'</span>';
			break;
		} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

// Save the Data
function save_programs($post_id) {
	global $custom_program_meta_fields;

	// verify nonce
	if( isset( $_POST['custom_meta_box_nonce'] ) ) {
		if ( !wp_verify_nonce( $_POST['custom_meta_box_nonce'], basename(__FILE__) ) )
			return $post_id;
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;
	// check permissions
	if( isset( $_POST['post_type'] ) ) {
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
				return $post_id;
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	// loop through fields and save the data
	foreach ( $custom_program_meta_fields as $field ) {
		if( isset( $field['id'] ) ) {
			$old = get_post_meta($post_id, $field['id'], true);
			if( isset($_POST[$field['id']] ) ) {
				$new = $_POST[$field['id']];
				if ($new && $new != $old) {
					update_post_meta($post_id, $field['id'], $new);
				} elseif ( '' == $new && $old ) {
					delete_post_meta($post_id, $field['id'], $old);
				}
			}
		}
	} // end foreach
}

add_action('save_post_programs', 'save_programs',10);
