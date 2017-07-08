<?php

if ( !class_exists( 'WP_Mobile_Menu' ) ) {
    die;
}
class WP_Mobile_Menu_Core
{
    public function __construct()
    {
    }
    
    /**
     * Init WP Mobile Menu
     *
     * @since 1.0
     */
    public function add_body_class()
    {
        if ( !$this->is_page_menu_disabled() ) {
            add_action( 'body_class', function ( $classes ) {
                $titan = TitanFramework::getInstance( 'mobmenu' );
                
                if ( !$titan->getOption( 'menu_display_type' ) ) {
                    $menu_display_type = 'mob-menu-slideout';
                } else {
                    $menu_display_type = 'mob-menu-overlay';
                }
                
                $classes[] = $menu_display_type;
                return $classes;
            } );
        }
    }
    
    //Frontend Scripts
    public function frontend_enqueue_scripts()
    {
        
        if ( !$this->is_page_menu_disabled() ) {
            wp_register_script( 'mobmenujs', plugins_url( 'js/mobmenu.js', __FILE__ ), array( 'jquery' ) );
            wp_enqueue_script( 'mobmenujs' );
            wp_enqueue_style( 'cssmobmenu-icons', plugins_url( 'css/mobmenu-icons.css', __FILE__ ) );
            // Filters
            add_filter( 'wp_head', array( $this, 'load_dynamic_css_style' ) );
        }
    
    }
    
    public function load_dynamic_css_style()
    {
        if ( !$this->is_page_menu_disabled() ) {
            include_once 'dynamic-style.php';
        }
    }
    
    public function get_icons_html()
    {
        if ( isset( $_POST['menu_item_id'] ) ) {
            $menu_item_id = absint( $_POST['menu_item_id'] );
        }
        if ( isset( $_POST['menu_id'] ) ) {
            $menu_id = absint( $_POST['menu_id'] );
        }
        if ( isset( $_POST['menu_title'] ) ) {
            $menu_title = $_POST['menu_title'];
        }
        if ( isset( $_POST['full_content'] ) ) {
            $full_content = $_POST['full_content'];
        }
        $seleted_icon = get_post_meta( $menu_item_id, '_mobmenu_icon', true );
        
        if ( !empty($seleted_icon) ) {
            $selected = ' data-selected-icon="' . $seleted_icon . '" ';
        } else {
            $selected = '';
        }
        
        $icons = $this->get_icons_list();
        
        if ( $full_content == 'yes' ) {
            $output = '<div class="mobmenu-icons-overlay"></div><div class="mobmenu-icons-content" data-menu-id="' . $menu_id . '"  data-menu-item-id="' . $menu_item_id . '">';
            $output .= '<div id="mobmenu-modal-header"><h2>' . $menu_title . ' - Menu Item Icon</h2><div class="mobmenu-icons-close-overlay"><span class="mobmenu-item mobmenu-close-overlay mob-icon-cancel-7"></span></div>';
            $output .= '<div class="mobmenu-icons-search"><input type="text" name="mobmenu_search_icons" id="mobmenu_search_icons" value="" placeholder="Search"><span class="mobmenu-item mob-icon-search-7"></span></div>';
            $output .= '<div class="mobmenu-icons-remove-selected">' . __( 'Remove Icon Selection', 'mob-menu-lang' ) . '</div>';
            $output .= '</div><div id="mobmenu-modal-body"><div class="mobmenu-icons-holder" ' . $selected . '>';
            //Loop through all the icons to create the icons list
            foreach ( $icons as $icon ) {
                $output .= '<span class="mobmenu-item mob-icon-' . $icon . '" data-icon-key="' . $icon . '"></span>';
            }
            $output .= '</div></div>';
        } else {
            $output = '<div class="mobmenu-icons-holder" ' . $selected . ' data-title="' . $menu_title . '" - Menu Item Icon" >';
        }
        
        echo  $output ;
        wp_die();
    }
    
    //Build the WP Mobile Menu Html Markup
    public function load_menu_html_markup()
    {
        global  $mm_fs ;
        $left_logged_in_user = false;
        $right_logged_in_user = false;
        $titan = TitanFramework::getInstance( 'mobmenu' );
        $menu_display_type = 'mob-menu-slideout';
        $output = '';
        //Premium options
        // Check if Header Menu Toolbar is enabled
        
        if ( 'yes' == $titan->getOption( 'enabled' ) && !$this->is_page_menu_disabled() ) {
            $header_text = $titan->getOption( 'header_text' );
            if ( $header_text == '' ) {
                $header_text = get_bloginfo();
            }
            $output .= '</div></ul>';
            $sticky_el_data_detach = '';
            if ( $titan->getOption( 'sticky_elements' ) ) {
                $sticky_el_data_detach = 'data-detach-el="' . $titan->getOption( 'sticky_elements' ) . '"';
            }
            $output .= '<div class="mob-menu-header-holder mobmenu" ' . $sticky_el_data_detach . '> ';
            
            if ( $titan->getOption( 'enable_left_menu' ) && !$left_logged_in_user ) {
                $left_menu_text = '';
                if ( $titan->getOption( 'left_menu_text' ) != '' ) {
                    $left_menu_text .= '<span class="left-menu-icon-text">' . $titan->getOption( 'left_menu_text' ) . '</span>';
                }
                
                if ( $titan->getOption( 'left_menu_icon_action' ) ) {
                    $output .= '<div  class="mobmenul-container"><a href="#" class="mobmenu-left-bt">';
                } else {
                    
                    if ( $titan->getOption( 'left_icon_url_target' ) ) {
                        $left_icon_url_target = '_self';
                    } else {
                        $left_icon_url_target = '_blank';
                    }
                    
                    $output .= '<div  class="mobmenul-container"><a href="' . $titan->getOption( 'left_icon_url' ) . '" target="' . $left_icon_url_target . '" id="mobmenu-center">';
                }
                
                $left_icon_image = wp_get_attachment_image_src( $titan->getOption( 'left_menu_icon' ) );
                $left_icon_image = $left_icon_image[0];
                
                if ( !$titan->getOption( 'left_menu_icon_opt' ) || $left_icon_image == '' ) {
                    $output .= '<i class="mob-icon-' . $titan->getOption( 'left_menu_icon_font' ) . ' mob-menu-icon"></i><i class="mob-icon-cancel mob-cancel-button"></i>';
                } else {
                    $output .= '<img src="' . $left_icon_image . '" alt="' . __( 'Left Menu Icon', 'mob-menu-lang' ) . '">';
                }
                
                $output .= $left_menu_text;
                $output .= '</a></div>';
            }
            
            $logo_img = wp_get_attachment_image_src( $titan->getOption( 'logo_img' ), 'full' );
            $logo_img = $logo_img[0];
            //Premium options
            
            if ( $mm_fs->is__premium_only() && $titan->getOption( 'logo_img_retina' ) ) {
                $logo_img_retina = wp_get_attachment_image_src( $titan->getOption( 'logo_img_retina' ), 'full' );
                $logo_img_retina = $logo_img_retina[0];
                $logo_img_retina_metadata = wp_get_attachment_metadata( $titan->getOption( 'logo_img_retina' ) );
                $logo_img_retina_width = intval( $logo_img_retina_metadata['width'], 10 ) / 2;
            }
            
            
            if ( $titan->getOption( 'disabled_logo_url' ) ) {
                $logo_url = '<h3 class="headertext">';
                $logo_url_end = '</h3>';
            } else {
                
                if ( $titan->getOption( 'logo_url' ) === '' ) {
                    $logo_url = get_bloginfo( 'url' );
                } else {
                    $logo_url = $titan->getOption( 'logo_url' );
                }
                
                $logo_url_end = '</a>';
                $logo_url = '<a href="' . $logo_url . '" class="headertext">';
            }
            
            $output .= '<div class="mob-menu-logo-holder">' . $logo_url;
            
            if ( $titan->getOption( 'enabled_logo' ) && $logo_img != '' ) {
                $output .= '<img class="mob-standard-logo" src="' . $logo_img . '"  alt=" ' . __( 'Logo Header Menu', 'mob-menu-lang' ) . '">';
                //Premium options
                if ( $mm_fs->is__premium_only() && $titan->getOption( 'logo_img_retina' ) ) {
                    $output .= '<img class="mob-retina-logo" width="' . $logo_img_retina_width . '" src="' . $logo_img_retina . '"  alt=" ' . __( 'Logo Header Menu', 'mob-menu-lang' ) . '">';
                }
            } else {
                $output .= $header_text;
            }
            
            $output .= $logo_url_end . '</div>';
            
            if ( $titan->getOption( 'enable_right_menu' ) && !$right_logged_in_user ) {
                $right_menu_text = '';
                if ( $titan->getOption( 'right_menu_text' ) != '' ) {
                    $right_menu_text .= '<span class="right-menu-icon-text">' . $titan->getOption( 'right_menu_text' ) . '</span>';
                }
                
                if ( $titan->getOption( 'right_menu_icon_action' ) ) {
                    $output .= '<div  class="mobmenur-container"><a href="#" class="mobmenu-right-bt">';
                } else {
                    
                    if ( $titan->getOption( 'right_icon_url_target' ) ) {
                        $right_icon_url_target = '_self';
                    } else {
                        $right_icon_url_target = '_blank';
                    }
                    
                    $output .= '<div  class="mobmenur-container"><a href="' . $titan->getOption( 'right_icon_url' ) . '" target="' . $right_icon_url_target . '">';
                }
                
                //$output .= '<div  class="mobmenur-container"><a href="#" class="mobmenu-right-bt">';
                $right_icon_image = wp_get_attachment_image_src( $titan->getOption( 'right_menu_icon' ) );
                $right_icon_image = $right_icon_image[0];
                
                if ( !$titan->getOption( 'right_menu_icon_opt' ) || $right_icon_image == '' ) {
                    $output .= '<i class="mob-icon-' . $titan->getOption( 'right_menu_icon_font' ) . ' mob-menu-icon"></i><i class="mob-icon-cancel mob-cancel-button"></i>';
                } else {
                    $output .= '<img src="' . $right_icon_image . '" alt="' . __( 'Right Menu Icon', 'mob-menu-lang' ) . '">';
                }
                
                $output .= $right_menu_text;
                $output .= '</a></div>';
            }
            
            $output .= '</div></ul></div>';
            echo  $output ;
            
            if ( $titan->getOption( 'enable_left_menu' ) && !$left_logged_in_user ) {
                ?>
				
				<div class="mob-menu-left-panel mobmenu">
					<?php 
                ?>
					<div class="mobmenu_content leftmtop">
					<?php 
                
                if ( is_active_sidebar( 'mobmlefttop' ) ) {
                    ?>
 
						<div class="leftmtop">	
							<?php 
                    dynamic_sidebar( 'Left Menu Top' );
                    ?>
						</div>
					<?php 
                }
                
                //Check if it was set and alternative menu for this specific page
                $current_left_menu = $titan->getOption( 'alternative_left_menu', get_the_ID() );
                if ( $current_left_menu == '' ) {
                    $current_left_menu = $titan->getOption( 'left_menu' );
                }
                //Display the left menu
                wp_nav_menu( array(
                    'menu'            => $current_left_menu,
                    'items_wrap'      => '<ul id="mobmenuleft" class="%2$s">%3$s</ul>',
                    'container_class' => 'menu rounded',
                    'container'       => '',
                    'fallback_cb'     => false,
                    'depth'           => 3,
                    'walker'          => new WP_Mobile_Menu_Walker_Nav_Menu( 'left' ),
                ) );
                //Check if the Left Menu Bottom Widget has any content
                
                if ( is_active_sidebar( 'mobmleftbottom' ) ) {
                    ?>
 
							<ul class="leftmbottom">
								<?php 
                    dynamic_sidebar( 'Left Menu Bottom' );
                    ?>
     		
							</ul>
					<?php 
                }
                
                ?>

				</div><div class="mob-menu-left-bg-holder"></div></div>

		 <?php 
            }
            
            
            if ( $titan->getOption( 'enable_right_menu' ) && !$right_logged_in_user ) {
                ?>
				<!--  Right Panel Structure -->
				<div class="mob-menu-right-panel mobmenu">
					<?php 
                ?>
					<div class="mobmenu_content">
					
		 <?php 
                //Check if the Right Menu Top Widget has any content
                
                if ( is_active_sidebar( 'mobmrighttop' ) ) {
                    ?>
 
					<ul class="rightmtop">
						<?php 
                    dynamic_sidebar( 'Right Menu Top' );
                    ?>
					</ul>
		 <?php 
                }
                
                ?>

		 <?php 
                //Check if it was set and alternative menu for this specific page
                $current_right_menu = $titan->getOption( 'alternative_right_menu', get_the_ID() );
                if ( $current_right_menu == '' ) {
                    $current_right_menu = $titan->getOption( 'right_menu' );
                }
                //Display the right menu
                wp_nav_menu( array(
                    'menu'            => $current_right_menu,
                    'items_wrap'      => '<ul id="mobmenuright" class="%2$s">%3$s</ul>',
                    'container_class' => 'menu rounded',
                    'container'       => '',
                    'fallback_cb'     => false,
                    'depth'           => 2,
                    'empty'           => false,
                    'walker'          => new WP_Mobile_Menu_Walker_Nav_Menu( 'right' ),
                ) );
                //Check if the Right Menu Bottom Widget has any content
                
                if ( is_active_sidebar( 'mobmrightbottom' ) ) {
                    ?>
 
					<ul class="rightmbottom">
						<?php 
                    dynamic_sidebar( 'Right Menu Bottom' );
                    ?>
					</ul>
	  	 <?php 
                }
                
                ?>
		

				</div><div class="mob-menu-right-bg-holder"></div></div>

		 <?php 
            }
        
        }
    
    }
    
    public function save_menu_item_icon()
    {
        
        if ( isset( $_POST['menu_item_id'] ) ) {
            $menu_item_id = absint( $_POST['menu_item_id'] );
            $menu_item_icon = $_POST['menu_item_icon'];
            if ( $menu_item_id > 0 ) {
                update_post_meta( $menu_item_id, '_mobmenu_icon', $menu_item_icon );
            }
            wp_send_json_success();
        }
    
    }
    
    //Register Sidebar Menu Widgets
    public function register_sidebar()
    {
        $args = array(
            'name'          => 'Left Menu Top',
            'id'            => 'mobmlefttop',
            'description'   => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        );
        register_sidebar( $args );
        $args = array(
            'name'          => 'Left Menu Bottom',
            'id'            => 'mobmleftbottom',
            'description'   => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        );
        register_sidebar( $args );
        $args = array(
            'name'          => 'Right Menu Top',
            'id'            => 'mobmrighttop',
            'description'   => '',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        );
        register_sidebar( $args );
        $args = array(
            'name'          => 'Right Menu Bottom',
            'id'            => 'mobmrightbottom',
            'description'   => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        );
        register_sidebar( $args );
    }
    
    //Check if WP Mobile Menu should be disabled in this page
    public function is_page_menu_disabled()
    {
        global  $mm_fs ;
        $titan = TitanFramework::getInstance( 'mobmenu' );
        //Premium options
        
        if ( $mm_fs->is__premium_only() && 'yes' == $titan->getOption( 'enabled' ) ) {
            $current_ID = 0;
            if ( isset( $wp_query->post ) ) {
                $current_ID = $wp_query->post->ID;
            }
            
            if ( !$titan->getOption( 'disable_menu_pages' ) ) {
                return false;
            } else {
                return in_array( $current_ID, $titan->getOption( 'disable_menu_pages' ) );
            }
        
        } else {
            
            if ( 'yes' == $titan->getOption( 'enabled' ) ) {
                return false;
            } else {
                return true;
            }
        
        }
    
    }
    
    public function get_icons_list()
    {
        global  $mm_fs ;
        $icons_base = array(
            'menu',
            'menu-2',
            'menu-3',
            'menu-1',
            'menu-outline',
            'plus',
            'user-1',
            'star-1',
            'ok-1',
            'ok-circled',
            'ok-circled2'
        );
        return $icons_base;
    }

}