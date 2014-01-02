<?php

/**
 * TOC Widget Layout
 *
 * @since rtWidget 1.3
 */
class rtw_toc_widget extends WP_Widget {
    
    public function __construct() {
        global $rtwidgets;
        $widget_ops = array(
            'classname'     => 'rtw-toc-widget', 
            'description'   => __( 'This widget automatically creates a table of contents with various options.', $rtwidgets->rtwidgets_text_domain )
        );
        parent::__construct( 'rtw_toc_widget', __( 'rtw: TOC Widget' ), $widget_ops );
    }

    /**
     * Outputs the HTML
     *
     * @param array An array of standard parameters for widgets in this theme
     * @param array An array of settings for this widget instance
     * @return void Echoes it's output
     *
     * @since rtWidget 1.3
     **/
    public function widget( $args, $instance ) {
        global $rtwidgets;
        extract( $args, EXTR_SKIP );
        $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Categories', $rtwidgets->rtwidgets_text_domain ) : $instance['title'], $instance, $this->id_base );


        echo $args['before_widget'];
            if ( !empty($instance['title']) ) {
                echo $args['before_title'] . $instance['title'] . $args['after_title'];
            } ?>
            <!-- Start of .rtw-toc-wrapper -->
            <div class="rtw-toc-wrapper"></div>
            <!-- End of .rtw-toc-wrapper -->

<?php
         echo $args['after_widget'];
    }

    /**
     * Deals with the settings when they are saved by the admin
     *
     * @since rtWidget 1.3
     **/
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags ( $new_instance['title'] );
        
        return $instance;
    }

    /**
     * Displays the form on the Widgets page of the WP Admin area
     *
     * @since rtWidget 1.3
     **/
    public function form( $instance ) {
        global $rtwidgets;
        $title = isset ( $instance['title'] ) ? esc_attr( $instance['title'] ) : ''; ?>
        <p style="overflow: hidden;">
            <label for="<?php echo $this->get_field_id( 'title' ); ?>" style="display: block; float: left; padding: 0 0 3px;"><?php _e( 'Title', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <input class="widefat" role="textbox" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        
        <?php
    }
}