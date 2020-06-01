<?php
// Add our Settings Page to the WP Admin menu
function wrp_add_settings_page_to_menu() {
    add_submenu_page(
        'options-general.php', // parent page
        'WCMS19 Related Posts Settings', // page title
        'Related Posts', // menu title
        'manage_options', // minimum capability
        'relatedposts', // slug for our page
        'wrp_settings_page', // callback to render page
    );
}
add_action('admin_menu', 'wrp_add_settings_page_to_menu');

// Render Settings page
function wrp_settings_page() {
    ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="POST" action="options.php">
                <?php 
                    // Output security fields for the registered setting
                    settings_fields("wrp_general_options");

                    // Output setting sections and their fields
                    do_settings_sections("relatedposts");

                    // output save settings button
                    submit_button();
                ?>
            </form>
        </div>
    <?php
}

// Register all options for our settings page
function wrp_settings_init() {
    /* 
    * Add Settings Section 'General Options'
    */
    add_settings_section(
        'wrp_general_options', // id
        'General Options',  // Section title
        'wrp_general_options_section', // callback for rendering content below title and above settings fields
        'relatedposts', // page to add this settings section to
    );

    /* 
    *   Add Settings fields to Settings Section 'General Options'.
    */
    add_settings_field(
        'wrp_default_title', // id
        'Default Title', // label
        'wrp_default_title_cb', // Callback for rendering form field
        'relatedposts', // page to add settings field to
        'wrp_general_options', // section to add settings field to
    );
    register_setting('wrp_general_options', 'wrp_default_title');

    add_settings_field(
        'wrp_add_to_posts', // id
        'Add Related Posts to all posts', // label
        'wrp_add_to_posts_cb', // Callback for rendering form field
        'relatedposts', // page to add settings field to
        'wrp_general_options', // section to add settings field to
    );
    register_setting('wrp_general_options', 'wrp_add_to_posts');
}
add_action('admin_init', 'wrp_settings_init');

function wrp_general_options_section() {
    ?>
        <p>This is a very nice section. Best settings ever</p>
        <?php 
            var_dump([
                'wrp_default_title' =>  get_option('wrp_default_title'),
                'wrp_add_to_posts'  =>  get_option('wrp_add_to_posts'),
            ]);
        ?>
    <?php
}

function wrp_default_title_cb() {
    ?>
        <input 
            type="text"
            name="wrp_default_title"
            id="wrp_default_title"
            value="<?php echo get_option('wrp_default_title', 'Related Posts'); ?>"
            >
    <?php
}

function wrp_add_to_posts_cb() {
    ?>
		<input
			type="checkbox"
			name="wrp_add_to_posts"
			id="wrp_add_to_posts"
			value="1"
			<?php
				checked(1, get_option('wrp_add_to_posts'));
			?>
		>
	<?php
}