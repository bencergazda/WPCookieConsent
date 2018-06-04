<?php
/**
 * Plugin Name: CC Cookie Consent
 * Plugin URI: https://github.com/progcode/WPCookieConsent
 * Plugin Issues: https://github.com/progcode/WPCookieConsent/issues
 * Description: Cookie Consent Plugin for WordPress. Original javascript plugin developed by Silktide
 * Version: 2.0.0
 * Author: WebPositive <hello@progweb.hu>
 * Author URI: https://progweb.hu
 * Tags: cookie, cookie consent, wordpress, silktide
 * Author e-mail: developer@progweb.hu
 * Text Domain: cc-cookie-consent
 * Domain Path: /locale
 */

if(!defined('ABSPATH')) exit('No direct script access allowed');
define('CC_VERSION','2.0.0');
define('CC_BUILD_DATE','2018-06-04');

/**
 * Load plugin translations
 */
function loadPluginTranslation()
{
    load_plugin_textdomain( 'cc-cookie-consent', FALSE, basename( dirname( __FILE__ ) ) . '/locale/' );
}
add_action( 'plugins_loaded', 'loadPluginTranslation' );

function wpSilktideCookieScripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        wp_register_script('cc-js', plugins_url( 'assets/plugin-js/cookieconsent.min.js', __FILE__ ), array(), CC_VERSION, true);
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
		window.cookieconsent_options = {
			<?php if(get_option('silktide_cc_type')): echo '"type": "'.esc_js(get_option('silktide_cc_type')).'",'; endif; ?>
			"palette": {
				"popup": {
					<?php if(get_option('silktide_cc_bg_color')): echo '"background": "'.esc_js(get_option('silktide_cc_bg_color')).'",'; endif; ?>
					<?php if(get_option('silktide_cc_text_color')): echo '"text": "'.esc_js(get_option('silktide_cc_text_color')).'",'; endif; ?>
				},
				"button": {
					<?php if(get_option('silktide_cc_button_bg_color')): echo '"background": "'.esc_js(get_option('silktide_cc_button_bg_color')).'",'; endif; ?>
					<?php if(get_option('silktide_cc_button_text_color')): echo '"text": "'.esc_js(get_option('silktide_cc_button_text_color')).'",'; endif; ?>
				}
			},
			"content": {
				<?php if(get_option('silktide_cc_message')): echo '"message": "'.esc_js(get_option('silktide_cc_message')).'",'; endif; ?>
				<?php if(get_option('silktide_cc_allow_button')): echo '"allow": "'.esc_js(get_option('silktide_cc_allow_button')).'",'; endif; ?>
				<?php if(get_option('silktide_cc_deny_button')): echo '"deny": "'.esc_js(get_option('silktide_cc_deny_button')).'",'; endif; ?>
				<?php if(get_option('silktide_cc_dismiss_button')): echo '"dismiss": "'.esc_js(get_option('silktide_cc_dismiss_button')).'",'; endif; ?>
				<?php if(get_option('silktide_cc_cookie_page')): echo '"href": "'.esc_js(get_option('silktide_cc_cookie_page')).'",'; endif; ?>
				<?php if(get_option('silktide_cc_more_button')): echo '"link": "'.esc_js(get_option('silktide_cc_more_button')).'",'; endif; ?>
			},
			<?php if(get_option('silktide_cc_theme')): echo '"theme": "'.esc_js(get_option('silktide_cc_theme')).'",'; endif; ?>
			<?php if(get_option('silktide_cc_position')): echo '"position": "'.esc_js(get_option('silktide_cc_position')).'",'; endif; ?>
		};

		window.addEventListener("load", function(){
			window.cookieconsent.initialise(window.cookieconsent_options);
		});
	</script>
    <?php
}
add_action('wp_footer', 'wpSilktideCookieInlineScripts');

/** Add Settings link */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpSilktideCookieSettingsLinks' );
function wpSilktideCookieSettingsLinks( $links )
{
    $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=cookie-consent') ) .'">'.__( 'Settings', 'cc-cookie-consent' ).'</a>';
    return $links;
}

/**
 * Add Settings Page
 */
add_action('admin_menu', 'wpSilktideCookieSettings');
function wpSilktideCookieSettings() {
    add_menu_page(__('Cookie Consent','cc-cookie-consent'), __('Cookie Consent','cc-cookie-consent'), 'manage_options', 'cookie-consent', 'wpSilktideCookieSettingsPage');
}

add_action('admin_menu', 'wpSilktideCookieSubMenu');
function wpSilktideCookieSubMenu() {
    add_submenu_page( 'cookie-consent',  __('Help/Information','cc-cookie-consent'),  __('Help/Information','cc-cookie-consent'), 'manage_options', 'cookie-consent-info', 'wpSilktideCookieHelpPage' );
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
                    __( 'Wow! Your plugin is ready! Would you like support the development? <a href="%s" target="_blank" rel="noopener">Click here</a>!', 'cc-cookie-consent' ),
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
        <a class="button" href="admin.php?page=cookie-consent-info"><?php _e('Click here for plugin help & information', 'cc-cookie-consent'); ?></a>
    </div>
    <?php
}

function wpSilktideInputField($input, $placeholder)
{
    echo '<input class="regular-text" type="text" name="'.$input.'" id="'.$input.'" value="'.htmlentities(get_option($input)).'" placeholder="'.htmlentities($placeholder).'" />';
}

function wpSilktideColorPicker($input, $placeholder)
{
	echo '<input class="cc_color_picker" type="text" name="'.$input.'" id="'.$input.'" value="'.htmlentities(get_option($input)).'" placeholder="'.htmlentities($placeholder).'" />';
}

function wpSilktideSelectField($link)
{
    echo '<select name="'.$link.'">';
	echo '<option value="0">'.__('-- Not selected --', 'cc-cookie-consent').'</option>';
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
    $option_title = __('Cookie Consent Settings', 'cc-cookie-consent');
    $option_desc = __('These settings are required to use Cookie Consent plugin on Your website. Please fill the form then see the frontend page!', 'cc-cookie-consent');
    $option_section = 'silktide-cc-plugin-section';
    $option_options = 'silktide-cc-plugin-options';
    wpSilktideCustomOptionTemplate($option_title, $option_desc, $option_section, $option_options);
}

/** Plugin Settings Fields */
function wpSilktideCookieChooseType()
{
    echo
        '<select name="silktide_cc_type" id="silktide_cc_type">'.
			'<option value="" '.selected( get_option('silktide_cc_type'), '', false).'>'.__('Just tell users that we use cookies', 'cc-cookie-consent').'</option>'.
        	'<option value="opt-out" '.selected( get_option('silktide_cc_type'), 'opt-out', false).'>'.__('Let users opt out of cookies', 'cc-cookie-consent').'</option>'.
			'<option value="opt-in" '.selected( get_option('silktide_cc_type'), 'opt-in', false).'>'.__('Ask users to opt into cookies', 'cc-cookie-consent').'</option>'.
        '</select>';
}

function wpSilktideCookieChooseTheme()
{
    echo
        '<select name="silktide_cc_theme" id="silktide_cc_theme">'.
			'<option value="block" '.selected( get_option('silktide_cc_theme'), 'top', false).'>'.__('Block', 'cc-cookie-consent').'</option>'.
        	'<option value="classic" '.selected( get_option('silktide_cc_theme'), 'classic', false).'>'.__('Classic', 'cc-cookie-consent').'</option>'.
			'<option value="edgeless" '.selected( get_option('silktide_cc_theme'), 'edgeless', false).'>'.__('Edgeless', 'cc-cookie-consent').'</option>'.
			'<option value="wire" '.selected( get_option('silktide_cc_theme'), 'wire', false).'>'.__('Wire', 'cc-cookie-consent').'</option>'.
        '</select>';
}

function wpSilktideCookieChoosePosition()
{
    echo
        '<select name="silktide_cc_position" id="silktide_cc_position">'.
            '<option value="top" '.selected( get_option('silktide_cc_position'), "top", false).'>'.__('Top', 'cc-cookie-consent').'</option>'.
            '<option value="bottom" '.selected( get_option('silktide_cc_position'), "bottom", false).'>'.__('Bottom', 'cc-cookie-consent').'</option>'.
            '<option value="top-left" '.selected( get_option('silktide_cc_position'), "top-left", false).'>'.__('Top left', 'cc-cookie-consent').'</option>'.
            '<option value="top-right" '.selected( get_option('silktide_cc_position'), "top-right", false).'>'.__('Top right', 'cc-cookie-consent').'</option>'.
            '<option value="bottom-left" '.selected( get_option('silktide_cc_position'), "bottom-left", false).'>'.__('Bottom left', 'cc-cookie-consent').'</option>'.
            '<option value="bottom-right" '.selected( get_option('silktide_cc_position'), "bottom-right", false).'>'.__('Bottom right', 'cc-cookie-consent').'</option>'.
        '</select>';
}

function wpSilktideCookieMessage()
{
    $input = 'silktide_cc_message';
    $placeholder = 'Headline text';
    wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieAllowButton()
{
    $input = 'silktide_cc_allow_button';
    $placeholder = 'Allow button text';
	wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieDenyButton()
{
    $input = 'silktide_cc_deny_button';
    $placeholder = 'Deny button text';
	wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieDismissButton()
{
    $input = 'silktide_cc_dismiss_button';
    $placeholder = 'Dismiss button text';
	wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieReadMoreButton()
{
    $input = 'silktide_cc_more_button';
    $placeholder = 'Read more button text';
	wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieBgColorPicker()
{
    $input = 'silktide_cc_bg_color';
    $placeholder = 'Background color';
	wpSilktideColorPicker($input, $placeholder);
}

function wpSilktideCookieButtonBgColorPicker()
{
    $input = 'silktide_cc_button_bg_color';
    $placeholder = 'Button background color';
	wpSilktideColorPicker($input, $placeholder);
}

function wpSilktideCookieTextColorPicker()
{
    $input = 'silktide_cc_text_color';
    $placeholder = 'Text color';
	wpSilktideColorPicker($input, $placeholder);
}

function wpSilktideCookieButtonTextColorPicker()
{
    $input = 'silktide_cc_button_text_color';
    $placeholder = 'Button text color';
	wpSilktideColorPicker($input, $placeholder);
}

function wpSilktideCookieLinkCookiePolicy()
{
    $link = 'silktide_cc_cookie_page';
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
	wp_enqueue_script('silktide_cc_admin_js', plugins_url('assets/js/admin.js', __FILE__), array('jquery', 'wp-color-picker'));

    add_settings_section('silktide-cc-plugin-section', null, null, 'silktide-cc-plugin-options');

    add_settings_field('silktide_cc_type', __('Choose compliance type', 'cc-cookie-consent'), 'wpSilktideCookieChooseType', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_theme', __('Choose theme', 'cc-cookie-consent'), 'wpSilktideCookieChooseTheme', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_position', __('Choose position', 'cc-cookie-consent'), 'wpSilktideCookieChoosePosition', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_message', __('Headline text', 'cc-cookie-consent'), 'wpSilktideCookieMessage', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_allow_button', __('Accept button text', 'cc-cookie-consent'), 'wpSilktideCookieAllowButton', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_deny_button', __('Deny button text', 'cc-cookie-consent'), 'wpSilktideCookieDenyButton', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_dismiss_button', __('Dismiss button text', 'cc-cookie-consent'), 'wpSilktideCookieDismissButton', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_more_button', __('Read more button text', 'cc-cookie-consent'), 'wpSilktideCookieReadMoreButton', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_cookie_page', __('Your cookie policy page', 'cc-cookie-consent'), 'wpSilktideCookieLinkCookiePolicy', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_bg_color', __('Background color', 'cc-cookie-consent'), 'wpSilktideCookieBgColorPicker', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_button_bg_color', __('Button background color', 'cc-cookie-consent'), 'wpSilktideCookieButtonBgColorPicker', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_text_color', __('Text color', 'cc-cookie-consent'), 'wpSilktideCookieTextColorPicker', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');
    add_settings_field('silktide_cc_button_text_color', __('Button text color', 'cc-cookie-consent'), 'wpSilktideCookieButtonTextColorPicker', 'silktide-cc-plugin-options', 'silktide-cc-plugin-section');

    register_setting('silktide-cc-plugin-section', 'silktide_cc_type');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_theme');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_position');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_message');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_allow_button');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_deny_button');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_dismiss_button');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_more_button');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_cookie_page');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_bg_color');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_button_bg_color');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_text_color');
    register_setting('silktide-cc-plugin-section', 'silktide_cc_button_text_color');

}
add_action('admin_init', 'wpSilktideCookieFields');