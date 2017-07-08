<?php

if ( !class_exists( 'WP_Mobile_Menu' ) ) {
    die;
}
class WP_Mobile_Menu_options
{
    public function __construct()
    {
        $this->init_options();
    }
    
    private function init_options()
    {
        add_action( 'tf_create_options', array( $this, 'create_plugin_options' ) );
    }
    
    public function create_plugin_options()
    {
        global  $mm_fs ;
        $prefix = '';
        $menus = get_terms( 'nav_menu', array(
            'hide_empty' => true,
        ) );
        $menus_options = array();
        $menus_options[0] = __( 'Choose one menu', 'mob-menu-lang' );
        foreach ( $menus as $menu ) {
            $menus_options[$menu->name] = $menu->name;
        }
        // Initialize Titan with my special unique namespace
        $titan = TitanFramework::getInstance( 'mobmenu' );
        // Create my admin options panel
        $panel = $titan->createAdminPanel( array(
            'name' => __( 'Mobile Menu Options', 'mob-menu-lang' ),
            'icon' => 'dashicons-smartphone',
        ) );
        //Only proceed if we are in the plugin page
        
        if ( !is_admin() || isset( $_GET['page'] ) && $_GET['page'] == 'mobile-menu-options' ) {
            // Create General Options panel
            $general_tab = $panel->createTab( array(
                'name' => __( 'General Options', 'mob-menu-lang' ),
            ) );
            // Create Header Options panel
            $header_tab = $panel->createTab( array(
                'name' => __( 'Header options', 'mob-menu-lang' ),
            ) );
            // Create Left Menu Options panel
            $left_menu_tab = $panel->createTab( array(
                'name' => __( 'Left Menu options', 'mob-menu-lang' ),
            ) );
            // Create Right Menu Options panel
            $right_menu_tab = $panel->createTab( array(
                'name' => __( 'Right Menu options' ),
                'mob-menu-lang',
            ) );
            // Create Color Options panel
            $colors_tab = $panel->createTab( array(
                'name' => __( 'Color Options' ),
                'mob-menu-lang',
            ) );
            // Create Documentation panel
            $documentation_tab = $panel->createTab( array(
                'name' => __( 'Documentation' ),
                'mob-menu-lang',
            ) );
            // Documentation IFrame
            $documentation_tab->createOption( array(
                'type' => 'iframe',
                'url'  => 'http://wpmobilemenu.com/documentation-iframe/',
            ) );
            // Enable/Disable WP Mobile Menu
            $general_tab->createOption( array(
                'name'     => __( 'Enable Mobile Menu', 'mob-menu-lang' ),
                'id'       => 'enabled',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => 'Enable or disable the WP Mobile Menu without deactivate the plugin.',
                'enabled'  => 'On',
                'disabled' => 'Off',
            ) );
            // Width trigger
            $general_tab->createOption( array(
                'name'    => __( 'Mobile Menu Visibility(Width Trigger)', 'mob-menu-lang' ),
                'id'      => 'width_trigger',
                'type'    => 'number',
                'desc'    => __( 'The Mobile menu will appear at this window size. Place it at 5000 to be always visible. ', 'mob-menu-lang' ),
                'default' => get_option( 'mobmenu_opt_res_trigger', '1024' ),
                'max'     => '5000',
                'min'     => '479',
                'unit'    => 'px',
            ) );
            $enable_left_menu = get_option( 'mobmenu_opt_left_menu_enabled' );
            
            if ( $enable_left_menu == 'false' ) {
                $enable_left_menu = false;
            } else {
                $enable_left_menu = true;
            }
            
            // Enable/Disable Left Header Menu
            $general_tab->createOption( array(
                'name'     => __( 'Enable Left Menu', 'mob-menu-lang' ),
                'id'       => 'enable_left_menu',
                'type'     => 'enable',
                'default'  => $enable_left_menu,
                'desc'     => __( 'Enable or disable the WP Mobile Menu without deactivate the plugin.', 'mob-menu-lang' ),
                'enabled'  => 'On',
                'disabled' => 'Off',
            ) );
            $enable_right_menu = get_option( 'mobmenu_opt_right_menu_enabled' );
            
            if ( $enable_right_menu == 'false' ) {
                $enable_right_menu = false;
            } else {
                $enable_right_menu = true;
            }
            
            // Enable/Disable Right Header Menu
            $general_tab->createOption( array(
                'name'     => __( 'Enable Right Menu', 'mob-menu-lang' ),
                'id'       => 'enable_right_menu',
                'type'     => 'enable',
                'default'  => $enable_right_menu,
                'desc'     => 'Enable or disable the WP Mobile Menu without deactivate the plugin.',
                'enabled'  => __( 'On', 'mob-menu-lang' ),
                'disabled' => __( 'Off', 'mob-menu-lang' ),
            ) );
            $general_tab->createOption( array(
                'type' => 'note',
                'desc' => __( 'The Width trigger field is very important because it determines the width that will show the Mobile Menu. If you want it always visible set it to 5000px', 'mob-menu-lang' ),
            ) );
            $general_tab->createOption( array(
                'name' => __( 'Advanced Options', 'mob-menu-lang' ),
                'type' => 'heading',
            ) );
            $general_tab->createOption( array(
                'name'    => 'Hide elements by default',
                'id'      => 'default_hided_elements',
                'type'    => 'multicheck',
                'desc'    => 'Check the desired elements',
                'options' => array(
                '1' => '.nav',
                '2' => '.main-navigation',
                '3' => '.genesis-nav-menu',
            ),
                'default' => array( '1', '2', '3' ),
            ) );
            // Hide Html Elements
            $general_tab->createOption( array(
                'name'    => 'Hide Elements',
                'id'      => 'hide_elements',
                'type'    => 'text',
                'default' => get_option( 'mobmenu_opt_hide_selectors', '.main-navigation' ),
                'desc'    => '<p>This will hide the desired elements when the Mobile menu is trigerred at the chosen width.</p><p>You can use css class or IDs.</p><p> Example: .menu , #nav</p>',
            ) );
            //Custom css
            $general_tab->createOption( array(
                'name' => 'Custom CSS',
                'id'   => 'custom_css',
                'type' => 'code',
                'desc' => __( 'Put your custom CSS rules here', 'mob-menu-lang' ),
                'lang' => 'css',
            ) );
            // Sticky Html Elements
            $general_tab->createOption( array(
                'name'    => 'Sticky Html Elements',
                'id'      => 'sticky_elements',
                'type'    => 'text',
                'default' => '',
                'desc'    => '<p>If you are having issues with sticky elements that dont assume a sticky behaviour, enter the ids or class name that identify that element.</p>',
            ) );
            $header_tab->createOption( array(
                'name' => __( 'Logo options', 'mob-menu-lang' ),
                'type' => 'heading',
            ) );
            // Enable/Disable Site Logo
            $header_tab->createOption( array(
                'name'     => __( 'Site Logo', 'mob-menu-lang' ),
                'id'       => 'enabled_logo',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Choose if you want to display an image has logo or text instead.', 'mob-menu-lang' ),
                'enabled'  => __( 'Logo', 'mob-menu-lang' ),
                'disabled' => __( 'Text', 'mob-menu-lang' ),
            ) );
            //Site Logo Image
            $header_tab->createOption( array(
                'name'    => __( 'Logo', 'mob-menu-lang' ),
                'id'      => 'logo_img',
                'type'    => 'upload',
                'desc'    => __( 'Upload your logo image', 'mob-menu-lang' ),
                'default' => get_option( 'mobmenu_opt_site_logo_img' ),
            ) );
            //Header Height
            $header_tab->createOption( array(
                'name'    => __( 'Logo Height', 'mob-menu-lang' ),
                'id'      => 'logo_height',
                'type'    => 'number',
                'desc'    => __( 'Enter the height of the logo', 'mob-menu-lang' ),
                'default' => '',
                'max'     => '500',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            // Enable/Disable Logo Url
            $header_tab->createOption( array(
                'name'     => __( 'Disable Logo URL ', 'mob-menu-lang' ),
                'id'       => 'disabled_logo_url',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Choose if you want to disable the logo url to avoid being redirect to the homepage or alternative home url when touching the header logo.', 'mob-menu-lang' ),
                'enabled'  => __( 'Yes', 'mob-menu-lang' ),
                'disabled' => __( 'No', 'mob-menu-lang' ),
            ) );
            //Alternative Site URL
            $header_tab->createOption( array(
                'name'    => __( 'Alternative Logo URL', 'mob-menu-lang' ),
                'id'      => 'logo_url',
                'type'    => 'text',
                'desc'    => __( 'Enter you alternative logo URL. If you leave it blank it will use the Site URL.', 'mob-menu-lang' ),
                'default' => '',
            ) );
            //Logo Top Margin
            $header_tab->createOption( array(
                'name'    => __( 'Logo Top Margin', 'mob-menu-lang' ),
                'id'      => 'logo_top_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the logo top margin', 'mob-menu-lang' ),
                'default' => get_option( 'mobmenu_opt_header_logo_topmargin', '5' ),
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            $header_tab->createOption( array(
                'name' => __( 'Header options', 'mob-menu-lang' ),
                'type' => 'heading',
            ) );
            //Header Height
            $header_tab->createOption( array(
                'name'    => __( 'Header Height', 'mob-menu-lang' ),
                'id'      => 'header_height',
                'type'    => 'number',
                'desc'    => __( 'Enter the height of the header', 'mob-menu-lang' ),
                'default' => get_option( 'mobmenu_opt_header_height', '40' ),
                'max'     => '500',
                'min'     => '20',
                'unit'    => 'px',
            ) );
            // Header Text
            $header_tab->createOption( array(
                'name'    => __( 'Header Text', 'mob-menu-lang' ),
                'id'      => 'header_text',
                'type'    => 'text',
                'desc'    => __( 'Enter the desired text for the Mobile Header. If not specified it will use the site title.', 'mob-menu-lang' ),
                'default' => get_option( 'mobmenu_opt_header_text', '' ),
            ) );
            //Header Text Font Size
            $header_tab->createOption( array(
                'name'    => __( 'Header Text Font Size', 'mob-menu-lang' ),
                'id'      => 'header_font_size',
                'type'    => 'number',
                'desc'    => __( 'Enter the header text font size', 'mob-menu-lang' ),
                'default' => get_option( 'mobmenu_opt_header_font_size', '20' ),
                'max'     => '100',
                'min'     => '5',
                'unit'    => 'px',
            ) );
            //Header Logo/Text Alignment
            $header_tab->createOption( array(
                'name'    => 'Header Logo/Text Alignment',
                'id'      => 'header_text_align',
                'type'    => 'select',
                'desc'    => 'Chose the header Logo/Text alignment.',
                'options' => array(
                'left'   => 'Left',
                'center' => 'Center',
                'right'  => 'Right',
            ),
                'default' => 'center',
            ) );
            //Header Logo/Text Left Margin
            $header_tab->createOption( array(
                'name'    => __( 'Header Logo/Text Left Margin', 'mob-menu-lang' ),
                'id'      => 'header_text_left_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the header Logo/Text left margin (only used whit Header Left Alignment)', 'mob-menu-lang' ),
                'default' => '20',
                'max'     => '200',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            //Header Logo/Text Right Margin
            $header_tab->createOption( array(
                'name'    => __( 'Header Logo/Text Right Margin', 'mob-menu-lang' ),
                'id'      => 'header_text_right_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the header Logo/Text right margin (only used whit Header Right Alignment)', 'mob-menu-lang' ),
                'default' => '20',
                'max'     => '200',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            $def_value = $titan->getOption( 'header_font_size' );
            
            if ( $def_value > 0 ) {
                $def_value .= 'px';
            } else {
                $def_value = '';
            }
            
            $header_tab->createOption( array(
                'name'                => __( 'Header Menu Font', 'mob-menu-lang' ),
                'id'                  => 'header_menu_font',
                'type'                => 'font',
                'desc'                => __( 'Select a style', 'mob-menu-lang' ),
                'show_font_weight'    => true,
                'show_font_style'     => true,
                'show_line_height'    => true,
                'show_letter_spacing' => true,
                'show_text_transform' => true,
                'show_font_variant'   => false,
                'show_text_shadow'    => false,
                'show_color'          => false,
                'css'                 => '.mobmenu .headertext {
							value
				}',
                'default'             => array(
                'line-height' => '1.5em',
                'font-family' => 'Dosis',
                'font-size'   => $def_value,
            ),
            ) );
            //Left Menu
            $left_menu_tab->createOption( array(
                'name'    => __( 'Left Menu', 'mob-menu-lang' ),
                'id'      => 'left_menu',
                'type'    => 'select',
                'desc'    => __( 'Select the menu that will open in the left side.', 'mob-menu-lang' ),
                'options' => $menus_options,
                'default' => get_option( 'mobmenu_opt_left_menu', '0' ),
            ) );
            $left_menu_tab->createOption( array(
                'name' => __( 'Menu Icon', 'mob-menu-lang' ),
                'type' => 'heading',
            ) );
            // Icon Action Option
            $left_menu_tab->createOption( array(
                'name'     => __( 'Icon Action', 'mob-menu-lang' ),
                'id'       => 'left_menu_icon_action',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Open the Left Menu Panel or open a Link url.', 'mob-menu-lang' ),
                'enabled'  => 'Open Menu',
                'disabled' => 'Open Link Url',
            ) );
            // Text After/Before Icon
            $left_menu_tab->createOption( array(
                'name'    => __( 'Text After Icon', 'mob-menu-lang' ),
                'id'      => 'left_menu_text',
                'type'    => 'text',
                'desc'    => __( 'Enter the text that will appear after the Icon.', 'mob-menu-lang' ),
                'default' => '',
            ) );
            // Icon URL
            $left_menu_tab->createOption( array(
                'name'    => __( 'Icon Link URL', 'mob-menu-lang' ),
                'id'      => 'left_icon_url',
                'type'    => 'text',
                'desc'    => __( 'Enter the Icon Link Url.', 'mob-menu-lang' ),
                'default' => '',
            ) );
            // Icon URL Target
            $left_menu_tab->createOption( array(
                'name'     => __( 'Icon Link Url Target', 'mob-menu-lang' ),
                'id'       => 'left_icon_url_target',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Choose it the link will open in the same window or in the new window.', 'mob-menu-lang' ),
                'enabled'  => 'Self',
                'disabled' => 'Blank',
            ) );
            // Icon Image/text Option
            $left_menu_tab->createOption( array(
                'name'     => __( 'Icon Type', 'mob-menu-lang' ),
                'id'       => 'left_menu_icon_opt',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Choose if you want to display an image or an icon.', 'mob-menu-lang' ),
                'enabled'  => 'Image',
                'disabled' => 'Icon Font',
            ) );
            //Left Menu Icon Font
            $left_menu_tab->createOption( array(
                'name'    => __( 'Icon Font', 'mob-menu-lang' ),
                'id'      => 'left_menu_icon_font',
                'type'    => 'text',
                'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mob-menu-lang' ),
                'default' => 'menu',
            ) );
            //Left Menu Icon Font Size
            $left_menu_tab->createOption( array(
                'name'    => __( 'Icon Font Size', 'mob-menu-lang' ),
                'id'      => 'left_icon_font_size',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Icon Font Size', 'mob-menu-lang' ),
                'default' => '30',
                'max'     => '100',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            //Left Menu Icon
            $left_menu_tab->createOption( array(
                'name'        => __( 'Icon Image', 'mob-menu-lang' ),
                'id'          => 'left_menu_icon',
                'type'        => 'upload',
                'placeholder' => 'Click here to select the icon',
                'desc'        => __( 'Upload your left menu icon image', 'mob-menu-lang' ),
                'default'     => get_option( 'mobmenu_opt_left_icon' ),
            ) );
            //Left Menu Icon Top Margin
            $left_menu_tab->createOption( array(
                'name'    => __( 'Icon Top Margin', 'mob-menu-lang' ),
                'id'      => 'left_icon_top_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Icon Top Margin', 'mob-menu-lang' ),
                'default' => get_option( 'mobmenu_opt_left_icon_topmargin', '10' ),
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            //Left Menu Icon Left Margin
            $left_menu_tab->createOption( array(
                'name'    => __( 'Icon Left Margin', 'mob-menu-lang' ),
                'id'      => 'left_icon_left_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Icon Left Margin', 'mob-menu-lang' ),
                'default' => '5',
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            $left_menu_tab->createOption( array(
                'name' => __( 'Left Panel options', 'mob-menu-lang' ),
                'type' => 'heading',
            ) );
            //Left Menu Background Image
            $left_menu_tab->createOption( array(
                'name' => __( 'Panel Background Image', 'mob-menu-lang' ),
                'id'   => 'left_menu_bg_image',
                'type' => 'upload',
                'desc' => __( 'Upload your left menu background image(this will override the Background color option)', 'mob-menu-lang' ),
            ) );
            //Left Menu Background Image Opacity
            $left_menu_tab->createOption( array(
                'name'    => __( 'Panel Background Image Opacity', 'mob-menu-lang' ),
                'id'      => 'left_menu_bg_opacity',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Background image opacity', 'mob-menu-lang' ),
                'default' => '100',
                'max'     => '100',
                'min'     => '10',
                'step'    => '10',
                'unit'    => '%',
            ) );
            //Left Menu Gradient css
            $left_menu_tab->createOption( array(
                'name'    => __( 'Panel Background Gradient Css', 'mob-menu-lang' ),
                'id'      => 'left_menu_bg_gradient',
                'type'    => 'text',
                'desc'    => __( '<a href="https://webgradients.com/" target="_blank">Click here</a> to get your desired Gradient, just press the copy button and paste in this field.', 'mob-menu-lang' ),
                'default' => '',
            ) );
            // Left Menu Panel Width Units
            $left_menu_tab->createOption( array(
                'name'     => __( 'Menu Panel Width Units', 'mob-menu-lang' ),
                'id'       => 'left_menu_width_units',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Choose the width units.', 'mob-menu-lang' ),
                'enabled'  => 'Pixels',
                'disabled' => __( 'Percentage', 'mob-menu-lang' ),
            ) );
            //Left Menu Panel Width
            $left_menu_tab->createOption( array(
                'name'    => __( 'Menu Panel Width(Pixels)', 'mob-menu-lang' ),
                'id'      => 'left_menu_width',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Menu Panel Width', 'mob-menu-lang' ),
                'default' => '270',
                'max'     => '1000',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            //Left Menu Panel Width
            $left_menu_tab->createOption( array(
                'name'    => __( 'Menu Panel Width(Percentage)', 'mob-menu-lang' ),
                'id'      => 'left_menu_width_percentage',
                'type'    => 'number',
                'desc'    => __( 'Enter the Left Menu Panel Width', 'mob-menu-lang' ),
                'default' => '70',
                'max'     => '90',
                'min'     => '0',
                'unit'    => '%',
            ) );
            $left_menu_tab->createOption( array(
                'name'                => __( 'Left Menu Font', 'mob-menu-lang' ),
                'id'                  => 'left_menu_font',
                'type'                => 'font',
                'desc'                => __( 'Select a style', 'mob-menu-lang' ),
                'show_font_weight'    => true,
                'show_font_style'     => true,
                'show_line_height'    => true,
                'show_letter_spacing' => true,
                'show_text_transform' => true,
                'show_font_variant'   => false,
                'show_text_shadow'    => false,
                'show_color'          => false,
                'css'                 => '.mobmenul-container .left-menu-icon-text, #mobmenuleft  .mob-expand-submenu , #mobmenuleft > .widgettitle, #mobmenuleft li a, #mobmenuleft li a:visited, #mobmenuleft .mobmenu_content h2, #mobmenuleft .mobmenu_content h3 {
							value
				}',
                'default'             => array(
                'line-height' => '1.5em',
                'font-family' => 'Dosis',
            ),
            ) );
            //Right Menu
            $right_menu_tab->createOption( array(
                'name'    => __( 'Right Menu', 'mob-menu-lang' ),
                'id'      => 'right_menu',
                'type'    => 'select',
                'desc'    => __( 'Select the menu that will open in the right side.', 'mob-menu-lang' ),
                'options' => $menus_options,
                'default' => get_option( 'mobmenu_opt_right_menu', '0' ),
            ) );
            //Icon Heading
            $right_menu_tab->createOption( array(
                'name' => __( 'Menu Icon', 'mob-menu-lang' ),
                'type' => 'heading',
            ) );
            // Icon Action Option
            $right_menu_tab->createOption( array(
                'name'     => __( 'Icon Action', 'mob-menu-lang' ),
                'id'       => 'right_menu_icon_action',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Open the Right Menu Panel or open a Link url.', 'mob-menu-lang' ),
                'enabled'  => 'Open Menu',
                'disabled' => 'Open Link Url',
            ) );
            // Text After/Before Icon
            $right_menu_tab->createOption( array(
                'name'    => __( 'Text Before Icon', 'mob-menu-lang' ),
                'id'      => 'right_menu_text',
                'type'    => 'text',
                'desc'    => __( 'Enter the text that will appear before the Icon.', 'mob-menu-lang' ),
                'default' => '',
            ) );
            // Icon URL
            $right_menu_tab->createOption( array(
                'name'    => __( 'Icon Link URL', 'mob-menu-lang' ),
                'id'      => 'right_icon_url',
                'type'    => 'text',
                'desc'    => __( 'Enter the Icon Link Url.', 'mob-menu-lang' ),
                'default' => '',
            ) );
            // Icon URL Target
            $right_menu_tab->createOption( array(
                'name'     => __( 'Icon Link Url Target', 'mob-menu-lang' ),
                'id'       => 'right_icon_url_target',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Choose it the link will open in the same window or in the new window.', 'mob-menu-lang' ),
                'enabled'  => 'Self',
                'disabled' => 'Blank',
            ) );
            // Icon Image/Icon Font
            $right_menu_tab->createOption( array(
                'name'     => __( 'Icon Type', 'mob-menu-lang' ),
                'id'       => 'right_menu_icon_opt',
                'type'     => 'enable',
                'default'  => false,
                'desc'     => __( 'Choose if you want to display an image or an icon.', 'mob-menu-lang' ),
                'enabled'  => __( 'Image', 'mob-menu-lang' ),
                'disabled' => __( 'Icon Font', 'mob-menu-lang' ),
            ) );
            //RightLeft Menu Icon Font
            $right_menu_tab->createOption( array(
                'name'    => __( 'Icon Font', 'mob-menu-lang' ),
                'id'      => 'right_menu_icon_font',
                'type'    => 'text',
                'desc'    => __( '<div class="mobmenu-icon-holder"></div><a href="#" class="mobmenu-icon-picker button">Select menu icon</a>', 'mob-menu-lang' ),
                'default' => 'menu',
            ) );
            //Right Menu Icon Font Size
            $right_menu_tab->createOption( array(
                'name'    => __( 'Icon Font Size', 'mob-menu-lang' ),
                'id'      => 'right_icon_font_size',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Icon Font Size', 'mob-menu-lang' ),
                'default' => '30',
                'max'     => '100',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            //Right Menu Icon
            $right_menu_tab->createOption( array(
                'name'    => __( 'Icon Image', 'mob-menu-lang' ),
                'id'      => 'right_menu_icon',
                'type'    => 'upload',
                'desc'    => __( 'Upload your right menu icon image', 'mob-menu-lang' ),
                'default' => get_option( 'mobmenu_opt_right_icon' ),
            ) );
            //Right Menu Icon Top Margin
            $right_menu_tab->createOption( array(
                'name'    => __( 'Icon Top Margin', 'mob-menu-lang' ),
                'id'      => 'right_icon_top_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Icon Top Margin', 'mob-menu-lang' ),
                'default' => get_option( 'mobmenu_opt_right_icon_topmargin', '10' ),
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            //Right Menu Icon Right Margin
            $right_menu_tab->createOption( array(
                'name'    => __( 'Icon Right Margin', 'mob-menu-lang' ),
                'id'      => 'right_icon_right_margin',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Icon Right Margin', 'mob-menu-lang' ),
                'default' => '5',
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            //Background Heading
            $right_menu_tab->createOption( array(
                'name' => __( 'Right Panel options', 'mob-menu-lang' ),
                'type' => 'heading',
            ) );
            //Right Menu Background Image
            $right_menu_tab->createOption( array(
                'name' => __( 'Panel Background Image', 'mob-menu-lang' ),
                'id'   => 'right_menu_bg_image',
                'type' => 'upload',
                'desc' => __( 'upload your right menu background image(this will override the Background color option)', 'mob-menu-lang' ),
            ) );
            //Right Menu Background Image Opacity
            $right_menu_tab->createOption( array(
                'name'    => __( 'Panel Background Image Opacity', 'mob-menu-lang' ),
                'id'      => 'right_menu_bg_opacity',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Background image opacity', 'mob-menu-lang' ),
                'default' => '100',
                'max'     => '100',
                'min'     => '10',
                'step'    => '10',
                'unit'    => '%',
            ) );
            //Right Menu Gradient css
            $right_menu_tab->createOption( array(
                'name'    => __( 'Panel Background Gradient Css', 'mob-menu-lang' ),
                'id'      => 'right_menu_bg_gradient',
                'type'    => 'text',
                'desc'    => __( '<a href="https://webgradients.com/" target="_blank">Click here</a> to get your desired Gradient, just press the copy button and paste in this field.', 'mob-menu-lang' ),
                'default' => '',
            ) );
            //Right Menu Panel Width Units
            $right_menu_tab->createOption( array(
                'name'     => __( 'Menu Panel Width Units', 'mob-menu-lang' ),
                'id'       => 'right_menu_width_units',
                'type'     => 'enable',
                'default'  => true,
                'desc'     => __( 'Choose the width units.', 'mob-menu-lang' ),
                'enabled'  => __( 'Pixels', 'mob-menu-lang' ),
                'disabled' => __( 'Percentage', 'mob-menu-lang' ),
            ) );
            //Right Menu Panel Width
            $right_menu_tab->createOption( array(
                'name'    => __( 'Menu Panel Width(Pixels)', 'mob-menu-lang' ),
                'id'      => 'right_menu_width',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Menu Panel Width', 'mob-menu-lang' ),
                'default' => '270',
                'max'     => '450',
                'min'     => '0',
                'unit'    => 'px',
            ) );
            //Right Menu Panel Width
            $right_menu_tab->createOption( array(
                'name'    => __( 'Menu Panel Width(Percentage)', 'mob-menu-lang' ),
                'id'      => 'right_menu_width_percentage',
                'type'    => 'number',
                'desc'    => __( 'Enter the Right Menu Panel Width', 'mob-menu-lang' ),
                'default' => '70',
                'max'     => '90',
                'min'     => '0',
                'unit'    => '%',
            ) );
            //Right Menu Font
            $right_menu_tab->createOption( array(
                'name'                => __( 'Right Menu Font', 'mob-menu-lang' ),
                'id'                  => 'right_menu_font',
                'type'                => 'font',
                'desc'                => __( 'Select a style', 'mob-menu-lang' ),
                'show_font_weight'    => true,
                'show_font_style'     => true,
                'show_line_height'    => true,
                'show_letter_spacing' => true,
                'show_text_transform' => true,
                'show_font_variant'   => false,
                'show_text_shadow'    => false,
                'show_color'          => false,
                'css'                 => '#mobmenuright li a, #mobmenuright li a:visited, #mobmenuright .mobmenu_content h2, #mobmenuright .mobmenu_content h3 {
							value
				}',
                'default'             => array(
                'line-height' => '1.5em',
                'font-family' => 'Dosis',
            ),
            ) );
            //Header Background color
            $colors_tab->createOption( array(
                'name'    => __( 'Header Background Color', 'mob-menu-lang' ),
                'id'      => 'header_bg_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_header_bgcolor', '#3f444c' ),
            ) );
            //Header Text color
            $colors_tab->createOption( array(
                'name'    => __( 'Header Text Color', 'mob-menu-lang' ),
                'id'      => 'header_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_header_textcolor', '#fff' ),
            ) );
            // Header Left Menu Section
            $colors_tab->createOption( array(
                'name' => __( 'Left Menu Colors', 'mob-menu-lang' ),
                'type' => 'heading',
            ) );
            //Left Menu Icon color
            $colors_tab->createOption( array(
                'name'    => __( 'Left Menu Icon Color', 'mob-menu-lang' ),
                'id'      => 'left_menu_icon_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#fff',
            ) );
            //Left Panel Background color
            $colors_tab->createOption( array(
                'name'    => __( 'Background Color', 'mob-menu-lang' ),
                'id'      => 'left_panel_bg_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_left_menu_bgcolor', '#38404c' ),
            ) );
            //Left Panel Text color
            $colors_tab->createOption( array(
                'name'    => __( 'Text Color', 'mob-menu-lang' ),
                'id'      => 'left_panel_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_left_text_color', '#fff' ),
            ) );
            //Left Panel Background Hover Color
            $colors_tab->createOption( array(
                'name'    => __( 'Background Hover Color', 'mob-menu-lang' ),
                'id'      => 'left_panel_hover_bgcolor',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_left_bg_color_hover', '#8dacba' ),
            ) );
            //Left Panel Text color Hover
            $colors_tab->createOption( array(
                'name'    => __( 'Hover Text Color', 'mob-menu-lang' ),
                'id'      => 'left_panel_hover_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_left_text_color_hover', '#fff' ),
            ) );
            //Left Panel Sub-menu Background Color
            $colors_tab->createOption( array(
                'name'    => __( 'Submenu Background Color', 'mob-menu-lang' ),
                'id'      => 'left_panel_submenu_bgcolor',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_left_submenu_bg_color', '#3a3a3a' ),
            ) );
            //Left Panel Sub-menu Text Color
            $colors_tab->createOption( array(
                'name'    => __( 'Submenu Text Color', 'mob-menu-lang' ),
                'id'      => 'left_panel_submenu_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_left_submenu_text_color', '#fff' ),
            ) );
            // Header Right Menu Section
            $colors_tab->createOption( array(
                'name' => __( 'Right Menu Colors', 'mob-menu-lang' ),
                'type' => 'heading',
            ) );
            //Right Menu Icon color
            $colors_tab->createOption( array(
                'name'    => __( 'Right Menu Icon Color', 'mob-menu-lang' ),
                'id'      => 'right_menu_icon_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => '#fff',
            ) );
            //Right Panel Background color
            $colors_tab->createOption( array(
                'name'    => __( 'Panel Background Color', 'mob-menu-lang' ),
                'id'      => 'right_panel_bg_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_right_menu_bgcolor', '#38404c' ),
            ) );
            //Right Panel Text color
            $colors_tab->createOption( array(
                'name'    => __( 'Text Color', 'mob-menu-lang' ),
                'id'      => 'right_panel_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_right_text_color', '#fff' ),
            ) );
            //Right Panel Background Hover Color
            $colors_tab->createOption( array(
                'name'    => __( 'Background Hover Color', 'mob-menu-lang' ),
                'id'      => 'right_panel_hover_bgcolor',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_right_bg_color_hover', '#8dacba' ),
            ) );
            //Right Panel Text color Hover
            $colors_tab->createOption( array(
                'name'    => __( 'Hover Text Color', 'mob-menu-lang' ),
                'id'      => 'right_panel_hover_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_right_text_color_hover', '#fff' ),
            ) );
            //Right Panel Sub-menu Background Color
            $colors_tab->createOption( array(
                'name'    => __( 'Submenu Background Color', 'mob-menu-lang' ),
                'id'      => 'right_panel_submenu_bgcolor',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_right_submenu_bg_color', '#3a3a3a' ),
            ) );
            //Right Panel Sub-menu Text Color
            $colors_tab->createOption( array(
                'name'    => __( 'Submenu Text Color', 'mob-menu-lang' ),
                'id'      => 'right_panel_submenu_text_color',
                'type'    => 'color',
                'desc'    => '',
                'alpha'   => true,
                'default' => get_option( 'mobmenu_opt_right_submenu_text_color', '#fff' ),
            ) );
            $panel->createOption( array(
                'type' => 'save',
            ) );
        }
    
    }

}