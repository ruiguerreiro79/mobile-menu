<?php

/* 
	Plugin Name: Mobile Menu 
	Plugin URI: http://www.wpmobilemenu.com/ 
	Description: An easy to use WordPress responsive mobile menu. Keep your mobile visitors engaged.
	Version: 2.4
	Author: Takanakui 
	Author URI: http://www.jedipress.com
	License: GPLv2 
*/
if ( !defined( 'ABSPATH' ) ) {
    die;
}
if ( !class_exists( 'WP_Mobile_Menu' ) ) {
    class WP_Mobile_Menu
    {
        public  $mm_fs ;
        public  $mobmenu_core ;
        /**
         * Constructor
         *
         * @since 1.0
         */
        public function __construct()
        {
            $this->init_mobile_menu();
        }
        
        public function wp_mobile_menu_custom_admin_notice()
        {
            ?>
	
				<div class="wp-mobile-menu-notice notice notice-success is-dismissible">
					<span class="dashicons dashicons-warning"></span>
        
					<?php 
            _e( '<strong>WP Mobile Menu PRO- </strong>If you need further features like 2000+ Menu Icons, 3rd Level Menus, Header Banner, Menus only visible for logged in users, alternative menus per page, Disable Mobile Menus in specific pages, Check the <a href="' . esc_url( $this->mm_fs()->get_upgrade_url() ) . '"> PRO Version Features</a> and the <a href="http://www.wpmobilemenu.com" target="_blank" >Demo site</a>', 'mob-menu-lang' );
            ?>
		
				</div>
	
		<?php 
        }
        
        /**
         * Init WP Mobile Menu
         *
         * @since 1.0
         */
        public function init_mobile_menu()
        {
            // Init Freemius.
            $this->mm_fs = $this->mm_fs();
            // Uninstall Action
            $this->mm_fs->add_action( 'after_uninstall', array( $this, 'mm_fs_uninstall_cleanup' ) );
            // Include Required files
            $this->include_required_files();
            //Instanciate the Menu Options
            new WP_Mobile_Menu_options();
            //Instanciate the Mobile Menu Core Functions
            $this->mobmenu_core = new WP_Mobile_Menu_Core();
            //Hooks
            if ( is_admin() ) {
                // Admin Scripts
                add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            }
            // Sidebar Menu Widgets
            add_action( 'wp_loaded', array( $this->mobmenu_core, 'register_sidebar' ) );
            //Load frontend assets
            if ( !is_admin() ) {
                $this->load_frontend_assets();
            }
            //Load Ajax actions
            $this->load_ajax_actions();
        }
        
        /**
         *  Init Freemius Settings
         *
         * @since 1.0
         */
        public function mm_fs()
        {
            global  $mm_fs ;
            
            if ( !isset( $this->mm_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $mm_fs = fs_dynamic_init( array(
                    'id'             => '235',
                    'slug'           => 'mobile-menu',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_1ec93edfb66875251b62505b96489',
                    'is_premium'     => false,
                    'has_addons'     => false,
                    'has_paid_plans' => false,
                    'menu'           => array(
                    'slug' => 'mobile-menu-options',
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $mm_fs;
        }
        
        /**
         * Include required files
         *
         * @since 1.0
         */
        private function include_required_files()
        {
            require_once dirname( __FILE__ ) . '/vendor/titan-framework/titan-framework-embedder.php';
            require_once dirname( __FILE__ ) . '/includes/class-wp-mobile-menu-core.php';
            require_once dirname( __FILE__ ) . '/includes/class-wp-mobile-menu-options.php';
            require_once dirname( __FILE__ ) . '/includes/class-wp-mobile-menu-walker-nav-menu.php';
        }
        
        /**
         * Load Frontend Assets
         *
         * @since 1.0
         */
        private function load_frontend_assets()
        {
            //Enqueue Html to the Footer
            add_action( 'wp_footer', array( $this->mobmenu_core, 'load_menu_html_markup' ) );
            // Frontend Scripts
            add_action( 'wp_enqueue_scripts', array( $this->mobmenu_core, 'frontend_enqueue_scripts' ), 100 );
            //Add menu display type class to the body
            add_action( 'init', array( $this->mobmenu_core, 'add_body_class' ) );
        }
        
        /**
         * Load Ajax actions
         *
         * @since 1.0
         */
        private function load_ajax_actions()
        {
            add_action( 'wp_ajax_get_icons_html', array( $this->mobmenu_core, 'get_icons_html' ) );
            add_action( 'wp_ajax_nopriv_get_icons_html', array( $this->mobmenu_core, 'get_icons_html' ) );
            add_action( 'wp_ajax_save_menu_item_icon', array( $this->mobmenu_core, 'save_menu_item_icon' ) );
            add_action( 'wp_ajax_nopriv_save_menu_item_icon', array( $this->mobmenu_core, 'save_menu_item_icon' ) );
        }
        
        //Admin Scripts
        public function admin_enqueue_scripts( $hook )
        {
            
            if ( 'toplevel_page_mobile-menu-options' == $hook || 'index.php' == $hook ) {
                add_action( 'admin_notices', array( $this, 'wp_mobile_menu_custom_admin_notice' ) );
                wp_enqueue_style( 'cssmobmenu-admin', plugins_url( 'includes/css/mobmenu-admin.css', __FILE__ ) );
            }
            
            
            if ( 'nav-menus.php' == $hook || 'toplevel_page_mobile-menu-options' == $hook ) {
                wp_enqueue_style( 'cssmobmenu-icons', plugins_url( 'includes/css/mobmenu-icons.css', __FILE__ ) );
                wp_enqueue_style( 'cssmobmenu-admin', plugins_url( 'includes/css/mobmenu-admin.css', __FILE__ ) );
                wp_register_script( 'mobmenu-admin-js', plugins_url( 'includes/js/mobmenu-admin.js', __FILE__ ), array( 'jquery' ) );
                wp_enqueue_script( 'mobmenu-admin-js' );
            }
        
        }
    
    }
}
//Instanciate the WP_Mobile_Menu
new WP_Mobile_Menu();