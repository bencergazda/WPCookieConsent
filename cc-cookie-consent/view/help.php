<?php
/**
 * @Package: CC Cookie Consent (Silktide)
 * @View: Help Page
 */
?>

<div class="wrap">
    <h1><?php _e('Cookie Consent Help & Information', 'cc-cookie-consent'); ?></h1>
    <h3><?php _e('What is this?', 'cc-cookie-consent'); ?></h3>
    <p>
        <?php _e('The <b>CC Cookie Consent</b> plugin is an unofficial WordPress plugin version of the', 'cc-cookie-consent'); ?>
        <b><a href="https://silktide.com/tools/cookie-consent/" title="Silktide Cookie Consent" target="_blank" rel="noopener">Silktide Cookie Consent</a></b>.
        <?php _e('The most popular solution to the EU Cookie Law.', 'cc-cookie-consent'); ?>
    </p>
    <h3><?php _e('Who developed this plugin?', 'cc-cookie-consent'); ?></h3>
    <p>
        <?php _e('This plugin developed by WebPositive from Hungary.', 'cc-cookie-consent'); ?>
        <a href="https://progweb.hu?utm_soure=plugin_admin" target="_blank" rel="noopener"><?php _e('Click here for more information', 'cc-cookie-consent'); ?></a>
    </p>
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
    <hr />
    <h3><?php _e('Why required this plugin for my site?', 'cc-cookie-consent'); ?></h3>
    <p>
        <?php _e('From October 2015 a new privacy law came into effect across the EU. The law requires that websites ask visitors for consent to use most web cookies.', 'cc-cookie-consent'); ?><br/>
        <?php _e('More information please read this:', 'cc-cookie-consent'); ?>
        <a href="http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm" title="<?php _e('EU Cookies Law', 'cc-cookie-consent'); ?>" target="_blank" rel="noopener"><?php _e('EU Cookies Law', 'cc-cookie-consent'); ?></a>
    </p>
    <h3><?php _e('Why Cookie Consent?', 'cc-cookie-consent'); ?></h3>
        <p><b><?php _e('Free & open source', 'cc-cookie-consent'); ?></b></p>
        <p><?php _e('You\'re forever free to copy, modify and even sell Cookie Consent.', 'cc-cookie-consent'); ?></p>
        <p><b><?php _e('Lightweight', 'cc-cookie-consent'); ?></b></p>
        <p><?php _e('Just 3.5k when minified, and you don\'t need JQuery or anything else.', 'cc-cookie-consent'); ?></p>
        <p><b><?php _e('Customisable'); ?></b></p>
        <p><?php _e('Choose from one of our built-in themes or build your own with CSS.', 'cc-cookie-consent'); ?></p>
    <hr />
    <h3><?php _e('Default Settings', 'cc-cookie-consent'); ?></h3>
    <p><?php _e('The plugin includes the following default configuration:', 'cc-cookie-consent'); ?></p>
    <ul class="plugin-data">
        <li>
            <b><?php _e('Headline text:', 'cc-cookie-consent'); ?></b> "Hello! This website uses cookies to ensure you get the best experience on our website"
        </li>
        <li>
            <b><?php _e('Accept button:', 'cc-cookie-consent'); ?></b> "Got it!"
        </li>
        <li>
            <b><?php _e('Read more button:', 'cc-cookie-consent'); ?></b> "More information"
        </li>
    </ul>
    <p><b><?php _e('Theme settings', 'cc-cookie-consent'); ?></b></p>
    <img class="img-responsive" src="<?php echo plugins_url(); ?>/cc-cookie-consent/assets/img/theme_docs.png" alt="<?php _e('Theme settings', 'cc-cookie-consent'); ?>" />
    <p><a class="button" href="admin.php?page=cookie-consent"><?php _e('You can change settings here', 'cc-cookie-consent'); ?></a></p>
    <p><a class="button" href="https://silktide.com/tools/cookie-consent/docs/" target="_blank" rel="noopener"><?php _e('Silktide Cookie Consent documentation', 'cc-cookie-consent'); ?></a></p>
    <hr />
    <h3><?php _e('Version', 'cc-cookie-consent'); ?></h3>
    <ul class="plugin-data">
        <li>
            <b><?php _e('Plugin version:', 'cc-cookie-consent'); ?></b> <?php echo CC_VERSION; ?>
        </li>
        <li>
            <b><?php _e('Plugin Build date:', 'cc-cookie-consent'); ?></b> <?php echo CC_BUILD_DATE; ?>
        </li>
    </ul>
</div>
