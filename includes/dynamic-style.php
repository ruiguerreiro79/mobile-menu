<?php
    /*
	*
	*	Plugin Styling 
	*	------------------------------------------------
	*	WP Mobile Menu Pro
	* 	Copyright WP Mobile Menu 2017 - http://www.wpmobilemenu.com/upgrade
	*
	*
	*/

    /* CUSTOM CSS OUTPUT
 	================================================== */
	global $mm_fs;	 
	$titan = TitanFramework::getInstance( 'mobmenu' );

	$default_elements = "";
	$def_el_arr = $titan->getOption( 'default_hided_elements' );

	if ( in_array( "1" , $def_el_arr ) ) {
		$default_elements .= '.nav, '; 
	}

	if ( in_array( "2" , $def_el_arr ) ) {
		$default_elements .= '.main-navigation, '; 
	}

	if ( in_array( "3" , $def_el_arr ) ) {
		$default_elements .= '.genesis-nav-menu, '; 
	} 

	$default_elements .= '.hide';

	// Check if the Mobile Menu is enable in the plugin options
	if ( 'yes' == $titan->getOption( 'enabled' ) ) {

		$header_bg_color = $titan->getOption( 'header_bg_color' );
		$wrap_padding_top = $titan->getOption( 'header_height' );

		$trigger_res = $titan->getOption( 'width_trigger' );
		$right_menu_width = $titan->getOption( 'right_menu_width' ) . 'px';

	if ( $titan->getOption( 'right_menu_width_units' ) ) {
		
		$right_menu_width = $titan->getOption( 'right_menu_width' ) . 'px';
		$right_menu_width_translate = $right_menu_width;

	} else {
		
		$right_menu_width = $titan->getOption( 'right_menu_width_percentage' ) . '%';
		$right_menu_width_translate = '100%';

	}
	

	if ( $titan->getOption( 'left_menu_width_units' ) ) {

		$left_menu_width = $titan->getOption( 'left_menu_width' ) . 'px';
		$left_menu_width_translate = $left_menu_width;

	} else {
		
		$left_menu_width = $titan->getOption( 'left_menu_width_percentage' ) . '%';
		$left_menu_width_translate = '100%';

	}

	$logo_height = '';
	if ( $titan->getOption( 'logo_height' ) > 0 ) {
		$logo_height = 'height:' . $titan->getOption( 'logo_height' ) . 'px;';
	}

	?>

	<style>

	/* Hide WP Mobile Menu outside the width of trigger */
	@media only screen and (min-width:<?php	echo $trigger_res; ?>px){
		
		.mob_menu, .mob_menu_left_panel, .mob_menu_right_panel, .mobmenu {
			display: none;
		}
		
	}

	/* Our css Custom Options values */
	
	@media only screen and (max-width:<?php	echo $trigger_res; ?>px){
		<?php echo $titan->getOption('hide_elements'); ?> {
			display:none !important;
		}
	
		<?php 
			
			$header_height = $titan->getOption( 'header_height' );
			$total_header_height = $header_height;
			
		?>

		 .mob-menu-slideout  .mob-cancel-button{
			display: none;
		 }

		.mobmenu, .mob-menu-left-panel, .mob-menu-right-panel {
			display: block;
		}	
		.mobmenur-container i {
		 	color: <?php echo $titan->getOption( 'right_menu_icon_color' ); ?> ;
		}
		.mobmenul-container i {
			color: <?php echo $titan->getOption( 'left_menu_icon_color' ); ?> ;
		}
		#mobmenuleft li a , #mobmenuleft li a:visited {
			color: <?php echo $titan->getOption( 'left_panel_text_color' );?> ;

		}
		.mobmenu_content h2, .mobmenu_content h3, .show-nav-left .mob-menu-copyright, .show-nav-left .mob-expand-submenu i {
    		color: <?php echo $titan->getOption( 'left_panel_text_color' );?> ;
		}

		.mobmenu_content #mobmenuleft li:hover, .mobmenu_content #mobmenuright li:hover  {
 	         background-color: <?php echo $titan->getOption( 'left_panel_hover_bgcolor' );?>;
		}

		.mobmenu_content #mobmenuright li:hover  {
 	         background-color: <?php echo $titan->getOption( 'right_panel_hover_bgcolor' );?> ;
		}
		
		.mobmenu_content #mobmenuleft .sub-menu  {
 	         background-color: <?php echo $titan->getOption( 'left_panel_submenu_bgcolor' );?> ;
 	         margin: 0;
 	         color: <?php echo $titan->getOption( 'left_panel_submenu_text_color' );?> ;
 	         width: 100%;
			 position: initial;
 	         
		}
				 
		.mob-menu-left-bg-holder {
    		background: url(<?php echo wp_get_attachment_url( $titan->getOption("left_menu_bg_image") );?>);
    		opacity: <?php echo $titan->getOption("left_menu_bg_opacity") / 100  ; ?>;
    		background-attachment: fixed ;
			background-position: center top ;
			-webkit-background-size: cover ;
			-moz-background-size: cover ;
			background-size: cover ;
		}
		.mob-menu-right-bg-holder {
    		background: url(<?php echo wp_get_attachment_url( $titan->getOption("right_menu_bg_image") );?>);
    		opacity: <?php echo $titan->getOption("right_menu_bg_opacity") / 100  ; ?>;
    		background-attachment: fixed ;
			background-position: center top ;
			-webkit-background-size: cover ;
			-moz-background-size: cover ;
			background-size: cover ;
		}

		.mobmenu_content #mobmenuleft .sub-menu a {
 	         color: <?php echo $titan->getOption("left_panel_submenu_text_color");?> ;
		}

		.mobmenu_content #mobmenuright .sub-menu  a{
 	         color: <?php echo $titan->getOption("right_panel_submenu_text_color");?> ;
		}
		.mobmenu_content #mobmenuright .sub-menu .sub-menu {
			background-color: inherit;
		}

		.mobmenu_content #mobmenuright .sub-menu  {
 	         background-color: <?php echo $titan->getOption("right_panel_submenu_bgcolor");?> ;
 	         margin: 0;  
 	         color: <?php echo $titan->getOption("right_panel_submenu_text_color");?> ;
			 position: initial;
			 width: 100%;
		}

		#mobmenuleft li a:hover {
			color: <?php echo $titan->getOption("left_panel_hover_text_color");?> ;

		}
		
		#mobmenuright li a , #mobmenuright li a:visited, .show-nav-right .mob-menu-copyright, .show-nav-right .mob-expand-submenu i {
			color: <?php echo $titan->getOption('right_panel_text_color');?> ;
		}

		#mobmenuright li a:hover {
			color: <?php echo $titan->getOption('right_panel_hover_text_color');?> ;
		}

		.mobmenul-container {
			top: <?php echo $titan->getOption( 'left_icon_top_margin' ); ?>px;
			margin-left: <?php echo $titan->getOption( 'left_icon_left_margin' ); ?>px;
			margin-top: <?php echo $header_margin_top;?>px;
		}

		.mobmenur-container {
			top: <?php	echo $titan->getOption( 'right_icon_top_margin' ); ?>px;
			margin-right: <?php	echo $titan->getOption( 'right_icon_right_margin' ); ?>px;
			margin-top: <?php echo $header_margin_top;?>px;
		}
			
		/* 2nd Level Menu Items Padding */
		.mobmenu .sub-menu li a {
    		padding-left: 50px;
		}
			
		/* 3rd Level Menu Items Padding */
		.mobmenu .sub-menu .sub-menu li a {
    		padding-left: 75px;
		}
	        
	    
      <?php 
  	 
	  $header_margin_left = '';
  	  $header_margin_right = '';

	  if ( $titan->getOption( 'header_text_align' ) == 'left' ) {
 		 $header_margin_left = 'margin-left:' . $titan->getOption( 'header_text_left_margin' ) . 'px;';
  	  }

	 if ( $titan->getOption( 'header_text_align' ) == 'right' ) {
 		 $header_margin_right = 'margin-right:' . $titan->getOption( 'header_text_right_margin' ) . 'px;';
  	  } 
  
      ?>
		.mob-menu-logo-holder {
			padding-top: <?php echo $titan->getOption( 'logo_top_margin' ); ?>px;
			margin-top: <?php echo $header_margin_top;?>px;
			text-align: <?php echo $titan->getOption( 'header_text_align' ); ?>;
		    <?php echo $header_margin_left; ?>;
			<?php echo $header_margin_right; ?>;
		}

		.mob-menu-header-holder {

			background-color: <?php	echo $header_bg_color; ?> ;
			height: <?php echo $total_header_height; ?>px ;
			width: 100%;
			font-weight:bold;
			position:fixed;
			top:0px;	
			right: 0px;
			z-index: 99998;
			color:#000;
			display: block;
		}

		.mobmenu-push-wrap {
    		padding-top: <?php	echo $wrap_padding_top; ?>px;
		}
		<?php 

		if ( $titan->getOption( 'left_menu_bg_gradient' ) != '' ) {
			$left_panel_bg_color = $titan->getOption( 'left_menu_bg_gradient' ) . ';';
		} else {
			$left_panel_bg_color = 	'background-color:' . $titan->getOption( 'left_panel_bg_color' ) . ';';
		}

		if ( $titan->getOption( 'right_menu_bg_gradient' ) != '' ) {
			$right_panel_bg_color = $titan->getOption( 'right_menu_bg_gradient' ) .';';
		} else {
			$right_panel_bg_color = 	'background-color:' . $titan->getOption( 'right_panel_bg_color' ) . ';';
		}

		?>
	    .mob-menu-slideout 	.mob-menu-left-panel {
		    <?php echo $left_panel_bg_color; ?>;
			width:  <?php echo $left_menu_width; ?>;  
			-webkit-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
            -moz-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
            -ms-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
            -o-transform: translateX(-<?php echo $left_menu_width_translate; ?>);
            transform: translateX(-<?php echo $left_menu_width_translate; ?>);
		}     

		.mob-menu-slideout .mob-menu-right-panel {
			<?php echo $right_panel_bg_color; ?>
			width:  <?php echo $right_menu_width; ?>;  
			-webkit-transform: translateX( <?php echo $right_menu_width_translate; ?> );
            -moz-transform: translateX( <?php echo $right_menu_width_translate; ?> );
            -ms-transform: translateX( <?php echo $right_menu_width_translate; ?> );
            -o-transform: translateX( <?php echo $right_menu_width_translate; ?> );
            transform: translateX( <?php echo $right_menu_width_translate; ?> );
		}

		/* Will animate the content to the right 275px revealing the hidden nav */
		.mob-menu-slideout.show-nav-left .mobmenu-push-wrap, .mob-menu-slideout.show-nav-left .mob-menu-header-holder {

		    -webkit-transform: translate(<?php echo $left_menu_width_translate; ?>, 0);
		    -moz-transform: translate(<?php echo $left_menu_width_translate; ?>, 0);
		    -ms-transform: translate(<?php echo $left_menu_width_translate; ?>, 0);
		    -o-transform: translate(<?php echo $left_menu_width_translate; ?>, 0);
		    transform: translate(<?php echo $left_menu_width_translate; ?>, 0);

		    -webkit-transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
		    -moz-transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
		    -ms-transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
		    -o-transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
		    transform: translate3d(<?php echo $left_menu_width; ?>, 0, 0);
		}

		.mob-menu-slideout.show-nav-right .mobmenu-push-wrap , .mob-menu-slideout.show-nav-right .mob-menu-header-holder {

		    -webkit-transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);
		    -moz-transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);
		    -ms-transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);
		    -o-transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);
		    transform: translate(-<?php echo $right_menu_width_translate; ?>, 0);

		    -webkit-transform: translate3d(-<?php echo $right_menu_width; ?>, 0, 0);
		    -moz-transform: translate3d(-<?php echo $right_menu_width; ?>, 0, 0);
		    -ms-transform: translate3d(-<?php echo $right_menu_width; ?>, 0, 0);
		    -o-transform: translate3d(-<?php echo $right_menu_width; ?>, 0, 0);
		    transform: translate3d(-<?php echo $right_menu_width; ?>, 0, 0);
		}
  
		.mobmenu .headertext { 
			color: <?php echo $titan->getOption( 'header_text_color' ); ?> ;
		}

				
		/* Adds a transition and the resting translate state */
		.mob-menu-slideout .mobmenu-push-wrap, .mob-menu-slideout .mob-menu-header-holder {
			
		    -webkit-transition: all 300ms ease 0;
		    -moz-transition: all 300ms ease 0;
		    -o-transition: all 300ms ease 0;
		    transition: all 300ms ease 0;
		   /* margin-top: 30px;  */

		    -webkit-transform: translate(0, 0);
		    -moz-transform: translate(0, 0);
		    -ms-transform: translate(0, 0);
		    -o-transform: translate(0, 0);
		    transform: translate(0, 0);

		    -webkit-transform: translate3d(0, 0, 0);
		    -moz-transform: translate3d(0, 0, 0);
		    -ms-transform: translate3d(0, 0, 0);
		    -o-transform: translate3d(0, 0, 0);
		    transform: translate3d(0, 0, 0);

		    -webkit-transition: -webkit-transform .5s;
		    -moz-transition: -moz-transform .5s;
		    -ms-transition: -ms-transform .5s;
		    -o-transition: -o-transform .5s;
		    transition: transform .5s;
		}

		/* Mobile Menu Frontend CSS Style*/
		html, body {
			overflow-x: hidden;
		}

		/* Hides everything pushed outside of it */
		.mob-menu-slideout .mob-menu-left-panel {
			position: fixed;
			top: 0;
			height: 100%;
			z-index: 300000;
			overflow-y: auto;   
			overflow-x: hidden;
			opacity: 1;
			-webkit-transition: -webkit-transform .5s;
			-moz-transition: -moz-transform .5s;
			-ms-transition: -ms-transform .5s;
			-o-transition: -o-transform .5s;
			transition: transform .5s;
		}   

		.mob-menu-slideout.show-nav-left .mob-menu-left-panel {

			transition: transform .5s;
			-webkit-transform: translateX(0);
			-moz-transform: translateX(0);
			-ms-transform: translateX(0);
			-o-transform: translateX(0);
			transform: translateX(0);
		}

		body.admin-bar .mobmenu {
			top: 32px;
		}

		@media screen and ( max-width: 782px ){
			body.admin-bar .mobmenu {
				top: 46px;   
			}
		}

		.mob-menu-slideout .mob-menu-right-panel {
			position: fixed;
			top: 0;
			right: 0;
			height: 100%;
			z-index: 300000;
			overflow-y: auto;   
			overflow-x: hidden;
			opacity: 1;
			-webkit-transition: -webkit-transform .5s;
			-moz-transition: -moz-transform .5s;
			-ms-transition: -ms-transform .5s;
			-o-transition: -o-transform .5s;
			transition: transform .5s;
		}   

		.mob-menu-slideout.show-nav-right .mob-menu-right-panel {
			transition: transform .5s;
			-webkit-transform: translateX(0);
			-moz-transform: translateX(0);
			-ms-transform: translateX(0);
			-o-transform: translateX(0);
			transform: translateX(0);
		}

		.show-nav-left .mobmenu-push-wrap {
			height: 100%;
		}

		/* Will animate the content to the right 275px revealing the hidden nav */
		.mob-menu-slideout.show-nav-left .mobmenu-push-wrap, .show-nav-left .mob-menu-header-holder {
			-webkit-transition: -webkit-transform .5s;
			-moz-transition: -moz-transform .5s;
			-ms-transition: -ms-transform .5s;
			-o-transition: -o-transform .5s;
			transition: transform .5s;
		}

		.show-nav-right .mobmenu-push-wrap {
			height: 100%;
		}

		/* Will animate the content to the right 275px revealing the hidden nav */
		.mob-menu-slideout.show-nav-right .mobmenu-push-wrap , .mob-menu-slideout.show-nav-right .mob-menu-header-holder{  
			-webkit-transition: -webkit-transform .5s;
			-moz-transition: -moz-transform .5s;
			-ms-transition: -ms-transform .5s;
			-o-transition: -o-transform .5s;
			transition: transform .5s;
		}

		.widget img {
			max-width: 100%; 
		}

		#mobmenuleft, #mobmenuright {
			margin: 0;
			padding: 0;
		}

		#mobmenuleft  li > ul {
			display:none;
			left: 15px;
		}
		
		.mob-expand-submenu {
			position: relative;
			right: 0px;
			float: right;
			margin-top: -36px;
		}

		.mob-expand-submenu i {
			padding: 12px;
		}

		#mobmenuright  li > ul {
			display:none;
			left: 15px;
		}

		.rightmbottom, .rightmtop {
			padding-left: 10px;
			padding-right: 10px;                            
		}

		.mobmenu_content {
			z-index: 1;
			height: 100%;
			overflow: auto;
			padding-top: 20px;
		}
		
		.mobmenu_content li a {
			display: block;
			font-family: "Open Sans";
			letter-spacing: 1px;
			padding: 10px 20px;
			text-decoration: none;
			font-size: 14px;
		}

		.mobmenu_content li {
			list-style: none;
		}

		.mob-menu-slideout .mob_menu_left_panel_anim {
			-webkit-transition: all .30s ease-in-out !important;
			transition: all .30s ease-in-out !important;    
			transform: translate(0px) !important;
			-ms-transform: translate(0px) !important;   
			-webkit-transform: translate(0px) !important;
		}

		.mob-menu-slideout .mob_menu_right_panel_anim {
			-webkit-transition: all .30s ease-in-out !important;    
			transition: all .30s ease-in-out !important;    
			transform: translate(0px) !important;
			-ms-transform: translate(0px) !important;
			-webkit-transform: translate(0px) !important;
		}

		.mobmenul-container {
			position: absolute;
		}

		.mobmenur-container {
			position: absolute;     
			right: 0px; 
		} 

		.mob-menu-slideout .mob_menu_left_panel {
			width: 230px;
			height: 100%;
			position: fixed;
			top: 0px;
			left: 0px;
			z-index: 99999999;  
			transform: translate(-230px);
			-ms-transform: translate(-230px);
			-webkit-transform: translate(-230px);
			transition: all .30s ease-in-out !important;    
			-webkit-transition: all .30s ease-in-out !important;    
			overflow:hidden;		
		}  

		.leftmbottom h2 {
			font-weight: bold;
			background-color: transparent;
			color: inherit;
		}
		
		.mobmenu .mob-cancel-button, .show-nav-left .mobmenu .mob-menu-icon, .show-nav-right .mobmenu .mob-menu-icon {
			display:none;
		}
		
		.show-nav-left .mobmenu .mob-cancel-button,  .mobmenu .mob-menu-icon, .show-nav-right .mobmenu .mob-cancel-button {
			display:block;
		}

		.mobmenul-container i {
    		line-height: <?php echo $titan->getOption( 'left_icon_font_size' ); ?>px;
			font-size: <?php echo $titan->getOption( 'left_icon_font_size' ); ?>px;
			float: left;
		}
		.left-menu-icon-text{
			float: left;
			line-height: <?php echo $titan->getOption( 'left_icon_font_size' ); ?>px;
		}

		.right-menu-icon-text{
			float: right;
			line-height: <?php echo $titan->getOption( 'right_icon_font_size' ); ?>px;
		}
		
		.mobmenur-container i {
    		line-height: <?php echo $titan->getOption( 'right_icon_font_size' ); ?>px;
			font-size: <?php echo $titan->getOption( 'right_icon_font_size' ); ?>px;
			float: right;
		}
		
		.mobmenu_content .widget {
			padding-bottom: 0px;
			padding: 20px;
		}
		
		.mobmenu input[type="text"]:focus, .mobmenu input[type="email"]:focus, .mobmenu textarea:focus, .mobmenu input[type="tel"]:focus, .mobmenu input[type="number"]:focus {
			border-color: rgba(0, 0, 0, 0)!important;
		}	

		.mob-expand-submenu i {
			padding: 12px;
			top: 10px;
			position: relative;
			font-weight: 600;
			cursor: pointer;
			padding-left: 25px;
		}

		<?php echo $default_elements; ?> {
			display: none!important;
		}

		.mob-menu-left-bg-holder, .mob-menu-right-bg-holder {
			width: 100%;
			height: 100%;
			position: absolute;
			z-index: -50;
			background-size: cover!important;
			/*background-position: 0 0;*/
			background-repeat: no-repeat;
			top: 0;
			left: 0;
		}
		
		.mobmenu_content .sub-menu {
    		display: none;
		}

		.mob-standard-logo {
			display: inline-block;
			<?php echo $logo_height; ?>
		}
		
	}

	</style>

	<?php  	} 
		 
	?>