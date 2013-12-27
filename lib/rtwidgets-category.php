<?php

/**
 * Custom Widget for Categories
 *
 * @since rtWidget 1.0
 */
class rtw_category_widget extends WP_Widget {
    
    public function __construct() {
        global $rtwidgets;
        $widget_ops = array(
            'classname'     => 'rtw-category-widget', 
            'description'   => __( 'A list or dropdown of categories with extra options.', $rtwidgets->rtwidgets_text_domain )
        );
        parent::__construct( 'rtw_category_widget', __( 'rtw: Categories' ), $widget_ops );
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
        $instance['title']        = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Categories', $rtwidgets->rtwidgets_text_domain ) : $instance['title'], $instance, $this->id_base );
        $instance['sortby']       = empty( $instance['sortby'] ) ? 'name' : $instance['sortby'];
        $instance['showstyle']    = empty( $instance['showstyle'] ) ? 'list' : $instance['showstyle'];
        $instance['order']        = empty( $instance['order'] ) ? 'ASC' : $instance['order'];
        $instance['show_cat']     = empty( $instance['show_cat'] ) ? 10 : $instance['show_cat'];
        $instance['exclude']      = empty( $instance['exclude'] ) ? '' : $instance['exclude'];
        $instance['hierarchical'] = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : true;
        $instance['show_count']   = isset( $instance['show_count'] ) ? $instance['show_count'] : true;
        $instance['hide_empty']   = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : true;
        $exclude = preg_replace( '/\,$/', '', $instance['exclude'] );

        echo $args['before_widget'];
            if ( !empty($instance['title']) ) {
                echo $args['before_title'] . $instance['title'] . $args['after_title'];
            }

                if ( $instance['showstyle'] == 'list' ) {
                    echo '<ul role="list">';
                        wp_list_categories( array( 'hierarchical' => $instance['hierarchical'], 'style' => $instance['showstyle'], 'hide_empty' => $instance['hide_empty'], 'show_count' => $instance['show_count'], 'number' => $instance['show_cat'], 'exclude' => $exclude, 'orderby' => $instance['sortby'], 'title_li' => '', 'order' => $instance['order'] ) );
                    echo '</ul>';
                } else {
                        wp_dropdown_categories( array( 'id' => 'rtw_cat', 'name' => 'rtw_cat', 'hierarchical' => $instance['hierarchical'], 'orderby' => $instance['sortby'], 'order' => $instance['order'], 'show_count' => $instance['show_count'], 'hide_empty' => $instance['hide_empty'], 'exclude' => $exclude, 'class' => 'postform rtw_cat_dropdown' ) ); ?>
                        <script type="text/javascript">
                        /* <![CDATA[ */
                                var dropdown = document.getElementById("rtw_cat");
                                function onCatChange() {
                                        if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
                                                location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
                                        }
                                }
                                dropdown.onchange = onCatChange;
                        /* ]]> */
                        </script>
                <?php }

         echo $args['after_widget'];
    }

    /**
     * Deals with the settings when they are saved by the admin
     *
     * @since rtWidget 1.0
     **/
    public function update( $new_instance, $old_instance ) {
        $total_category = count( get_categories() );
        $instance = $old_instance;
        $instance['title'] = strip_tags ( $new_instance['title'] );
        if ( in_array( $new_instance['showstyle'], array( 'list', 'dropdown' ) ) ) {
            $instance['showstyle'] = $new_instance['showstyle'];
        } else {
            $instance['showstyle'] = 'list';
        }
        $instance['hide_empty'] = !empty( $new_instance['hide_empty'] ) ? 1 : 0;
        if ( in_array( $new_instance['sortby'], array( 'name', 'ID', 'count', 'slug' ) ) ) {
            $instance['sortby'] = $new_instance['sortby'];
        } else {
            $instance['sortby'] = 'name';
        }
        if ( in_array( $new_instance['order'], array( 'ASC', 'DESC' ) ) ) {
            $instance['order'] = $new_instance['order'];
        } else {
            $instance['order'] = 'ASC';
        }
        $instance['show_cat'] = (int) $new_instance['show_cat'] > $total_category ? $total_category : (int) $new_instance['show_cat'];
        $instance['exclude'] = strip_tags ( $new_instance['exclude'] );
        $instance['hierarchical'] = !empty( $new_instance['hierarchical'] ) ? 1 : 0;
        $instance['show_count'] = !empty( $new_instance['show_count'] ) ? 1 : 0;
        return $instance;
    }

    /**
     * Displays the form on the Widgets page of the WP Admin area
     *
     * @since rtWidget 1.0
     **/
    public function form($instance) {
        global $rtwidgets;
        $title = isset ( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $showstyle = isset ( $instance['showstyle'] ) ? ( $instance['showstyle'] ) : 'list';
        $hide_empty = isset( $instance['hide_empty']) ? (bool) $instance['hide_empty'] :false;
        $sortby = empty( $instance['sortby'] ) ? 'name' : $instance['sortby'];
        $order = empty( $instance['order'] ) ? 'ASC' : $instance['order'];
        $show_cat = empty( $instance['show_cat'] ) ? 10 : absint( $instance['show_cat'] );
        $exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];
        $hierarchical = isset( $instance['hierarchical']) ? (bool) $instance['hierarchical'] :false;
        $show_count = isset( $instance['show_count']) ? (bool) $instance['show_count'] :false; ?>
        <p style="overflow: hidden;">
            <label for="<?php echo $this->get_field_id( 'title' ); ?>" style="display: block; float: left; padding: 0 0 3px;"><?php _e( 'Title', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <input class="widefat" role="textbox" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p style="overflow: hidden;">
            <label for="<?php echo $this->get_field_id( 'sortby' ); ?>" style="display: block;float: left;padding: 3px 0 0;"><?php _e( 'Order by', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <select role="listbox" id="<?php echo $this->get_field_id( 'sortby' ); ?>" name="<?php echo $this->get_field_name( 'sortby' ); ?>" style="float: right; width: 150px;">
                <option value="name" <?php selected( 'name', $sortby ); ?>><?php _e( 'Category Name', $rtwidgets->rtwidgets_text_domain ); ?></option>
                <option value="ID" <?php selected( 'ID', $sortby ); ?>><?php _e( 'Category ID', $rtwidgets->rtwidgets_text_domain ); ?></option>
                <option value="count" <?php selected( 'count', $sortby ); ?>><?php _e( 'Category Count', $rtwidgets->rtwidgets_text_domain ); ?></option>
                <option value="slug" <?php selected( 'slug', $sortby ); ?>><?php _e( 'Category Slug', $rtwidgets->rtwidgets_text_domain ); ?></option>
            </select>
        </p>
        <p style="overflow: hidden;">
            <label for="<?php echo $this->get_field_id( 'order' ); ?>" style="display: block;float: left;padding: 3px 0 0;"><?php _e( 'Sort by', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <select role="listbox" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" style="float: right; width: 150px;">
                <option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', $rtwidgets->rtwidgets_text_domain ); ?></option>
                <option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', $rtwidgets->rtwidgets_text_domain ); ?></option>
            </select>
        </p>
        <p style="overflow: hidden;">
            <label for="<?php echo $this->get_field_id( 'show_cat' ); ?>" style="display: block;float: left;padding: 3px 0 0;"><?php _e( 'Show Category', $rtwidgets->rtwidgets_text_domain ); ?>:</label>
            <input class="widefat show-cat" id="<?php echo $this->get_field_id( 'show_cat' ); ?>" name="<?php echo $this->get_field_name( 'show_cat' ); ?>" type="text" role="textbox" value="<?php echo $show_cat; ?>" style="float: right; clear: right; width: 150px;" /><div class="clear"></div>
            <span class="description"><?php _e( 'Total Categories', $rtwidgets->rtwidgets_text_domain ); ?>: <?php echo count( get_categories() ); ?></span>
        </p>
        <p style="overflow: hidden;">
            <label for="<?php echo $this->get_field_id( 'showstyle' ); ?>" style="display: block;float: left;padding: 3px 0 0;"><?php _e( 'Style', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
            <select role="listbox" id="<?php echo $this->get_field_id( 'showstyle' ); ?>" name="<?php echo $this->get_field_name( 'showstyle' ); ?>" style="float: right; width: 150px;">
                <option value="list" <?php selected( 'list', $showstyle ); ?>><?php _e( 'List', $rtwidgets->rtwidgets_text_domain ); ?></option>
                <option value="dropdown" <?php selected( 'dropdown', $showstyle ); ?>><?php _e( 'Dropdown', $rtwidgets->rtwidgets_text_domain ); ?></option>
            </select>
        </p>
        <p style="overflow: hidden;">
            <label for="<?php echo $this->get_field_id( 'exclude' ); ?>" style="display: block; float: left; padding: 3px 0 0;"><?php _e( 'Exclude', $rtwidgets->rtwidgets_text_domain ); ?>:</label>
            <input class="widefat exclude" id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" type="text" role="textbox" value="<?php echo $exclude; ?>" style="float: right; clear: right; margin: 0 0 0 3px; width: 150px;" /><div class="clear"></div>
            <span class="description"><?php _e( 'Separate Category ID with ","', $rtwidgets->rtwidgets_text_domain ); ?><br /><?php _e( 'Ex: 1,5,15', $rtwidgets->rtwidgets_text_domain ); ?></span>
        </p>
        <p style="overflow: hidden;">
            <input role="checkbox" type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
            <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show Hierarchy', $rtwidgets->rtwidgets_text_domain ); ?></label><br />
        </p>
        <p style="overflow: hidden;">
            <input role="checkbox" type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>"<?php checked( $hide_empty ); ?> />
            <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e( 'Hide Empty', $rtwidgets->rtwidgets_text_domain ); ?></label><br />
        </p>
        <p style="overflow: hidden;">
            <input role="checkbox" type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>"<?php checked( $show_count ); ?> />
            <label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e( 'Show post counts', $rtwidgets->rtwidgets_text_domain ); ?></label><br />
        </p>
        <script type="text/javascript">
            jQuery( function() {
                jQuery('.show-cat').keyup( function () { this.value = this.value.replace(/[^0-9\/]/g,''); } );
                jQuery('.exclude').keyup( function () { this.value = this.value.replace(/[^0-9\,]/g,''); } );
            });
        </script><?php
    }
}