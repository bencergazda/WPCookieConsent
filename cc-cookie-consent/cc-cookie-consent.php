<?php
/**
 * Plugin Name: CC Cookie Consent (Silktide)
 * Plugin URI: https://progweb.hu/cc
 * Description: Cookie Consent Plugin for WordPress. Original javascript plugin developed by Silktide
 * Version: 1.0.1
 * Author: WebPositive <hello@progweb.hu>
 * Author URI: https://progweb.hu
 * Tags: cookie, cookie consent, wordpress, silktide
 * Author e-mail: developer@progweb.hu
 */

if(!defined('ABSPATH'))exit;
define('CC_VERSION','1.0.1');
define('CC_BUILD_DATE','2015-10-08');

global $theme;
global $message;
global $more_info;
global $more_link;
global $ok_button;

$more_theme = "light-bottom";
$message = "Hello! This website uses cookies to ensure you get the best experience on our website";
$more_info = "More info";
$more_link = null;
$ok_button = "Got it!";

function cookie_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        wp_register_script('cc-js', ''.plugins_url().'/cc-cookie-consent/assets/plugin-js/cookieconsent.latest.min.js', array(), '1.0.1', false);
        wp_enqueue_script('cc-js');
    }
}
add_action('init', 'cookie_scripts');

/** Add CC config js if cookie.consent.js loaded */
function cc_inline_script() {
    if ( wp_script_is( 'cc-js', 'done' ) ) {
        ?>
        <script type="text/javascript">
            window.cookieconsent_options = {
                "message":"<?php if(get_option('cc_text_headline')): echo get_option('cc_text_headline'); else: global $message; echo $message; endif; ?>",
                "dismiss":"<?php if(get_option('cc_text_button')): echo get_option('cc_text_button'); else: global $ok_button; echo $ok_button; endif; ?>",
                "learnMore":"<?php if(get_option('cc_text_more_button')): echo get_option('cc_text_more_button'); else: global $more_info; echo $more_info; endif; ?>",
                "link":"<?php if(get_option('cc_cookie_page')): echo get_option('cc_cookie_page'); else: global $more_link; echo $more_link; endif; ?>",
                "theme":"<?php if(get_option('cc_theme')): echo get_option('cc_theme'); else: global $theme; echo $theme; endif; ?>"
            };
        </script>
        <?php
    }
}
add_action('wp_head', 'cc_inline_script');

/** Add Settings link */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'cc_settings_links' );
function cc_settings_links( $links ) {
    $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=cookie-consent') ) .'">Settings</a>';
    return $links;
}

/**
 * Add Settings Page
 */
add_action('admin_menu', 'create_settings_page');
function create_settings_page() {
    add_menu_page(__('Cookie Consent','cookie-consent'), __('Cookie Consent','cookie-consent'), 'manage_options', 'cookie-consent', 'settingsPage');
}

add_action('admin_menu', 'sub_menu_page');
function sub_menu_page() {
    add_submenu_page( 'cookie-consent', 'Help/Information', 'Help/Information', 'manage_options', 'cookie-consent-info', 'helpPage' );
}

/** option template for settings pages */
function custom_option_template($option_title, $option_desc, $option_section, $option_options)
{
    ?>
    <div class="wrap">
        <h1><?php _e($option_title); ?></h1>
        <p><?php _e($option_desc) ?></p>
        <form class="cc" method="post" action="options.php" id="cookieConsentSettings">
            <?php
            settings_fields($option_section);
            do_settings_sections($option_options);
            submit_button();
            ?>
        </form>
        <hr/>
        <a class="button" href="admin.php?page=cookie-consent-info"><?php _e('Click here for plugin help & information'); ?></a>
    </div>
    <?php
}

function input_field($input, $placeholder) {
    echo '<input class="regular-text" type="text" name="'.$input.'" id="'.$input.'" value="'.get_option($input).'" placeholder="'.$placeholder.'" />';
}

function option_select_page($link) {
    echo '<select name="'.$link.'">';
        $selected_page = get_option($link);
        $pages = get_pages();
        foreach ( $pages as $page ) {
            $option = '<option value="' . get_page_link( $page->ID ) . '" ';
            $option .= ( get_page_link( $page->ID ) == $selected_page ) ? 'selected="selected"' : '';
            $option .= '>';
            $option .= $page->post_title;
            $option .= '</option>';
            echo $option;
        }
    echo '</select>';
}

/** Plugin Settings Tab */
function settingsPage() {
    $option_title = "Cookie Consent Settings";
    $option_desc = "This settings are required to use Cookie Consent plugin on Your website. Please fill the form then see the frontend page!";
    $option_section = "cc-plugin-section";
    $option_options = "cc-plugin-options";
    custom_option_template($option_title, $option_desc, $option_section, $option_options);
}

/** Plugin Settings Fields */
function chooseTheme() {
    echo
        "<select name='cc_theme' id='cc_theme'>".
            "<option value='dark-top' ".selected( get_option('cc_theme'), 'dark-top', false).">Dark Top</option>".
            "<option value='dark-floating' ".selected( get_option('cc_theme'), 'dark-floating', false).">Dark Floating</option>".
            "<option value='dark-bottom' ".selected( get_option('cc_theme'), 'dark-bottom', false).">Dark Bottom</option>".
            "<option value='light-floating' ".selected( get_option('cc_theme'), 'light-floating', false).">Dark Floating</option>".
            "<option value='light-top' ".selected( get_option('cc_theme'), 'light-top', false).">Light Top</option>".
            "<option value='light-bottom' ".selected( get_option('cc_theme'), 'light-bottom', false).">Light Bottom</option>".
        "</select>";
}

function textHeadline() {
    $input = "cc_text_headline";
    $placeholder = "Headline text";
    input_field($input, $placeholder);
}

function textAcceptButton() {
    $input = "cc_text_button";
    $placeholder = "Accept button text";
    input_field($input, $placeholder);
}

function textReadMoreButton() {
    $input = "cc_text_more_button";
    $placeholder = "Read more button text";
    input_field($input, $placeholder);
}

function linkCookiePolicy() {
    $link = "cc_cookie_page";
    option_select_page($link);
}

/** Plugin help & Information Tab */
function helpPage() {
    include_once('view/help.php');
}

/**
 * Save and get options
 */
function cc_fields()
{
    add_settings_section("cc-plugin-section", null, null, "cc-plugin-options");

    add_settings_field("cc_theme", "Choose theme", "chooseTheme", "cc-plugin-options", "cc-plugin-section");
    add_settings_field("cc_text_headline", "Headline text", "textHeadline", "cc-plugin-options", "cc-plugin-section");
    add_settings_field("cc_text_button", "Accept button text", "textAcceptButton", "cc-plugin-options", "cc-plugin-section");
    add_settings_field("cc_text_more_button", "Read more button text", "textReadMoreButton", "cc-plugin-options", "cc-plugin-section");
    add_settings_field("cc_cookie_page", "Your cookie policy page", "linkCookiePolicy", "cc-plugin-options", "cc-plugin-section");

    register_setting("cc-plugin-section", "cc_theme");
    register_setting("cc-plugin-section", "cc_text_headline");
    register_setting("cc-plugin-section", "cc_text_button");
    register_setting("cc-plugin-section", "cc_text_more_button");
    register_setting("cc-plugin-section", "cc_cookie_page");

}
add_action("admin_init", "cc_fields");