<?php
/**
 * Plugin Name: CC Cookie Consent
 * Plugin URI: https://github.com/progcode/WPCookieConsent
 * Plugin Issues: https://github.com/progcode/WPCookieConsent/issues
 * Description: Cookie Consent Plugin for WordPress. Original javascript plugin developed by Silktide
 * Version: 1.2.0
 * Author: WebPositive <hello@progweb.hu>
 * Author URI: https://progweb.hu
 * Tags: cookie, cookie consent, wordpress, silktide
 * Author e-mail: developer@progweb.hu
 * Text Domain: cookie-consent
 * Domain Path: /locale
 */

if(!defined('ABSPATH')) exit('No direct script access allowed');
define('CC_VERSION','1.2.0');
define('CC_BUILD_DATE','2017-06-09');

global $type;
global $theme;
global $position;
global $message;
global $more_button;
global $more_link;
global $allow_button;
global $deny_button;
global $dismiss_button;

$type           = null;
$theme          = "block";
$position       = "bottom";
$message        = __( 'Hello! This website uses cookies to ensure you get the best experience on our website', 'cookie-consent' );
$more_button    = __( 'More info', 'cookie-consent' );
$more_link      = null;
$allow_button   = __( 'Allow', 'cookie-consent' );
$deny_button    = __( 'Deny', 'cookie-consent' );
$dismiss_button = __( 'Dismiss', 'cookie-consent' );

/**
 * Load plugin translations
 */
function loadPluginTranslation()
{
    load_plugin_textdomain( 'cookie-consent', FALSE, basename( dirname( __FILE__ ) ) . '/locale/' );
}
add_action( 'plugins_loaded', 'loadPluginTranslation' );

function wpSilktideCookieScripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        wp_register_script('cc-js', ''.plugins_url( 'assets/plugin-js/cookieconsent.min.js', __FILE__ ).'', array(), CC_VERSION, true);
        wp_enqueue_script('cc-js');
    }
}
add_action('wp_enqueue_scripts', 'wpSilktideCookieScripts');

/**
 * Load css to wp_head() without js/http request
 * Github issue: https://github.com/progcode/WPCookieConsent/issues/2
 */
function wpSilktideCookieStyle()
{
	if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
		wp_register_style('cc-css', plugins_url('assets/plugin-css/cookieconsent.min.css', __FILE__), array(), CC_VERSION);
		wp_enqueue_style('cc-css');
	}
}
add_action('wp_enqueue_scripts', 'wpSilktideCookieStyle');

/** Add CC config js if cookie.consent.js loaded */
function wpSilktideCookieInlineScripts()
{ ?>
    <script>
		window.addEventListener("load", function(){
			window.cookieconsent.initialise({
				"type": "<?php if(get_option('silktide_cc_type')): echo esc_js(get_option('silktide_cc_type')); else: global $type; echo esc_js($type); endif; ?>",
				"content": {
					"message":"<?php if(get_option('silktide_cc_text_message')): echo esc_js(get_option('silktide_cc_text_message')); else: global $message; echo esc_js($message); endif; ?>",
					"allow":"<?php if(get_option('silktide_cc_text_allow_button')): echo esc_js(get_option('silktide_cc_text_allow_button')); else: global $allow_button; echo esc_js($allow_button); endif; ?>",
					"deny":"<?php if(get_option('silktide_cc_text_deny_button')): echo esc_js(get_option('silktide_cc_text_deny_button')); else: global $deny_button; echo esc_js($deny_button); endif; ?>",
					"dismiss":"<?php if(get_option('silktide_cc_text_dismiss_button')): echo esc_js(get_option('silktide_cc_text_dismiss_button')); else: global $dismiss_button; echo esc_js($dismiss_button); endif; ?>",
					"href":"<?php if(get_option('silktide_cc_cookie_page')): echo esc_js(get_option('silktide_cc_cookie_page')); else: global $more_link; echo esc_js($more_link); endif; ?>",
					"link":"<?php if(get_option('silktide_cc_text_more_button')): echo esc_js(get_option('silktide_cc_text_more_button')); else: global $more_button; echo esc_js($more_button); endif; ?>",
				},
				"theme":"<?php if(get_option('silktide_cc_theme')): echo esc_js(get_option('silktide_cc_theme')); else: global $theme; echo esc_js($theme); endif; ?>",
				"position": "<?php if(get_option('silktide_cc_position')): echo esc_js(get_option('silktide_cc_position')); else: global $position; echo esc_js($position); endif; ?>"
			});
        });
    </script>
    <?php
}
add_action('wp_footer', 'wpSilktideCookieInlineScripts');

/** Add Settings link */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpSilktideCookieSettingsLinks' );
function wpSilktideCookieSettingsLinks( $links )
{
    $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=cookie-consent') ) .'">'.__( 'Settings', 'cookie-consent' ).'</a>';
    return $links;
}

/**
 * Add Settings Page
 */
add_action('admin_menu', 'wpSilktideCookieSettings');
function wpSilktideCookieSettings() {
    add_menu_page(__('Cookie Consent','cookie-consent'), __('Cookie Consent','cookie-consent'), 'manage_options', 'cookie-consent', 'wpSilktideCookieSettingsPage');
}

add_action('admin_menu', 'wpSilktideCookieSubMenu');
function wpSilktideCookieSubMenu() {
    add_submenu_page( 'cookie-consent',  __('Help/Information','cookie-consent'),  __('Help/Information','cookie-consent'), 'manage_options', 'cookie-consent-info', 'wpSilktideCookieHelpPage' );
}

/** option template for settings pages */
function wpSilktideCustomOptionTemplate($option_title, $option_desc, $option_section, $option_options)
{
    ?>
    <div class="wrap">
        <h1><?php __($option_title); ?></h1>
        <p><?php __($option_desc) ?></p>
        <div class="updated">
            <p>
                <?php
                printf(
                    __( "Wow! Your plugin is ready! Would you like support the development? <a href='%s' target='_blank' rel='noopener'>Click here</a>!", "cookie-consent" ),
                    'https://progweb.hu/cc?utm_soure=plugin_admin'
                );
                ?>
            </p>
        </div>
        <form class="cc" method="post" action="options.php" id="cookieConsentSettings">
            <?php
            settings_fields($option_section);
            do_settings_sections($option_options);
            submit_button();
            ?>
        </form>
        <hr/>
        <a class="button" href="admin.php?page=cookie-consent-info"><?php _e('Click here for plugin help & information', 'cookie-consent'); ?></a>
    </div>
    <?php
}

function wpSilktideInputField($input, $placeholder)
{
    echo '<input class="regular-text" type="text" name="'.$input.'" id="'.$input.'" value="'.get_option($input).'" placeholder="'.$placeholder.'" />';
}

function wpSilktideSelectField($link)
{
    echo '<select name="'.$link.'">';	
	echo '<option value="0">'.__('-- Not selected --', 'cookie-consent').'</option>';
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
function wpSilktideCookieSettingsPage()
{
    $option_title = __('Cookie Consent Settings', 'cookie-consent');
    $option_desc = __('This settings are required to use Cookie Consent plugin on Your website. Please fill the form then see the frontend page!', 'cookie-consent');
    $option_section = "silktide-cc-plugin-section";
    $option_options = "silktide-cc-plugin-options";
    wpSilktideCustomOptionTemplate($option_title, $option_desc, $option_section, $option_options);
}

/** Plugin Settings Fields */
function wpSilktideCookieChooseType()
{
    echo
        "<select name='silktide_cc_type' id='silktide_cc_type'>".
			"<option value='' ".selected( get_option('silktide_cc_type'), '', false).">".__('Just tell users that we use cookies', 'cookie-consent')."</option>".
        	"<option value='opt-out' ".selected( get_option('silktide_cc_type'), 'opt-out', false).">".__('Let users opt out of cookies', 'cookie-consent')."</option>".
			"<option value='opt-in' ".selected( get_option('silktide_cc_type'), 'opt-in', false).">".__('Ask users to opt into cookies', 'cookie-consent')."</option>".
        "</select>";
}

function wpSilktideCookieChooseTheme()
{
    echo
        "<select name='silktide_cc_theme' id='silktide_cc_theme'>".
			"<option value='block' ".selected( get_option('silktide_cc_theme'), 'top', false).">".__('Block', 'cookie-consent')."</option>".
        	"<option value='classic' ".selected( get_option('silktide_cc_theme'), 'classic', false).">".__('Classic', 'cookie-consent')."</option>".
			"<option value='edgeless' ".selected( get_option('silktide_cc_theme'), 'edgeless', false).">".__('Edgeless', 'cookie-consent')."</option>".
			"<option value='wire' ".selected( get_option('silktide_cc_theme'), 'wire', false).">".__('Wire', 'cookie-consent')."</option>".
        "</select>";
}

function wpSilktideCookieChoosePosition()
{
    echo
        "<select name='silktide_cc_position' id='silktide_cc_position'>".
            "<option value='top' ".selected( get_option('silktide_cc_position'), 'top', false).">".__('Top', 'cookie-consent')."</option>".
            "<option value='bottom' ".selected( get_option('silktide_cc_position'), 'bottom', false).">".__('Bottom', 'cookie-consent')."</option>".
            "<option value='top-left' ".selected( get_option('silktide_cc_position'), 'top-left', false).">".__('Top left', 'cookie-consent')."</option>".
            "<option value='top-right' ".selected( get_option('silktide_cc_position'), 'top-right', false).">".__('Top right', 'cookie-consent')."</option>".
            "<option value='bottom-left' ".selected( get_option('silktide_cc_position'), 'bottom-left', false).">".__('Bottom left', 'cookie-consent')."</option>".
            "<option value='bottom-right' ".selected( get_option('silktide_cc_position'), 'bottom-right', false).">".__('Bottom right', 'cookie-consent')."</option>".
        "</select>";
}

function wpSilktideCookieTextHeadline()
{
    $input = "silktide_cc_text_message";
    $placeholder = "Headline text";
    wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieTextAllowButton()
{
    $input = "silktide_cc_text_allow_button";
    $placeholder = "Allow button text";
    wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieTextDenyButton()
{
    $input = "silktide_cc_text_deny_button";
    $placeholder = "Deny button text";
    wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieTextDismissButton()
{
    $input = "silktide_cc_text_dismiss_button";
    $placeholder = "Dismiss button text";
    wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieTextReadMoreButton()
{
    $input = "silktide_cc_text_more_button";
    $placeholder = "Read more button text";
    wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieLinkCookiePolicy()
{
    $link = "silktide_cc_cookie_page";
    wpSilktideSelectField($link);
}

/** Plugin help & Information Tab */
function wpSilktideCookieHelpPage()
{
    include_once('view/help.php');
}

/**
 * Save and get options
 */
function wpSilktideCookieFields()
{
    add_settings_section("silktide-cc-plugin-section", null, null, "silktide-cc-plugin-options");

    add_settings_field("silktide_cc_type", __('Choose compliance type', 'cookie-consent'), "wpSilktideCookieChooseType", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_theme", __('Choose theme', 'cookie-consent'), "wpSilktideCookieChooseTheme", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_position", __('Choose position', 'cookie-consent'), "wpSilktideCookieChoosePosition", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_text_message", __('Headline text', 'cookie-consent'), "wpSilktideCookieTextHeadline", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_text_allow_button", __('Accept button text', 'cookie-consent'), "wpSilktideCookieTextAllowButton", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_text_deny_button", __('Deny button text', 'cookie-consent'), "wpSilktideCookieTextDenyButton", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_text_dismiss_button", __('Dismiss button text', 'cookie-consent'), "wpSilktideCookieTextDismissButton", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_text_more_button", __('Read more button text', 'cookie-consent'), "wpSilktideCookieTextReadMoreButton", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_cookie_page", __('Your cookie policy page', 'cookie-consent'), "wpSilktideCookieLinkCookiePolicy", "silktide-cc-plugin-options", "silktide-cc-plugin-section");

    register_setting("silktide-cc-plugin-section", "silktide_cc_type");
    register_setting("silktide-cc-plugin-section", "silktide_cc_theme");
    register_setting("silktide-cc-plugin-section", "silktide_cc_position");
    register_setting("silktide-cc-plugin-section", "silktide_cc_text_message");
    register_setting("silktide-cc-plugin-section", "silktide_cc_text_allow_button");
    register_setting("silktide-cc-plugin-section", "silktide_cc_text_deny_button");
    register_setting("silktide-cc-plugin-section", "silktide_cc_text_dismiss_button");
    register_setting("silktide-cc-plugin-section", "silktide_cc_text_more_button");
    register_setting("silktide-cc-plugin-section", "silktide_cc_cookie_page");

}
add_action("admin_init", "wpSilktideCookieFields");