<?php

/*
Plugin Name: BP Display User Posts
Version: 1.0
Author: Boone B Gorges
*/

/**
 * Load only when BuddyPress is present.
 */
function bpdup_include() {
	require( dirname( __FILE__ ) . '/bp-display-user-posts.php' );
}
add_action( 'bp_include', 'bpdup_include' );
