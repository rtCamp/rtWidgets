<?php

/**
 * Custom Widget for Recent Comments
 *
 * @since rtWidget 1.0
 */
class rtw_comments_widget extends WP_Widget {

    public function __construct() {
        global $rtwidgets;
        $widget_ops = array( 
            'classname'     => 'rtw-comments-widget', 
            'description'   => __( 'Shows Recent Comments with an option for displaying Author Gravatar also.', $rtwidgets->rtwidgets_text_domain ) 
        );
        parent::__construct( 'rtw_comments_widget', __( 'rtw: Comments with Gravatar', $rtwidgets->rtwidgets_text_domain ), $widget_ops );
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
        $instance['title']          = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Recent Comments', $rtwidgets->rtwidgets_text_domain ) : $instance['title'], $instance, $this->id_base );
        $instance['show_grav']      = empty( $instance['show_grav'] ) ? 0 : 1;
        $instance['count']          = empty( $instance['count'] ) ? 0 : $instance['count'];
        $instance['rtw_read_more']  = empty( $instance['rtw_read_more'] ) ? __( 'Read More &rarr;', $rtwidgets->rtwidgets_text_domain ) : $instance['rtw_read_more'];
        
        echo $args['before_widget'];
            if ( !empty($instance['title']) ) {
                echo $args['before_title'] . $instance['title'] . $args['after_title'];
            }
            global $wpdb;
            $total_comments = get_comments( array( 'type' => 'comment' ) );

            if ( !empty( $total_comments ) ) {
                echo '<ul role="list">';

                for ( $comments = 0; $comments < $instance['count']; $comments++ ) {
                    echo '<li role="listitem" class="clearfix">';
                        if ( !empty( $instance['show_grav'] ) ) {
                            echo '<figure class="author-vcard" title="' . $total_comments[$comments]->comment_author . '">';
                                echo get_avatar( $total_comments[$comments]->comment_author_email, 48, '', $total_comments[$comments]->comment_author );
                            echo '</figure>';
                        }
                        echo '<p class="rtw-comment-date ' . ( !empty( $instance['show_grav'] ) ? '' : 'rtw-margin-0' ) . '">';
                            echo '<a title="' . mysql2date( 'F j, Y - g:ia', $total_comments[$comments]->comment_date_gmt ) . '" href="' . get_permalink( $total_comments[$comments]->comment_post_ID ) . '#comment-' . $total_comments[$comments]->comment_ID . '">';
                            echo mysql2date( 'F j, Y - g:ia', $total_comments[$comments]->comment_date_gmt );
                            echo '</a>';
                        echo '</p>';
                        echo '<div class="author-comment">';
                            $str = wp_html_excerpt ( $total_comments[$comments]->comment_content, 70 );
                            if ( strlen( $str ) >= 70 ) {
                                echo $str.'&hellip;';
                            } else {
                                echo $str;
                            }
                        echo '</div>';
                        echo '<p class="rtw-reply rtw-common-link">';
                            echo '<a title="' . $instance['rtw_read_more'] . '" href="' . get_permalink( $total_comments[$comments]->comment_post_ID ) . '#comment-' . $total_comments[$comments]->comment_ID . '">' . $instance['rtw_read_more'] . '</a>';
                        echo '</p>';
                    echo '</li>';
                }
                echo '</ul>';
            }
         echo $args['after_widget'];
    }

    /**
     * Deals with the settings when they are saved by the admin
     *
     * @since rtWidget 1.0
     **/
    public function update( $new_instance, $old_instance ) {
        global $wpdb;
        $comment_query = "SELECT count(*) FROM $wpdb->comments WHERE comment_approved = 1 AND trim(comment_type) = ''";
        $comment_total = $wpdb->get_var($comment_query);
        $instance = $old_instance;
        $instance['title'] = strip_tags ( $new_instance['title'] );
        $instance['show_grav'] = !empty( $new_instance['show_grav'] ) ? 1 : 0;
        $instance['count'] = strip_tags ( $new_instance['count'] ) > $comment_total ? $comment_total : strip_tags ( $new_instance['count'] );
        $instance['rtw_read_more'] = strip_tags ( $new_instance['rtw_read_more'] );
        return $instance;
    }

    /**
     * Displays the form on the Widgets page of the WP Admin area
     *
     * @since rtWidget 1.0
     **/
    public function form( $instance ) {
        global $wpdb, $rtwidgets;
        $title = isset ( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $show_grav = isset( $instance['show_grav'] ) ? (bool) $instance['show_grav'] : TRUE;
        $comment_query = "SELECT count(*) FROM $wpdb->comments WHERE comment_approved = 1 AND trim(comment_type) = ''";
        $comment_total = $wpdb->get_var($comment_query);
        $def_count = ( $comment_total > 5 ) ? 5 : $comment_total;
        $count = empty( $instance['count'] ) ? $def_count : $instance['count'];
        $rtw_read_more = empty( $instance['rtw_read_more'] ) ? __( 'Read More &rarr;', $rtwidgets->rtwidgets_text_domain ) : $instance['rtw_read_more'];
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" role="textbox" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_grav' ); ?>"><?php _e( 'Show Gravatar', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <input class="show_grav" id="<?php echo $this->get_field_id( 'show_grav' ); ?>" name="<?php echo $this->get_field_name( 'show_grav' ); ?>" role="checkbox" type="checkbox" <?php checked( $show_grav ); ?> />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Show Comments', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <input class="widefat show-comments" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" role="textbox" value="<?php echo $count; ?>" />
            <span class="description"><?php printf( __( 'You have total \'%d\' comments to display', $rtwidgets->rtwidgets_text_domain ) , $comment_total ); ?></span>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'rtw_read_more' ); ?>"><?php _e( 'Read More Text', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'rtw_read_more' ); ?>" name="<?php echo $this->get_field_name( 'rtw_read_more' ); ?>" type="text" role="textbox" value="<?php echo $rtw_read_more; ?>" />
        </p>
        <script type="text/javascript">
            jQuery( function() {
                jQuery('.show-comments').keyup(function(){ this.value = this.value.replace(/[^0-9\/]/g,''); });
            });
        </script><?php
    }
}