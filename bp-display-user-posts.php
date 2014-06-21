<?php

class BP_Display_User_Posts_Component extends BP_Component {
	/**
	 * Initial component setup.
	 */
	public function __construct() {
		parent::start(
			// Unique component ID
			'bpdup',

			// Used by BP when listing components (eg in the Dashboard)
			__( 'Display User Posts', 'bp-display-user-posts' )
		);
	}

	/**
	 * Set up component data, as required by BP.
	 */
	public function setup_globals( $args = array() ) {
		parent::setup_globals( array(
			'slug' => 'my-posts', // used for building URLs
		) );
	}

	/**
	 * Set up component navigation, and register display callbacks.
	 */
	public function setup_nav( $main_nav = array(), $sub_nav = array() ) {
		$main_nav = array(
			'name'            => __( 'My Posts', 'bp-display-user-posts' ),
			'slug'            => $this->slug,
			'position'        => 35,
			'default_subnav_slug' => 'my-posts',
			'screen_function' => array( $this, 'screen_function' ),
		);

		// BuddyPress needs to have at least one subnav item, even if
		// it's redundant
		$sub_nav[] = array(
			'name' => __( 'My Posts', 'bp-display-user-posts' ),
			'slug' => 'my-posts',
			'parent_slug' => 'my-posts',
			'parent_url' => bp_displayed_user_domain() . 'my-posts/',
		);

		parent::setup_nav( $main_nav, $sub_nav );
	}

	/**
	 * Set up display screen logic.
	 *
	 * We are using BP's plugins.php template as a wrapper, which is
	 * the easiest technique for compatibility with themes.
	 */
	public function screen_function() {
		add_action( 'bp_template_content', array( $this, 'my_posts_content' ) );
		bp_core_load_template( 'members/single/plugins' );
	}

	/**
	 * Render the content of the my-posts tab.
	 */
	public function my_posts_content() {
		// BP helper function to get the "displayed" user
		$user_id = bp_displayed_user_id();

		$posts_query = new WP_Query( array(
			'author'         => $user_id,
			'post_type'      => array(
				'post',
				'page',
			),
			'post_status'    => 'publish',
			'posts_per_page' => '-1',
		) );

		if ( $posts_query->have_posts() ) {
			echo '<ul>';

			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();

				printf(
					'<li><a href="%s">%s</a> - %s',
					esc_url( get_the_permalink( get_the_ID() ) ),
					esc_html( get_the_title() ),
					esc_html( get_the_time() )
				);
			}

			echo '</ul>';
		}
	}
}

/**
 * Bootstrap the component.
 */
function bpdup_init() {
	buddypress()->bpdup = new BP_Display_User_Posts_Component();
}
add_action( 'bp_loaded', 'bpdup_init' );
