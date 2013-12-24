<?php

/**
 * Custom Front-end Login Widget
 *
 * @since rtWidget 1.0
 */
class rtw_frontend_login extends WP_Widget {

    public function __construct() {
        global $rtwidgets;
        $widget_ops = array(
            'classname' => 'rtw-login-widget',
            'description' => __('Provide fontend login, registration and forget password option', $rtwidgets->rtwidgets_text_domain)
        );
        parent::__construct('rtw_login_widget', __('rtw: Frontend Login'), $widget_ops);
    }

    /**
     * Outputs the HTML
     *
     * @param array An array of standard parameters for widgets in this theme
     * @param array An array of settings for this widget instance
     * @return void Echoes it's output
     *
     * @since rtWidget 1.0
     * */
    public function widget($args, $instance) {
        global $rtwidgets;
        extract($args, EXTR_SKIP);
        $instance['title'] = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        echo $args['before_widget'];
        if ( !empty( $instance['title'] ) ) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        } ?>

        <div id="rtw-login-container"><?php
            global $user_ID, $user_identity;
            get_currentuserinfo();
            if ( !$user_ID ) { ?>
                <ul class="tabs clearfix" data-tab>
                    <li class="active"><a href="#tab1_login" title="<?php _e( 'Login', $rtwidgets->rtwidgets_text_domain ); ?>"><?php _e( 'Login', $rtwidgets->rtwidgets_text_domain ); ?></a></li>
                    <li><a href="#tab2_login" title="<?php _e( 'Register', $rtwidgets->rtwidgets_text_domain ); ?>"><?php _e( 'Register', $rtwidgets->rtwidgets_text_domain ); ?></a></li>
                    <li><a href="#tab3_login" title="<?php _e( 'Forgot?', $rtwidgets->rtwidgets_text_domain ); ?>"><?php _e( 'Forgot?', $rtwidgets->rtwidgets_text_domain ); ?></a></li>
                </ul>
                <div class="tab_content_login content active" id="tab1_login">
                    <?php
                    $register = isset($_GET['register']) ? $_GET['register'] : '';
                    $reset = isset($_GET['reset']) ? $_GET['reset'] : '';
                    if ( $register == true ) { ?>
                        <h4><?php _e( 'Success!', $rtwidgets->rtwidgets_text_domain ); ?></h4>
                        <p><?php _e( 'Check your email for the password and then return to log in.', $rtwidgets->rtwidgets_text_domain ); ?></p><?php 
                    } elseif ($reset == true) { ?>
                        <h4><?php _e( 'Success!', $rtwidgets->rtwidgets_text_domain ); ?></h4>
                        <p><?php _e( 'Check your email to reset your password.', $rtwidgets->rtwidgets_text_domain ); ?></p><?php 
                    } else { ?>
                        <h4><?php _e( 'Have an account?', $rtwidgets->rtwidgets_text_domain ); ?></h4>
                        <p><?php _e( 'Log in or sign up! It&rsquo;s fast &amp; <em>free!</em>', $rtwidgets->rtwidgets_text_domain ); ?></p><?php 
                    } ?>
                    <form method="post" action="<?php bloginfo( 'url' ) ?>/wp-login.php" class="wp-user-form">
                        <div class="username rtw-margin-bottom-10">
                            <label for="user_login"><?php _e( 'Username', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
                                <?php $user_login = ( isset($user_login) && !empty( $user_login ) ) ? $user_login : ''; ?>
                            <input placeholder="<?php _e( 'Enter Username', $rtwidgets->rtwidgets_text_domain ); ?>" type="text" name="log" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="25" id="user_login" />
                        </div>
                        <div class="password rtw-margin-bottom-10">
                            <label for="user_pass"><?php _e( 'Password', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
                            <input placeholder="<?php _e( 'Enter Password', $rtwidgets->rtwidgets_text_domain ); ?>" type="password" name="pwd" value="" size="25" id="user_pass" />
                        </div>
                        <div class="login_fields rtw-margin-bottom-10">
                            <div class="rememberme rtw-margin-bottom-10">
                                <label for="rememberme">
                                    <input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme" /> <?php _e( 'Remember me', $rtwidgets->rtwidgets_text_domain ); ?>
                                </label>
                            </div><?php do_action( 'login_form' ); ?>
                            <input type="submit" name="user-submit" value="<?php _e( 'Login', $rtwidgets->rtwidgets_text_domain ); ?>" class="user-submit" />
                            <input type="hidden" name="redirect_to" value="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : ''; ?>" />
                            <input type="hidden" name="user-cookie" value="1" />
                        </div>
                    </form>
                </div>
                <div class="tab_content_register content" id="tab2_login">
                    <h4><?php _e( 'Register for this site!', $rtwidgets->rtwidgets_text_domain ); ?></h4>
                    <p><?php _e( 'Sign up now for the good stuff.', $rtwidgets->rtwidgets_text_domain ); ?></p>
                    <form method="post" action="<?php echo site_url( 'wp-login.php?action=register', 'login_post' ) ?>" class="wp-user-form">
                        <div class="username rtw-margin-bottom-10">
                            <label for="user_login"><?php _e( 'Username', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
                            <input placeholder="<?php _e( 'Enter Username', $rtwidgets->rtwidgets_text_domain ); ?>" type="text" name="user_login" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="25" id="user_login" />
                        </div>
                        <div class="password rtw-margin-bottom-10">
                            <label for="user_email"><?php _e( 'Your Email', $rtwidgets->rtwidgets_text_domain ); ?>: </label><?php 
                            $user_email = ( isset($user_email) && !empty($user_email) ) ? $user_email : ''; ?>
                            <input placeholder="<?php _e( 'Enter Email Address', $rtwidgets->rtwidgets_text_domain ); ?>" type="text" name="user_email" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25" id="user_email" />
                        </div>
                        <div class="login_fields rtw-margin-bottom-10">
                            <?php do_action( 'register_form' ); ?>
                            <input type="submit" name="user-submit" value="<?php _e( 'Sign up!', $rtwidgets->rtwidgets_text_domain ); ?>" class="user-submit" />
                            <?php
                            $register = isset($_GET['register']) ? $_GET['register'] : '';
                            if ($register == true) {
                                echo '<p>' . __( 'Check your email for the password!', $rtwidgets->rtwidgets_text_domain ) . '</p>';
                            } ?>
                            <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?register=true" />
                            <input type="hidden" name="user-cookie" value="1" />
                        </div>
                    </form>
                </div>
                <div class="tab_content_forget content" id="tab3_login">
                    <h4><?php _e( 'Lose something?', $rtwidgets->rtwidgets_text_domain ); ?></h4>
                    <p><?php _e( 'Enter your username or email to reset your password.', $rtwidgets->rtwidgets_text_domain ); ?></p>
                    <form method="post" action="<?php echo site_url( 'wp-login.php?action=lostpassword', 'login_post' ) ?>" class="wp-user-form">
                        <div class="username rtw-margin-bottom-10">
                            <label for="user_login" class="hide"><?php _e( 'Username or Email', $rtwidgets->rtwidgets_text_domain ); ?>: </label>
                            <input placeholder="<?php _e( 'Enter Username or Email ID', $rtwidgets->rtwidgets_text_domain ); ?>" type="text" name="user_login" value="" size="25" id="user_login" />
                        </div>
                        <div class="login_fields rtw-margin-bottom-10">
                            <?php do_action( 'login_form', 'resetpass' ); ?>
                            <input type="submit" name="user-submit" value="<?php _e( 'Reset my password', $rtwidgets->rtwidgets_text_domain ); ?>" class="user-submit" /><?php
                            $reset = isset($_GET['reset']) ? $_GET['reset'] : '';
                            if ($reset == true) {
                                echo '<p>' . __( 'A message will be sent to your email address.', $rtwidgets->rtwidgets_text_domain ) . '</p>';
                            } ?>
                            <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true" />
                            <input type="hidden" name="user-cookie" value="1" />
                        </div>
                    </form>
                </div>
        <?php } else { // is logged in 
            ?>
                <div class="sidebox">
                    <h4><?php _e( 'Welcome,', $rtwidgets->rtwidgets_text_domain ); ?> <?php echo $user_identity; ?></h4>
                    <div class="usericon"><?php
                        global $userdata;
                        get_currentuserinfo();
                        echo get_avatar($userdata->ID, 64); ?>
                    </div>
                    <div class="userinfo">
                        <p><?php _e( 'You&rsquo;re logged in as', $rtwidgets->rtwidgets_text_domain ); ?> <strong><?php echo $user_identity; ?></strong></p>
                        <p>
                            <a href="<?php echo wp_logout_url('index.php'); ?>"><?php _e( 'Log out', $rtwidgets->rtwidgets_text_domain ); ?></a> | <?php
                            if ( current_user_can( 'manage_options' ) ) {
                                echo '<a href="' . admin_url() . '">' . __( 'Admin', $rtwidgets->rtwidgets_text_domain ) . '</a>';
                            } else {
                                echo '<a href="' . admin_url() . 'profile.php">' . __( 'Profile', $rtwidgets->rtwidgets_text_domain ) . '</a>';
                            } ?>
                        </p>
                    </div>
                </div>
        <?php } ?>
        </div> <!-- End of rtw-login-container -->

        <?php
        echo $args['after_widget'];
    }

    /**
     * Deals with the settings when they are saved by the admin
     *
     * @since rtWidget 1.0
     * */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    /**
     * Displays the form on the Widgets page of the WP Admin area
     *
     * @since rtWidget 1.0
     * */
    public function form($instance) {
        global $rtwidgets;
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        ?>
        <p style="overflow: hidden;">
            <label for="<?php echo $this->get_field_id('title'); ?>" style="display: block; float: left; padding: 0 0 3px;"><?php _e('Title', $rtwidgets->rtwidgets_text_domain); ?>: </label>
            <input class="widefat" role="textbox" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

}
