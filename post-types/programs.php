<?php
/**
 * Programs CPT.
 *
 * @package BC_Student_Programs
 */

/**
 * Registers the `programs` post type.
 */
function programs_init() {
	register_post_type(
		'programs',
		array(
			'labels'                => array(
				'name'                  => __( 'Student Programs', 'student-programs' ),
				'singular_name'         => __( 'Student Programs', 'student-programs' ),
				'all_items'             => __( 'All Student Programs', 'student-programs' ),
				'archives'              => __( 'Student Programs Archives', 'student-programs' ),
				'attributes'            => __( 'Student Programs Attributes', 'student-programs' ),
				'insert_into_item'      => __( 'Insert into Student Programs', 'student-programs' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Student Programs', 'student-programs' ),
				'featured_image'        => _x( 'Featured Image', 'programs', 'student-programs' ),
				'set_featured_image'    => _x( 'Set featured image', 'programs', 'student-programs' ),
				'remove_featured_image' => _x( 'Remove featured image', 'programs', 'student-programs' ),
				'use_featured_image'    => _x( 'Use as featured image', 'programs', 'student-programs' ),
				'filter_items_list'     => __( 'Filter Student Programs list', 'student-programs' ),
				'items_list_navigation' => __( 'Student Programs list navigation', 'student-programs' ),
				'items_list'            => __( 'Student Programs list', 'student-programs' ),
				'new_item'              => __( 'New Student Programs', 'student-programs' ),
				'add_new'               => __( 'Add New', 'student-programs' ),
				'add_new_item'          => __( 'Add New Student Programs', 'student-programs' ),
				'edit_item'             => __( 'Edit Student Programs', 'student-programs' ),
				'view_item'             => __( 'View Student Programs', 'student-programs' ),
				'view_items'            => __( 'View Student Programs', 'student-programs' ),
				'search_items'          => __( 'Search Student Programs', 'student-programs' ),
				'not_found'             => __( 'No Student Programs found', 'student-programs' ),
				'not_found_in_trash'    => __( 'No Student Programs found in trash', 'student-programs' ),
				'parent_item_colon'     => __( 'Parent Student Programs:', 'student-programs' ),
				'menu_name'             => __( 'Student Programs Archive', 'student-programs' ),
			),
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array( 'title', 'editor' ),
			'capability_type'       => 'page',
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-admin-post',
			'show_in_rest'          => true,
			'rest_base'             => 'programs',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		)
	);

}
add_action( 'init', 'programs_init' );

/**
 * Sets the post updated messages for the `programs` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `programs` post type.
 */
function programs_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['programs'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Student Programs updated. <a target="_blank" href="%s">View Student Programs</a>', 'student-programs' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'student-programs' ),
		3  => __( 'Custom field deleted.', 'student-programs' ),
		4  => __( 'Student Programs updated.', 'student-programs' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Student Programs restored to revision from %s', 'student-programs' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Student Programs published. <a href="%s">View Student Programs</a>', 'student-programs' ), esc_url( $permalink ) ),
		7  => __( 'Student Programs saved.', 'student-programs' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Student Programs submitted. <a target="_blank" href="%s">Preview Student Programs</a>', 'student-programs' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf(
			__( 'Student Programs scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Student Programs</a>', 'student-programs' ),
			date_i18n( __( 'M j, Y @ G:i', 'student-programs' ), strtotime( $post->post_date ) ),
			esc_url( $permalink )
		),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Student Programs draft updated. <a target="_blank" href="%s">Preview Student Programs</a>', 'student-programs' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'programs_updated_messages' );
