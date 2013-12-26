<?php

/**
 * rtWidget Options Page
 *
 * @since rtWidget 1.0
 */
class rtWidgetsOptions {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        add_action( 'admin_menu', array($this, 'rtw_add_plugin_page' ));
        add_action( 'admin_init', array($this, 'rtw_page_init' ));
    }

    /**
     * Add options page
     */
    public function rtw_add_plugin_page() {
        global $rtwidgets;
        // This page will be under "Settings"
        add_options_page( __( 'rtWidgets Admin Settings', $rtwidgets->rtwidgets_text_domain ), __( 'rtWidgets Options', $rtwidgets->rtwidgets_text_domain ), 'manage_options', 'rtwidgets-options', array( $this, 'rtw_create_admin_page' ) );
    }

    /**
     * Options page callback
     */
    public function rtw_create_admin_page() {
        global $rtwidgets;

        // Set class property
        $this->options = get_option( 'rtw_options' ); ?>

        <div class="wrap rtwidgets-admin">
            <h2><?php _e( 'rtWidgets Options', $rtwidgets->rtwidgets_text_domain ); ?></h2>
            <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
                        <form method="post" action="options.php">
                            <?php settings_fields( 'rtw_option_group' ); // This prints out all hidden setting fields ?>
                            <div id="post-body-content" class="postbox">
                                <div title="<?php _e( 'Click to toggle', $rtwidgets->rtwidgets_text_domain ); ?>" class="handlediv"><br></div>
                                <h3 class="hndle">
                                    <span><?php _e( 'rtWidgets Settings', $rtwidgets->rtwidgets_text_domain ); ?></span>
                                </h3>
                                <div class="inside"><?php
                                    do_settings_sections( 'rtwidgets-options' );
                                    submit_button(); ?>
                                </div>
                            </div>
                        </form>
                        <div id="postbox-container-1" class="postbox-container">
                            <div class="postbox" id="social">
                                <div title="<?php _e( 'Click to toggle', $rtwidgets->rtwidgets_text_domain ); ?>" class="handlediv"><br /></div>
                                <h3 class="hndle">
                                    <span><?php _e( 'Getting Social is Good', $rtwidgets->rtwidgets_text_domain ); ?></span>
                                </h3>
                                <div style="text-align:center;" class="inside">
                                    <a class="rtw-facebook" title="<?php _e( 'Become a fan on Facebook', $rtwidgets->rtwidgets_text_domain ); ?>" target="_blank" href="http://www.facebook.com/rtCamp.solutions/"></a>
                                    <a class="rtw-twitter" title="<?php _e( 'Follow us on Twitter', $rtwidgets->rtwidgets_text_domain ); ?>" target="_blank" href="https://twitter.com/rtcamp/"></a>
                                    <a class="rtw-gplus" title="<?php _e( 'Add to Circle', $rtwidgets->rtwidgets_text_domain ); ?>" target="_blank" href="https://plus.google.com/110214156830549460974/posts"></a>
                                    <a class="rtw-rss" title="<?php _e( 'Subscribe to our feeds', $rtwidgets->rtwidgets_text_domain ); ?>" target="_blank" href="http://feeds.feedburner.com/rtcamp/"></a>
                                </div>
                            </div>

                            <div class="postbox" id="donations">
                                <div title="<?php _e( 'Click to toggle', $rtwidgets->rtwidgets_text_domain ); ?>" class="handlediv"><br /></div>
                                <h3 class="hndle">
                                    <span><?php _e( 'Promote, Donate, Share', $rtwidgets->rtwidgets_text_domain ); ?>...</span>
                                </h3>
                                <div class="inside">
                                    <p><?php printf( __( 'Buy coffee/beer for team behind <a href="%s" title="rtWidgets">rtWidgets</a>.', $rtwidgets->rtwidgets_text_domain ), 'https://rtcamp.com/rtwidgets/' ); ?></p>
                                    <div class="rt-paypal" style="text-align: center">
                                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                            <input type="hidden" name="cmd" value="_donations" />
                                            <input type="hidden" name="business" value="paypal@rtcamp.com" />
                                            <input type="hidden" name="lc" value="US" />
                                            <input type="hidden" name="item_name" value="rtWidgets" />
                                            <input type="hidden" name="no_note" value="0" />
                                            <input type="hidden" name="currency_code" value="USD" />
                                            <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest" />
                                            <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="<?php _e( 'PayPal - The safer, easier way to pay online!', $rtwidgets->rtwidgets_text_domain ); ?>" />
                                            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                                        </form>
                                    </div>
                                    <div class="rt-social-share">
                                        <div class="rt-twitter rtw-social-box">
                                            <a href="http://twitter.com/share"  class="twitter-share-button" data-text="I &hearts; #rtWidgets"  data-url="https://rtcamp.com/rtwidgets/" data-count="vertical" data-via="rtWidgets"><?php _e( 'Tweet', $rtwidgets->rtwidgets_text_domain ); ?></a>
                                        </div>
                                        <div class="rt-facebook rtw-social-box">
                                            <a style=" text-align: center;" name="fb_share" type="box_count" share_url="https://rtcamp.com/rtwidgets/"></a>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="postbox" id="support">
                                <div title="<?php _e( 'Click to toggle', $rtwidgets->rtwidgets_text_domain ); ?>" class="handlediv"><br /></div>
                                <h3 class="hndle">
                                    <span><?php _e( 'Free Support', $rtwidgets->rtwidgets_text_domain ); ?></span>
                                </h3>
                                <div class="inside">
                                    <p><?php printf( __( 'If you are facing any problems while using rtWidgets, or have good ideas for improvements, please discuss the same in our <a href="%s" target="_blank" title="Click here for rtWidgets Free Support">Support forums</a>', $rtwidgets->rtwidgets_text_domain ), 'https://rtcamp.com/support/forum/rtwidgets/' ); ?>.</p>
                                </div>
                            </div>

                            <div class="postbox" id="latest_news">
                                <div title="<?php _e( 'Click to toggle', $rtwidgets->rtwidgets_text_domain ); ?>" class="handlediv"><br /></div>
                                <h3 class="hndle">
                                    <span><?php _e( 'Latest News', $rtwidgets->rtwidgets_text_domain ); ?></span>
                                </h3>
                                <div class="inside"><?php
                                    require_once( ABSPATH . WPINC . '/feed.php' );          // Get RSS Feed(s)
                                    $maxitems = 0;
                                    $rss = fetch_feed( 'https://rtcamp.com/blog/feed/' );    // Get a SimplePie feed object from the specified feed source.
                                    if ( !is_wp_error( $rss ) ) {                           // Checks that the object is created correctly
                                        $maxitems = $rss->get_item_quantity( 5 );           // Figure out how many total items there are, but limit it to 5.
                                        $rss_items = $rss->get_items( 0, $maxitems );       // Build an array of all the items, starting with element 0 (first element).
                                    } ?>
                                    <ul role="list"><?php
                                        if ( $maxitems == 0 ) {
                                            echo '<li>' . __( 'No items', $rtwidgets->rtwidgets_text_domain ) . '.</li>';
                                        } else {
                                            foreach( $rss_items as $item ) {                // Loop through each feed item and display each item as a hyperlink. ?>
                                                <li role="listitem">
                                                    <a href='<?php echo $item->get_permalink(); ?>' title='<?php echo __( 'Posted ', $rtwidgets->rtwidgets_text_domain ) . $item->get_date( 'j F Y | g:i a' ); ?>'><?php echo $item->get_title(); ?></a>
                                                </li><?php
                                            }
                                        } ?>
                                    </ul>
                                </div> <!-- End of .inside -->
                            </div> <!-- End of #latest_news -->
                        </div> <!-- End of .postbox-container -->
                    </div> <!-- End of #post-body -->
                
            </div> <!-- End of #poststuff -->
        </div> <!-- End of wrap rtwidgets-admin -->
        <?php
    }

    /**
     * Register and add settings
     */
    public function rtw_page_init() {
        global $rtwidgets;
        register_setting( 'rtw_option_group', 'rtw_options', array( $this, 'rtw_sanitize' ) );
        add_settings_section( 'setting_section_id', '', array( $this, 'rtw_print_section_info' ), 'rtwidgets-options' );

        foreach( $rtwidgets->rt_widgets as $rtw_widget_class ) {
            $class_object = new $rtw_widget_class();
            add_settings_field( $rtw_widget_class, $class_object->name, array( $this, 'rtw_print_settings_field' ), 'rtwidgets-options', 'setting_section_id', $rtw_widget_class );
            unset( $class_object );
        }
        add_action( 'admin_enqueue_scripts', array( $this, 'rtw_admin_enqueue_assets' ), 999 );
    }

    /**
     * Adds neccessary scripts & styles for admin menu
     * 
     */
    public function rtw_admin_enqueue_assets( $hook ) {
        if ( 'settings_page_rtwidgets-options' != $hook && 'widgets.php' != $hook ) {
            return;
        }
        wp_enqueue_style( 'rtw-admin-icon-fonts', RTWIDGETS_ASSETS . 'icon-font/css/rtw-fontello.css', array(), '1.0' );
        wp_enqueue_style( 'rtw-admin-css', RTWIDGETS_ADMIN_ASSETS . 'rtwidgets-admin-style.css', array( 'rtw-admin-icon-fonts' ), '1.0' );

        if ( 'settings_page_rtwidgets-options' != $hook ) {
            return;
        }
        wp_enqueue_script( 'rtw-fb-share', ( 'http://static.ak.fbcdn.net/connect.php/js/FB.Share' ), '', '', true);
        wp_enqueue_script( 'rtw-twitter-share', ( 'http://platform.twitter.com/widgets.js' ), '', '', true);
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function rtw_sanitize($input) {
        global $rtwidgets;
        $new_input = array();
        foreach($rtwidgets->rt_widgets as $rtw_widget_class ) {
            if ( isset( $input[$rtw_widget_class] ) ) {
                $new_input[$rtw_widget_class] = $input[$rtw_widget_class];
            }
        }
        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function rtw_print_section_info() {
        global $rtwidgets;
        echo '<p>' . __( 'Check/Uncheck below checkboxs to enable OR disable widgets.', $rtwidgets->rtwidgets_text_domain ) . '</p>';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function rtw_print_settings_field( $rtw_widget_class ) {
        global $rtwidgets;
        $enabled_value = isset( $this->options[$rtw_widget_class] ) ? $this->options[$rtw_widget_class] : '1';
        
        // Fetch Widget Description
        $class_object = new $rtw_widget_class();
        $rtw_desc = $class_object->widget_options['description'];
        unset( $class_object ); ?>
        
        <input type='hidden' name="rtw_options[<?php echo $rtw_widget_class; ?>]" value='0' />
        <input type="checkbox" id='<?php echo $rtw_widget_class; ?>' name="rtw_options[<?php echo $rtw_widget_class; ?>]" <?php checked($enabled_value, '1', TRUE); ?> value='1' />
        <label for="<?php echo $rtw_widget_class; ?>"><?php _e( 'Enable Widget', $rtwidgets->rtwidgets_text_domain ); ?></label>
        <em class="rtw_desc"><?php echo $rtw_desc; ?></em><?php
    }
}

if ( is_admin() ) {
    $rtw_settings_page = new rtWidgetsOptions ();
}