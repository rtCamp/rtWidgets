<?php

/**
 * Multiple Navigation Menus widget class
 *
 * @since rtWidget 1.0
 */
 class rtw_multiple_nav_menu_widget extends WP_Widget {

    public function __construct() {
        global $rtwidgets;
        $widget_ops = array( 
            'classname'     => 'rtw-multiple-nav-widget', 
            'description'   => __( 'Use this widget to add multiple menus in a single widget.', $rtwidgets->rtwidgets_text_domain ) 
        );
        parent::__construct( 'rtw_multiple_nav_widget', __( 'rtw: Multiple Menus', $rtwidgets->rtwidgets_text_domain ), $widget_ops );
    }

    public function widget($args, $instance) {
        extract( $args, EXTR_SKIP );
        $nav_menu           = !empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
        $secondary_nav_menu = !empty( $instance['secondary_nav_menu'] ) ? wp_get_nav_menu_object( $instance['secondary_nav_menu'] ) : false;
        
        if ( !$nav_menu ) {
            return;
        }
        
        $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $instance['secondary_title'] = empty ( $instance['secondary_title'] ) ? '' : $instance['secondary_title'];
        
        echo $args['before_widget'];

            /* Primary Menu */
            if ( $nav_menu ) {
                echo '<div class="rtw-first-nav">';
                if ( !empty( $instance['title'] ) ) {
                    echo $args['before_title'] . $instance['title'] . $args['after_title'];
                }
                wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );
                echo '</div>';
            }

            /* Secondary Menu */
            if ( $secondary_nav_menu ) {
                echo '<div class="rtw-second-nav">';
                if ( !empty( $instance['secondary_title'] ) ) {
                    echo $args['before_title'] . $instance['secondary_title'] . $args['after_title'];
                }
                wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $secondary_nav_menu ) );
                echo '</div>';
            }
        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
        $instance['secondary_title'] = ( ! empty( $new_instance['secondary_title'] ) ) ? strip_tags( $new_instance['secondary_title'] ) : '';
        $instance['nav_menu'] = (int) $new_instance['nav_menu'];
        $instance['secondary_nav_menu'] = (int) $new_instance['secondary_nav_menu'];
        return $instance;
    }

    public function form( $instance ) {
        global $rtwidgets;
        $title = isset( $instance['title'] ) ? $instance['title'] : '';
        $secondary_title = isset( $instance['secondary_title'] ) ? $instance['secondary_title'] : '';
        $nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
        $secondary_nav_menu = isset( $instance['secondary_nav_menu'] ) ? $instance['secondary_nav_menu'] : '';

        // Get menus
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

        // If no menus exists, direct the user to go and create some.
        if ( !$menus ) {
            echo '<p>'. sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.', $rtwidgets->rtwidgets_text_domain ), admin_url( 'nav-menus.php' ) ) .'</p>';
            return;
        } ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'First Menu Title:', $rtwidgets->rtwidgets_text_domain ) ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:', $rtwidgets->rtwidgets_text_domain ); ?></label>
            <select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>"><?php
                foreach ( $menus as $menu ) {
                    echo '<option value="' . $menu->term_id . '"' . selected( $nav_menu, $menu->term_id, false ) . '>'. $menu->name . '</option>';
                } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'secondary_title' ); ?>"><?php _e( 'Second Menu Title:', $rtwidgets->rtwidgets_text_domain ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'secondary_title' ); ?>" name="<?php echo $this->get_field_name( 'secondary_title' ); ?>" type="text" value="<?php echo esc_attr( $secondary_title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'secondary_nav_menu' ); ?>"><?php _e( 'Select Menu:', $rtwidgets->rtwidgets_text_domain ); ?></label>
            <select id="<?php echo $this->get_field_id( 'secondary_nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'secondary_nav_menu' ); ?>"><?php
                foreach ( $menus as $menu ) {
                    echo '<option value="' . $menu->term_id . '"' . selected( $secondary_nav_menu, $menu->term_id, false ) . '>'. $menu->name . '</option>';
                } ?>
            </select>
        </p><?php
    }
}