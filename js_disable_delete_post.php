<?php /**
 *
 * Plugin Name:   	Disable Delete Post or Page
 * Plugin URI:    	https://www.reactivedevelopment.net/disable-delete-post-page
 * Description:   	A WordPress Plugin that allows the administrator to remove the delete post functionality from the wp-admin area.
 * Version:       	3.0
 * Author:        	Jeremy Selph @ Reactive Development LLC
 * Author URI:    	http://www.reactivedevelopment.net/
 * License:       	GPL v3
 * License URI:   	http://www.gnu.org/licenses/gpl-3.0.en.html
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.

	Credits

	 * Plugin Development by Jeremy Selph @ Reactive Development LLC
 
 	Change Log
	
	 * 3.0 	Updated documentation, readmes, comments and phpDOC comments.                               2019/01/15
	 * 2.5  Re-developed by, Jeremy Selph http://www.reactivedevelopment.net/							2019/01/14
     * 2.0 	Released to WordPress.
	 * 0.2  updated public function reference                                                           2014/01/04
     * 0.1 	initial plugin development by, Jeremy Selph http://www.reactivedevelopment.net/

	Activation Instructions

	 * 01. 	Visit 'Plugins > Add New'
	 * 02. 	Search for 'Disable Delete Post or Page'
	 * 03.	Click on 'Install Now'
	 * 04. 	Activate the 'Disable Delete Post or Page' plugin.
	 * 05. 	Go to 'Settings > Disable Delete Post or Page' and modify.

     * @package rdl_remove_from_post_list
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 3.0
     * @access public
     */
    function rdl_defaults(){        
        
        $r = new stdClass();
        $r->pnme = 'Disable delete link';
        $r->slug = 'rd-disable-delete-post-or-page';
        $r->meta = '_jsRemoveDeleteLink';
        $r->msg1 = 'You cannot delete this %s.';
        $r->msg2 = 'Remove the ability to delete this %s.';
        $r->msg3 = 'Remove trash link';
        $r->optn = 'rdlsettings';
        $r->suem = 'info@reactivedevelopment.net';
        $r->surl = 'https://www.reactivedevelopment.net/';
        $r->purl = 'https://wordpress.org/support/plugin/disable-delete-post-or-page-link-wordpress-plugin';
        $r->sprl = 'https://www.reactivedevelopment.net/contact/project-mind/?plugin=disable-delete-post-or-page';

        return $r;
        
    }

    /** @package rdl_on_install
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @since 0.1
     * @access public
     */
    function rdl_on_install(){
        if ( !get_option( 'jsDisableDeletePost', false ) ){

            add_option( 'jsDisableDeletePost', 'yes', '', 'no' );

        }
    } register_activation_hook( __FILE__, 'rdl_on_install' );

    /**
     * @package rdl_remove_from_post_list
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @since 0.1
     * @access public
     */
    function rdl_remove_from_post_list( $actions, $post ){
        if( get_post_meta( $post->ID, rdl_defaults()->meta, true ) == 'yes' ){ 
            
            unset( $actions[ 'trash' ] );
        
        } return $actions;        
    } add_filter( 'post_row_actions', 'rdl_remove_from_post_list', 10, 2 );
      add_filter( 'page_row_actions', 'rdl_remove_from_post_list', 10, 2 );

    /**
     * @package rdl_remove_from_edit
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @since 0.1
     * @access public
     */
    function rdl_remove_from_edit(){
        
        $post_id = intval( $_GET[ 'post' ] );
        if ( $post_id > 0 ){
            if ( get_post_meta( $post_id, rdl_defaults()->meta, true ) == 'yes' ){
                
                $type = get_post_type( $post_id );
                $objt = get_post_type_object( $type );
                ?><style>#delete-action{ display:none; } #js-remove-delete-message{ position:absolute; bottom:11px; }</style>
                    <div id="js-remove-delete-message">
                        <?php _e( str_replace( ' %s', '', rdl_defaults()->msg1 ) ); ?>
                    </div><?php
            
            }
        }
        
    } add_action( 'post_submitbox_start', 'rdl_remove_from_edit' );

    /**
     * @package rdl_deny_trash
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @since 0.1
     * @access public
     */
    function rdl_deny_trash( $caps ){
        if ( isset( $_GET[ 'post' ] ) &&
             isset( $_GET[ 'action' ] ) && ( $_GET[ 'action' ] == 'trash' || $_GET[ 'action' ] == 'delete' ) ){
            
            $post_id = intval( $_GET[ 'post' ] );
            if ( $post_id > 0 && 
                 get_post_meta( $post_id, rdl_defaults()->meta, true ) == 'yes' ){

                foreach( $caps as $c => $v ){ $caps[ $c ] = false; }

            }

        } return $caps;
    } add_filter( 'user_has_cap', 'rdl_deny_trash', 99, 3 );

    /**
     * @package rdl_add_checkBox
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @since 0.1
     * @access public
     * @note found this example in the dont-break-the-code-example
     */
    function rdl_add_checkBox( $current, $screen ){  
        
        $check = $checked = '';
        $post_id = intval( $_GET[ 'post' ] );
        if ( $post_id > 0 ){
            if( current_user_can( 'administrator' ) ){
                
                $type = get_post_type( $post_id );
                $objt = get_post_type_object( $type );
                $opt = get_post_meta( $post_id, rdl_defaults()->meta, true );
                $check  = '<h5>' . sprintf( __( rdl_defaults()->msg2 ), strtolower( $objt->labels->singular_name ) ) . '</h5>';
                if ( $opt == 'yes' ){ $checked = ' checked="checked" '; }
                $check .= '<input type="checkbox" id="jsRemoveDeleteLink" name="jsRemoveDeleteLink"' . $checked . '/>'
                        . '<label for="jsRemoveDeleteLink">'
                            . __( rdl_defaults()->msg3 )
                        . '</label> ';
                
            }
        
        } return $check;
        
    } add_filter( 'screen_settings', 'rdl_add_checkBox', 10, 2 );

    /**
     * @package rdl_add_ajax
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @since 0.1
     * @access public
     * @note found this example in the dont-break-the-code-example
     */
    function rdl_add_ajax(){       
        
        $checked = $_POST[ 'checked' ];
        $post_id = intval( $_REQUEST[ 'post' ] );
        if( wp_verify_nonce( $_REQUEST[ 'nounce' ], 'rdl-nounce' ) && 
            $post_id > 0 && $checked !== NULL ){
            
            update_post_meta( $post_id, rdl_defaults()->meta, $checked );            

        } echo $checked; exit;
        
    } add_action( 'wp_ajax_rdlRemove', 'rdl_add_ajax' );

    /**
     * @package rdl_add_jquery
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @access public
     */
    function rdl_add_jquery( $hook ){
        if ( $hook == 'post.php' || $hook == 'settings_page_' . rdl_defaults()->slug ){
            
            global $post;
            $type = get_post_type_object( $post->post_type );
            wp_enqueue_style(
                    
                'rdl-css', 
                plugin_dir_url( __FILE__ ) . 'assets/disable_delete_post_or_page.css',
                array(),
                '0.1'
            
            );            

            /**
             * add admin scripts
             * @author Jeremy Selph <jselph@reactivedevelopment.net>
             * @version 2.5
            */
            if ( $hook == 'post.php' ){

                $type = get_post_type( $post_id );
                $objt = get_post_type_object( $type );
                wp_enqueue_script( 
                        
                    'rdl-js', 
                    plugin_dir_url( __FILE__ ) . 'assets/disable_delete_post_or_page.js',
                    array( 'jquery' ),
                    '0.1',
                    true
                
                );

                /**
                 * add ajax params
                 * @author Jeremy Selph <jselph@reactivedevelopment.net>
                 * @version 2.5
                */				
                wp_localize_script(
                    
                    'rdl-js',
                    'rdl',
                    array(

                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'ajaxNounce' => wp_create_nonce( 'rdl-nounce' ),
                        'msg' => __( str_replace( ' %s', '', rdl_defaults()->msg1 ) ),
                        'post_id' => $post->ID

                    )

                );

            }

        }
    } add_action( 'admin_enqueue_scripts', 'rdl_add_jquery', 10, 1 );

    /**
     * @package rdl_register_options
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @access public
    */
    function rdl_register_options(){

        register_setting( rdl_defaults()->optn . '-options', rdl_defaults()->optn );

    } add_action( 'admin_init', 'rdl_register_options' );

    /**
     * @package rdl_page_content
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @access public
    */
    function rdl_page_content(){

        $option = get_option( rdl_defaults()->optn, array( 'hide' => 'no' ) );

      ?><div class="wrap rdlcontent">

            <div id="icon-options-general" class="icon32"></div>
            <h1><?php _e( 'Disable delete posts or pages' ) ?></h1>
        
            <div id="poststuff"><div id="post-body" class="metabox-holder columns-2"><div id="post-body-content">

                <!-- Overview -->
                <div class="meta-box-sortables ui-sortable"><div class="postbox">    
                    <h2><span><?php _e( 'Overview' ); ?></span></h2>            
                    <div class="inside">

                        <p><?php _e( 'When handing over a WordPress installation to the end-client, there are always certain pages that you may not want them to delete. It could be pages with custom templates, pages with HTML in the WYSWIG editor or for some reason a page that is hooked in and is not dynamic.' ); ?></p>
                        <p><?php _e( 'Whatever the reason is the Disable Delete Post or Page Link Plugin removes the ability to delete a post if its option has been previously set. The “Delete” links are removed from the following areas:' ); ?></p>
                        <ol>
                            <li>
                                <?php _e( 'When viewing the list of All Posts or All Pages.' ); ?><br /><br />
                                <img class="liImg" width="400px" src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/posts_normal.jpg" /><br /><br />
                                <?php _e( 'Link has been deleted.' ); ?><br /><br />
                                <img class="liImg" width="400px" src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/posts_deleted.jpg" />
                            </li>
                            <li>
                                <?php _e( 'When editing a post in the Publish meta box.' ); ?><br /><br />
                                <img class="liImg" width="250px" src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/edit_normal.jpg" /><br /><br />
                                <?php _e( 'Link has been deleted.' ); ?><br /><br />
                                <img class="liImg" width="250px" src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/edit_deleted.jpg" />
                            </li>
                        </ol>
                        
                        <h2><?php _e( 'Important notes' ); ?></h2>
                        <ol>
                            <li><?php _e( 'This plugin does not add anything to your current theme.' ); ?></li>
                            <li><?php _e( 'It will stop users from deleting posts, pages or other custom posts types if the option has been set.' ); ?></li>
                            <li><?php _e( 'The wp_trash_post() or wp_post_delete() functions are not affected and when used posts can and will be deleted.' ); ?></li>
                            <li><?php echo sprintf( 
                                __( 'The screen options panel is required to use the disable functionality. <a href="%s" target="_blank">See.</a>' ), 
                                esc_url( 'https://make.wordpress.org/support/user-manual/getting-to-know-wordpress/screen-options/' )
                            ); ?></li>
                        </ol>
                        
                        <h2><?php _e( 'Help and support' ); ?></h2>
                        <p><?php echo sprintf( 
                            __( 'For custom Plugin and Theme development requets email us at <a href="%s">%s</a> or go to <a href="%s" target="_blank">%s</a>. If you have questions or requests for this plugin go to <a href="%s" target="_blank">%s</a> or for quick and paid support go to <a href="%s" target="_blank">%s</a> to message us.' ), 
                            'mailto:' . rdl_defaults()->suem, 
                            rdl_defaults()->suem,
                            rdl_defaults()->surl,
                            rdl_defaults()->surl,
                            esc_url( rdl_defaults()->purl ),
                            esc_url( rdl_defaults()->purl ),
                            rdl_defaults()->sprl,
                            rdl_defaults()->sprl
                        ); ?></p>
                    
                    </div>    
                </div></div>
                
                <!-- Settings -->
                <div class="meta-box-sortables ui-sortable"><div class="postbox">    
                    <h2><span><?php _e( 'Settings' ); ?></span></h2>            
                    <div class="inside">

                        <p><?php _e( 'Use the form below to hide the settings page form the WordPress admin menu.' ) ?></p>
                        <form action="options.php" method="post" id="rdl_settings">

                            <?php settings_fields( rdl_defaults()->optn . '-options' ); ?>
                            <table class="widefat">
                                <tbody>
                                    
                                    <tr class="alternate">
                                        <td class="row-title"><label for="<?php echo rdl_defaults()->optn; ?>[hide]"><?php _e( 'Hide disable delete link page' ); ?>:</label></td>
                                        <td><input type="checkbox" value="yes" name="<?php echo rdl_defaults()->optn; ?>[hide]"<?php 
                                            if( esc_attr( $option[ 'hide' ] ) == 'yes' ){ ?> checked="checked"<?php } 
                                                ?>> <span><?php _e( 'Check to hide this settings page' ); ?>.</span></td>
                                    </tr>

                                </tbody>
                            </table><br />

                            <input type="submit" id="submit2" class="button-primary" value="<?php _e( 'Save Settings' ); ?>">

                        </form>

                    </div>    
                </div></div>
                
                <!-- How to -->
                <div class="meta-box-sortables ui-sortable"><div class="postbox">    
                    <h2><span><?php _e( 'How to' ); ?></span></h2>            
                    <div class="inside">

                        <p><?php _e( 'When using this &quot;Disable delete posts or pages&quot; plugin you can hide/remove the delete links and delete functionality from the areas shown above. Each post, page or custom post needs to be edited and its option set. To do this follow these steps:' ); ?></p>
                        <ol>
                            <li>
                                <?php _e( 'When editing the post click on &quot;Screen Options&quot; in the upper right-hand corner.' ); ?><br /><br />
                                <img class="liImg" src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/rdlstep_1.jpg" /><br /><br />
                            </li>
                            <li>
                                <?php _e( 'After the &quot;Screen Options&quot; panel has opened look for the label &quot;Remove the ability to delete this post&quot;. Then check the checkbox that says &quot;Remove trash link&quot;.' ); ?><br /><br />
                                <img class="liImg" src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/rdlstep_2.jpg" /><br />
                            </li>
                        </ol>
                    
                    </div>    
                </div></div>

            </div><div id="postbox-container-1" class="postbox-container"><div class="meta-box-sortables">

                <!-- Developer -->
                <div class="postbox">
                    <h2><span><?php _e( 'Developed by Jeremy Selph' ); ?> @</span></h2>
                    <div class="inside">

                        <a href="<?php echo rdl_defaults()->surl; ?>" target="_blank" class="rdLogo">
                            <img class="rdLogoImg" src="https://www.reactivedevelopment.net/wp-content/themes/reactive/img/reactive-development-web-development.png" alt="<?php _e( 'Web Development Experts' ); ?>">
                        </a>
                        <p><strong><?php _e( 'Reactive Development is a team of development experts, specializing in pixel perfect web development.' ); ?></strong></p>
                    
                    </div>
                </div>
                
                <!-- Contact -->
                <div class="postbox supportBox">
                    <h2><span><?php _e( 'Need Support or help?' );  ?></span></h2>
                    <div class="inside">

                        <p><?php echo sprintf( 
                            __( 'For questions or free support and after reading the &quot;How to&quot; section on this page go to %s. Otherwise...'),
                            '<strong><a href="' . esc_url( rdl_defaults()->purl ) . '" target="_blank">' . esc_url( rdl_defaults()->purl ) . '</strong></a>'
                        ); ?></p>
                        <p><img class="supportImg" src="<?php echo 'https://www.reactivedevelopment.net/wp-content/themes/reactive/img/support.jpg'; ?>" alt="<?php _e( 'Need support' ); ?>"></p>
                        <p><strong><?php echo sprintf( 
                            __( 'For paid or immediate support please go to %s to submit a request.' ),
                            '<a href="' . rdl_defaults()->sprl . '" target="_blank">' . rdl_defaults()->sprl . '</a>'
                        ); ?></strong></p>

                    </div>
                </div>
    
            </div></div></div><br class="clear"></div>
        
        </div><?php
    }
        
    /**
     * @package rdl_add_page
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 2.5
     * @access public
     */
    function rdl_add_page(){
        
        /**
         * add our settings page
         * @author Jeremy Selph <jselph@reactivedevelopment.net>
         * @since 2.5
         */
        add_options_page(

            __( rdl_defaults()->pnme ),
            __( rdl_defaults()->pnme ),
            'manage_options',
            rdl_defaults()->slug,
            'rdl_page_content'

        );

        /**
         * Hide the page from the menu
         * @author Jeremy Selph <jselph@reactivedevelopment.net>
         * @since 2.5
         */
        $option = get_option( rdl_defaults()->optn, array( 'hide' => 'no' ) );
        if ( esc_attr( $option[ 'hide' ] ) == 'yes' ){
            
            remove_submenu_page( 'options-general.php', rdl_defaults()->slug );
        
        }

    } add_action( 'admin_menu', 'rdl_add_page' );

    /**
     * @package rdl_settings_link
     * @author Jeremy Selph <jselph@reactivedevelopment.net>
     * @version 3.0
     * @access public
    */
    function rdl_settings_link( $plugin_meta, $plugin_file ){
        if ( plugin_basename( __FILE__ ) === $plugin_file ){

            $plugin_meta[] = sprintf(
                '<a href="%s">%s</a>',
                admin_url( 'options-general.php?page=' . rdl_defaults()->slug ),
                esc_html__( 'Settings' )
            );

            $plugin_meta[] = sprintf(
                '&#36; <a href="%s" target="_blank">%s</a>',
                rdl_defaults()->sprl,
                esc_html__( 'Paid support' )
            );

            $plugin_meta[] = sprintf(
                '&hearts; <a href="%s" target="_blank">%s</a>',
                'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RESFMU9LDAEDQ&source=url',
                esc_html__( 'Donate' )
            );

        } return $plugin_meta;
    } add_filter( 'plugin_row_meta', 'rdl_settings_link', 10, 2 );