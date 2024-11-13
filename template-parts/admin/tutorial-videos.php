<?php

wp_enqueue_script( 'helphub_structure', 'https://hewittmatthews.co.uk/wp-content/themes/hewitt-matthews/includes/help-hub/helphub-structure.js', array(), THEME_VERSION);
wp_enqueue_script( 'helphub_videos_script', 'https://hewittmatthews.co.uk/wp-content/themes/hewitt-matthews/includes/help-hub/tutorial-videos.js', array(), THEME_VERSION);
wp_enqueue_script( 'helphub_changelog_script', 'https://hewittmatthews.co.uk/wp-content/themes/hewitt-matthews/includes/help-hub/changelog.js', array(), THEME_VERSION);
wp_localize_script( 'helphub_videos_script', 'websiteName', array( 'website_name' => get_bloginfo('name') ) );
wp_localize_script( 'helphub_videos_script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
wp_enqueue_style( 'tutorial_styles', 'https://hewittmatthews.co.uk/wp-content/themes/hewitt-matthews/includes/help-hub/tutorial-videos.css', array(), THEME_VERSION);
	

?>