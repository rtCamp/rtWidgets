<?php

/**
 * Custom Widget for FeedBurner RSS Subscription and Social Share
 *
 * @since rtWidget 1.0
 */
class rtw_subscribe_widget extends WP_Widget {

    public $meta = array();
    
    public function __construct() {
        global $rtwidgets;
        $widget_ops = array( 
            'classname'     => 'rtw-subscribe-widget-container', 
            'description'   => __( 'Widget for email subscription form and Social Icons such as Facebook, Twitter, etc.', $rtwidgets->rtwidgets_text_domain ) 
        );
        parent::__construct( 'rtw_subscribe_widget_container', __( 'rtw: Subscribe Widget', $rtwidgets->rtwidgets_text_domain ), $widget_ops );
        
        /**
         * Set Default Social Icon Order, ID, Title and Backend Label
         * 
         * Default ID Support: 
         * rss, facebook, github-circled, youtube, info, flickr-circled, vimeo, 
         * twitter, gplus, pinterest, linkedin, stumbleupon, instagram, paypal, 
         * picasa, soundcloud, wordpress, blogger, delicious, deviantart, digg, 
         * foursquare, reddit, stackoverflow, tumblr
         */
        $rtw_default_social_array = array(
            array(
                'id'            => 'facebook',
                'title'         => __( 'Like Us on Facebook', $rtwidgets->rtwidgets_text_domain ),      
                'link_label'    => __( 'Facebook Link', $rtwidgets->rtwidgets_text_domain ) 
            ),
            array(
                'id'            => 'twitter',
                'title'         => __( 'Follow Us on Twitter', $rtwidgets->rtwidgets_text_domain ),     
                'link_label'    => __( 'Twitter Link', $rtwidgets->rtwidgets_text_domain ) 
            ),
            array(
                'id'            => 'gplus',
                'title'         => __( 'Add to Circle', $rtwidgets->rtwidgets_text_domain ),            
                'link_label'    => __( 'Google + Link', $rtwidgets->rtwidgets_text_domain ) 
            ),
            array(
                'id'            => 'rss',
                'title'         => __( 'Subscribe via RSS', $rtwidgets->rtwidgets_text_domain ),
                'link_label'    => __( 'RSS Feed Link', $rtwidgets->rtwidgets_text_domain ) 
            ),
            array(
                'id'            => 'pinterest',
                'title'         => __( 'Follow Us on Pinterest', $rtwidgets->rtwidgets_text_domain ),
                'link_label'    => __( 'Pinterest Link', $rtwidgets->rtwidgets_text_domain ) 
            ),
            array(
                'id'            => 'instagram',
                'title'         => __( 'Follow Us on Instagram', $rtwidgets->rtwidgets_text_domain ),
                'link_label'    => __( 'Instagram Link', $rtwidgets->rtwidgets_text_domain ) 
            )
        );
        
        /**
         * Filter to Add new Social Item
         */
        $this->meta = apply_filters( 'rtw_add_social_items', $rtw_default_social_array );
        
        /**
         * Generate Sort Order to save in database
         */
        $rtw_sort_string = '';
        $count = count( $this->meta );
        foreach( $this->meta as $key => $val ) {
            $rtw_sort_string .= 'rt_' . $key;
            $rtw_sort_string .= ( $key !== $count-1 ) ? '-' : '';
        }
        $this->rtw_default_sort_order = $rtw_sort_string;
    }

    /**
     * Outputs the HTML
     *
     * @param array An array of standard parameters for widgets in this theme
     * @param array An array of settings for this widget instance
     * @return void Echoes it's output
     *
     * @since rtWidget 1.0
     **/
    public function widget( $args, $instance ) {
        global $rtwidgets;
        extract( $args, EXTR_SKIP );
        $instance['title']      = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $instance['label']      = empty( $instance['label'] ) ? '' : $instance['label'];
        $instance['button']     = empty( $instance['button'] ) ? __( 'Subscribe', $rtwidgets->rtwidgets_text_domain ) : $instance['button'];
        $instance['sub_link']   = empty( $instance['sub_link'] ) ? '' : $instance['sub_link'];
        $instance['rtw_link_target']        = isset( $instance['rtw_link_target'] ) ? $instance['rtw_link_target'] : true;
        $instance['rtw_show_subscription']  = isset( $instance['rtw_show_subscription'] ) ? $instance['rtw_show_subscription'] : true;
        $instance['rtw_subscription']       = isset( $instance['rtw_subscription'] ) ? $instance['rtw_subscription'] : true;
        $instance['rtw_social_sort_order']  = isset( $instance['rtw_social_sort_order'] ) ? $instance['rtw_social_sort_order'] : $this->rtw_default_sort_order;
        
        $no_options = 0;

        echo $args['before_widget'];
        if ( !empty($instance['title']) ) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        } ?>

        <div class="email-subscription-container"><!-- email-subscription-container begins -->
        <?php
            if ( $instance['rtw_show_subscription'] && !empty( $instance['sub_link'] ) ) {
                $no_options++;
                
                if ( $instance['rtw_subscription'] == 'rtw_show_mailchimp' ) { ?>
                    <form onsubmit="window.open( '<?php echo $instance['sub_link']; ?>', 'popupwindow', 'scrollbars=yes,width=700px,height=700px' ); return true" target="popupwindow" method="post" action="<?php echo $instance['sub_link']; ?>" class="clearfix">
                <?php } else { ?>
                    <form onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $instance['sub_link']; ?>', 'popupwindow', 'scrollbars=yes,width=700px,height=700px' ); return true" target="popupwindow" method="post" action="http://feedburner.google.com/fb/a/mailverify" class="clearfix">
                <?php } ?>
                
                    <p><?php 
                        if ( $instance['label'] ) { ?>
                            <label for="email"><?php echo $instance['label']; ?></label><?php
                        } ?>
                        <input id="email" type="email" required="required" name="<?php echo ( $instance['rtw_subscription'] == 'rtw_show_mailchimp' ) ? 'EMAIL' : 'email'; ?>" placeholder="<?php _e( 'Enter Email Address', $rtwidgets->rtwidgets_text_domain ); ?>" class="email" title="<?php _e( 'Enter Email Address', $rtwidgets->rtwidgets_text_domain ); ?>" size="15" />
                        <input type="hidden" aria-hidden="true" name="uri" value="<?php echo $instance['sub_link']; ?>" />
                        <input type="hidden" aria-hidden="true" value="en_US" name="loc" />
                        <input type="submit" value="<?php echo $instance['button']; ?>" title="<?php echo $instance['button']; ?>" class="btn button tiny" />
                    </p>
                </form><?php
            }

            $target = ( $instance['rtw_link_target'] ) ? ' target="_blank"' : '';
            $rtw_social_new_orders = explode( '-', $instance['rtw_social_sort_order'] ); ?>
            <ul role="list" class="social-icons clearfix"><?php
                foreach( $rtw_social_new_orders as $rtw_social_new_order ) {
                    $rtw_social_index = str_replace( 'rt_', '', $rtw_social_new_order );
                    $rtw_cbox_slug      = 'rtw_show_' . $this->meta[$rtw_social_index]['id'];
                    $rtw_social_title   = $this->meta[$rtw_social_index]['title'];
                    $rtw_input_slug     = $this->meta[$rtw_social_index]['id'] . '_link';
                    $rtw_social_class   = $this->meta[$rtw_social_index]['id'];
                    $rtw_var_chkbox     = isset( $instance[$rtw_cbox_slug] ) ? (bool) $instance[$rtw_cbox_slug] : false;
                    $rtw_var_input      = isset( $instance[$rtw_input_slug] ) ? esc_url( $instance[$rtw_input_slug] ) : '';

                    if ( $rtw_var_chkbox && $rtw_var_input ) {
                        $no_options++;
                    }
                    
                    /**
                     * Show Social Icon in Frontend
                     */
                    echo ( $rtw_var_chkbox && $rtw_var_input ) ? '<li role="listitem"><a role="link" rel="nofollow"' . $target . ' class="' . $rtw_social_class . ' rtw-radius" href="' . $rtw_var_input . '" title="' . $rtw_social_title . '"><i class="rtw-' . $rtw_social_class . '"></i></a></li>' : '';
                } ?>
            </ul><?php
            
            /**
             * Show Message in frontend if widget is not configured
             */
            if ( !$no_options ) { ?>
                <p><?php printf( __( 'Please configure this widget <a href="%s" target="_blank" title="Configure Subscribe Widget">here</a>.', $rtwidgets->rtwidgets_text_domain ), admin_url( '/widgets.php' ) ); ?></p><?php
            } ?>
        </div> <!-- end email-subscription-container --><?php
        echo $args['after_widget'];
    }

    /**
     * Deals with the settings when they are saved by the admin
     *
     * @since rtWidget 1.0
     **/
    public function update( $new_instance, $old_instance ) {
        global $rtwidgets;
        $instance = $old_instance;
        $instance['title'] = strip_tags ( $new_instance['title'] );
        $instance['label'] =  strip_tags ( $new_instance['label'] );
        $instance['button'] =  !empty( $new_instance['button'] ) ? strip_tags ( $new_instance['button'] ) : __( 'Subscribe', $rtwidgets->rtwidgets_text_domain );
        $instance['sub_link'] = !empty( $new_instance['sub_link'] ) ? $new_instance['sub_link'] : '';
        $instance['rtw_subscription'] = !empty( $new_instance['rtw_subscription'] ) ? $new_instance['rtw_subscription'] : '';
        $instance['rtw_show_subscription'] = !empty( $new_instance['rtw_show_subscription'] ) ? 1 : 0;
        $instance['rtw_link_target'] = !empty( $new_instance['rtw_link_target'] ) ? 1 : 0;
        
        /**
         * Save sort order and widget options to database
         */
        $instance['rtw_social_sort_order'] = isset( $old_instance['rtw_social_sort_order'] ) ? $old_instance['rtw_social_sort_order'] : $this->rtw_default_sort_order;
        $rtw_social_new_orders = explode( '-', $instance['rtw_social_sort_order'] );
        foreach( $rtw_social_new_orders as $rtw_social_new_order ) {
            $rtw_social_index = str_replace( 'rt_', '', $rtw_social_new_order );
            $rtw_show      = 'rtw_show_' . $this->meta[$rtw_social_index]['id'];
            $rtw_links     = $this->meta[$rtw_social_index]['id'] . '_link';            
            $instance[$rtw_show] = !empty( $new_instance[$rtw_show] ) ? 1 : 0;
            $instance[$rtw_links] = esc_url_raw( $new_instance[$rtw_links] );
        }
        
        $instance['rtw_social_sort_order'] = !empty( $new_instance['rtw_social_sort_order'] ) ?  $new_instance['rtw_social_sort_order'] : $this->rtw_default_sort_order;
        return $instance;
    }

    /**
     * Displays the form on the Widgets page of the WP Admin area
     *
     * @since rtWidget 1.0
     **/
    public function form( $instance ) {
        global $rtwidgets;
        $title = isset ( $instance['title'] ) ? esc_attr( ( $instance['title'] ) ) : '';
        $label = isset ( $instance['label'] ) ? esc_textarea( ( $instance['label'] ) ) : __( 'Sign up for our email newsletter', $rtwidgets->rtwidgets_text_domain );
        $button = isset ( $instance['button'] ) ? esc_attr( ( $instance['button'] ) ) : __( 'Subscribe', $rtwidgets->rtwidgets_text_domain );
        $rtw_link_target = isset( $instance['rtw_link_target'] ) ? (bool) $instance['rtw_link_target'] : TRUE;
        $rtw_show_subscription = isset( $instance['rtw_show_subscription'] ) ? (bool) $instance['rtw_show_subscription'] : TRUE;
        $rtw_subscription = isset( $instance['rtw_subscription'] ) ? esc_attr( ( $instance['rtw_subscription'] ) ) : 'rtw_show_feedburner';
        $rtw_social_sort_order = isset( $instance['rtw_social_sort_order'] ) ? $instance['rtw_social_sort_order'] : $this->rtw_default_sort_order;

        $sub_link = isset ( $instance['sub_link'] ) ? esc_attr( $instance['sub_link'] ) : ''; ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" role="textbox" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        
        <!-- Subscription code start here -->
        <p>
            <strong><?php _e( 'Subscription', $rtwidgets->rtwidgets_text_domain ); ?>: </strong>
        </p>
        <p>
            <input class="rtw-subscription-check" role="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'rtw_show_subscription' ); ?>" id="<?php echo $this->get_field_id( 'rtw_show_subscription' ); ?>" <?php checked( $rtw_show_subscription, '1' ); ?> />
            <label for="<?php echo $this->get_field_id( 'rtw_show_subscription' ); ?>"><?php _e( 'Show Subscription', $rtwidgets->rtwidgets_text_domain ); ?></label>
        </p>
        <div class="rtw-show-subscription">
            <p>
                <input type="radio" value="rtw_show_feedburner" name="<?php echo $this->get_field_name( 'rtw_subscription' ); ?>" id="<?php echo $this->get_field_id( 'rtw_show_feedburner' ); ?>" <?php checked( 'rtw_show_feedburner', $rtw_subscription ); ?> />
                <label for="<?php echo $this->get_field_id( 'rtw_show_feedburner' ); ?>"><?php _e( 'Feedburner Subscription: (Handler)', $rtwidgets->rtwidgets_text_domain ); ?> </label>
            </p>
            <p>
                <input type="radio" value="rtw_show_mailchimp" name="<?php echo $this->get_field_name( 'rtw_subscription' ); ?>" id="<?php echo $this->get_field_id( 'rtw_show_mailchimp' ); ?>" <?php checked( 'rtw_show_mailchimp', $rtw_subscription ); ?> />
                <label for="<?php echo $this->get_field_id( 'rtw_show_mailchimp' ); ?>"><?php _e( 'MailChimp: (Form Action URL)', $rtwidgets->rtwidgets_text_domain ); ?> </label>
            </p>
            <p>
                <input class="widefat" id="<?php echo $this->get_field_id( 'sub_link' ); ?>" name="<?php echo $this->get_field_name( 'sub_link' ); ?>" type="text" role="textbox" value="<?php echo esc_attr( $sub_link ); ?>" placeholder="<?php _e( 'Enter Feedburner Handler OR MailChimp Action URL', $rtwidgets->rtwidgets_text_domain ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'label' ); ?>"><?php _e( 'Subscription Form Label', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
                <textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id( 'label' ); ?>" name="<?php echo $this->get_field_name( 'label' ); ?>"><?php echo $label; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'button' ); ?>"><?php _e( 'Subscription Form Button', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'button' ); ?>" name="<?php echo $this->get_field_name( 'button' ); ?>" type="text" role="textbox" value="<?php echo esc_attr( $button ); ?>" />
            </p>
        </div>
        <!-- Subscription code end here -->
        
        <p>
            <strong><?php _e( 'Social Connect', $rtwidgets->rtwidgets_text_domain ); ?>:</strong>
        </p>
        <p>
            <em><?php _e( 'Drag each item into the order you prefer', $rtwidgets->rtwidgets_text_domain ); ?></em>
        </p>
            <span class="info"></span><?php
                
        $rtw_social_new_orders = explode( '-', $rtw_social_sort_order ); ?>
        <ul class="rtw_social_container ui-sortable"><?php
            foreach( $rtw_social_new_orders as $rtw_social_new_order ) {
                $rtw_social_index = str_replace( 'rt_', '', $rtw_social_new_order ); ?>
                <li id="rt_<?php echo $rtw_social_index; ?>" class="ui-state-default rtw_social_item"><?php
                    $rtw_cbox_slug      = 'rtw_show_' . $this->meta[$rtw_social_index]['id'];
                    $rtw_social_label   = $this->meta[$rtw_social_index]['link_label'];
                    $rtw_input_slug     = $this->meta[$rtw_social_index]['id'] . '_link';
                    $rtw_var_chkbox     = isset( $instance[$rtw_cbox_slug] ) ? (bool) $instance[$rtw_cbox_slug] : false;
                    $rtw_var_input      = isset( $instance[$rtw_input_slug] ) ? esc_url( $instance[$rtw_input_slug] ) : ''; ?>
                    <input role="checkbox" type="checkbox" name="<?php echo $this->get_field_name( $rtw_cbox_slug ); ?>" id="<?php echo $this->get_field_id( $rtw_cbox_slug ); ?>" <?php checked( $rtw_var_chkbox ); ?> />
                    <label for="<?php echo $this->get_field_id( $rtw_cbox_slug ); ?>"><?php echo $rtw_social_label; ?></label>
                    <input style="display: none;" class="widefat rtw-social-input" id="<?php echo $this->get_field_id( $rtw_input_slug ); ?>" name="<?php echo $this->get_field_name( $rtw_input_slug ); ?>" type="text" role="textbox" value="<?php echo esc_attr( $rtw_var_input ); ?>" />
                </li><?php
            } ?>
        </ul>
        <input class="rtw_social_sort_order" id="<?php echo $this->get_field_id( 'rtw_social_sort_order' ); ?>" name="<?php echo $this->get_field_name( 'rtw_social_sort_order' ); ?>" role="hidden" type="hidden" value="<?php echo $rtw_social_sort_order; ?>" />
        <p>
            <input class="link_target" id="<?php echo $this->get_field_id( 'rtw_link_target' ); ?>" name="<?php echo $this->get_field_name( 'rtw_link_target' ); ?>" role="checkbox" type="checkbox" <?php checked( $rtw_link_target ); ?> />
            <label for="<?php echo $this->get_field_id( 'rtw_link_target' ); ?>"><?php _e( 'Open Social Links in New Tab/Window', $rtwidgets->rtwidgets_text_domain ); ?></label>
        </p>
        <script type="text/javascript">
            jQuery( function() {
                /**
                 * Social Input Field Sortable
                 */
                jQuery( '.rtw_social_container' ).sortable( {
                    axis: "y",
                    stop: function ( event, ui ) {
                        var data = jQuery(this).sortable( 'toArray' );
                        $new = data.join( '-' );
                        jQuery( '.rtw_social_sort_order' ).val( $new );
                    }
                } );

                /**
                 * Show/Hide Input Field on Checkbox
                 */
                jQuery( ".rtw_social_container li input[type=checkbox]" ).each( function() {
                    jQuery(this).on( 'change', function() {
                        if ( jQuery(this).is( ':checked' ) ) {
                            jQuery(this).siblings( ".rtw-social-input" ).show();
                        } else {
                            jQuery(this).siblings( ".rtw-social-input" ).hide();
                        }
                    } );

                    if ( jQuery(this).is( ':checked' ) ) {
                        jQuery(this).siblings( ".rtw-social-input" ).show();
                    } else {
                        jQuery(this).siblings( ".rtw-social-input" ).hide();
                    }
                } );
                jQuery( '.rtw-subscription-check' ).on( 'change', function (){
                    if ( jQuery(this).is( ':checked' ) ) {
                        jQuery( '.rtw-show-subscription' ).show();
                    } else {
                        jQuery( '.rtw-show-subscription' ).hide();
                    }
                } );
            } );
        </script><?php
    }
}