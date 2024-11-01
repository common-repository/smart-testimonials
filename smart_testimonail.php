<?php
/*
Plugin Name: Smart Testimonails plugin
Description: With Smart Testimonails you can use custom post types and taxonomies to manage testimonials section for your site along with many more features.
Version: 1.0
Author: TEST
Author URI: http://www.example.com
*/


//including scripts of color picker for admin
wp_enqueue_script( 'wp-color-picker' );
wp_enqueue_style( 'wp-color-picker' );

$expand = get_option('multifaq_expand');
if($expand == 'true')
{
add_action("init","aspire_smart_scripts_testi");
				function aspire_smart_scripts_testi(){
					wp_enqueue_script("jquery");
					wp_enqueue_script("jquery-ui-accordion");
					wp_enqueue_script("faq_accordion_aspire",plugins_url('/inc/js/faq_asp.js', __FILE__));
				}
}
include "roundit.php";
include "meta_box.php";

$css = get_option('multifaq_css');
if($css == 'true')
{
	wp_enqueue_style( 'asp_testi_front', plugins_url('/inc/style.css', __FILE__), array(), FAQ_VER, 'all' );
	wp_enqueue_style( '', plugins_url('/inc/jquery-ui.css', __FILE__), array(), FAQ_VER, 'all' );
}
//including style sheet for front-end. its a mandatory file.	

wp_enqueue_style( 'asp_testi_front', plugins_url('/inc/style.css', __FILE__), array(), FAQ_VER, 'all' );

class testimonial_Post_Type
{
	/**
	*  This is our constructor
	* @return FAQ_Post_Type
	*/
	public function __construct()
	{
		//Creating Custom Post Typ and Custom taxonomies
		$this->register_post_type();
		$this->taxonomies();
		
		// Creating Admin menu
		add_action( 'admin_menu',array( $this, 'admin_pages'));
		
		// Adding Scripts for admin menu
		add_action( 'admin_enqueue_scripts',array( $this, 'admin_scripts'	));
		add_action( 'wp_ajax_save_sort',array( $this, 'save_sort'));
	}
	/**
	*  This is our post type register function
	*
	 * @return FAQ_Post_Type
	*/
	public function register_post_type()
	{
		$labels = array(
			'name' => 'Testimonials',
			'singular_name' => 'Testimonial',
			'add_new' => 'Add New Testimonial',
			'add_new_item' => 'Add New Testimonial',
			'edit_item' => 'Edit Testimonial',
			'new_item' => 'New Testimonial',
			'all_items' => 'All Testimonials',
			'view_item' => 'View Testimonial',
			'search_items' => 'Search Testimonials',
			'not_found' =>  'No Testimonials found',
			'not_found_in_trash' => 'No Testimonial found in Trash', 
			'parent_item_colon' => '',
			
			'menu_name' => 'Testimonials'
		  );
	
		  $args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => array( 'slug' => 'asp_testi/' ),
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => true,
			'menu_icon'	=> plugins_url( '/plugin_images/faq_menu.png', __FILE__ ),
			'menu_position' => null,
			'supports' => array( 'title', 'editor', 'excerpt'),
			//'taxonomies' => array('post_tag'),
			'can_export' => true
		  ); 
	  // registering post type of faq		
	  register_post_type( 'asp_testi', $args );
	}
	
	/**
	*  This is our taxonomy register function
	* 
	 * @return FAQ_Post_Type
	*/
	public function taxonomies()
	{
		$taxanomies = array();
		$taxanomies['asp_testi_cat']=array(
			'hierarchical' => true,
			'query_var' => 'asp_testi_cat',
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'rewrite' => array(
				'slug' => 'asp_testi/asp_testi_cat',
			),
			$labels = array(
				'name' => 'Testimonials Categories',
				'singular_name' => 'Testimonial Category',
				'edit_item' => 'Edit Testimonial Category',
				'update_item' => 'Update Testimonial Category',
				'add_new_item' => 'New Testimonial Category',
				'all_items' => 'All Testimonials',
				'new_item_name' => 'Add New Testimonial Category',
				'search_items' => 'Search Testimonial Category',
				'popular_items' => 'Popular Testimonial Category',
				'search_item_with_comments' => ' Seprate Testimonial Category with comments',
				'add_or_remove_items' => 'Add or remove Testimonial Category',
				'choose_from_most_used' => 'Choose from most used Testimonial Category'
			  ),
		);
		
		$taxanomies['asp_testi_tag']=array(
			'hierarchical' => false,
			'query_var' => 'asp_testi_tag',
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'rewrite' => array(
				'slug' => 'asp_testi/asp_testi_tag',
			),
			$labels = array(
				'name' => 'Testimonials TAGs',
				'singular_name' => 'Testimonial TAGs',
				'edit_item' => 'Edit Testimonial TAGs',
				'update_item' => 'Update Testimonial TAGs',
				'add_new_item' => 'New Testimonial TAGs',
				'all_items' => 'All TAGs',
				'new_item_name' => 'Add New Testimonial TAGs',
				'search_items' => 'Search Testimonial TAGs',
				'popular_items' => 'Popular Testimonial TAGs',
				'search_item_with_comments' => ' Seprate Testimonial TAGs with comments',
				'add_or_remove_items' => 'Add or remove TAGs Category',
				'choose_from_most_used' => 'Choose from most used Testimonial TAGs'
			  ),
		);
		
		//parsing array to the function register_all_taxanomies
		$this->register_all_taxanomies($taxanomies);
	}
	
	/**
	*registering all taxonomies
	*
	 * @return FAQ_Post_Type
	*/
	public function register_all_taxanomies($taxanomies)
	{
		foreach($taxanomies as $name => $arr)
		{
			register_taxonomy($name,array('asp_testi'),$arr);

		}
		
	}
	/**
	 * Call admin pages
	 *
	* @return FAQ_Post_Type
	 */

	public function admin_pages() {
		
		add_submenu_page('edit.php?post_type=asp_testi', __('Sort Testimonials', ''), __('Sort Testimonials', 'wp_multifaq'), apply_filters( 'asp_testi', 'manage_options', 'asp_testisort' ), 'asp_testi-sort', array( &$this, 'sort_asp_testi' ));
		add_submenu_page('edit.php?post_type=asp_testi', __('Settings', ''), __('Settings', 'wp_multifaq'), apply_filters( 'asp_testi', 'manage_options', 'settings' ), 'asp_testi-options', array( &$this, 'settings_asp_testi' ));
		add_submenu_page('edit.php?post_type=asp_testi', __('Instructions', ''), __('Instructions', 'wp_multifaq'), apply_filters( 'asp_testi', 'manage_options', 'instructions' ), 'asp_testi-instructions', array( &$this, 'instructions_asp_testi' ));
		
	}
	

	
	/**
	 * Admin scripts and styles
	 *
	 * @return FAQ_Post_Type
	 */

	public function admin_scripts($hook) {

		$screen = get_current_screen();

		if ( is_object($screen) && 'asp_testi' == $screen->post_type ) :

			wp_enqueue_style( 'asp_testi-admin', plugins_url('/inc/css/faq-admin.css', __FILE__), array(), FAQ_VER, 'all' );

		endif;


			wp_enqueue_style( 'faq-admin', plugins_url('/inc/css/faq-admin.css', __FILE__), array(), FAQ_VER, 'all' );

			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script( 'faq-admin', plugins_url('/inc/js/faq.admin.init.js', __FILE__) , array('jquery'), FAQ_VER, true );

	
	}
	
	/**
	 * Save main options page structure
	 *
	 * @return FAQ_Post_Type
	 */
	public function save_options($option){
		global $wpdb;
		$table_options = $wpdb->prefix . "options"; 
		
		if($option!='')
		{
			if($option['css'] == '') { $option['css'] = 'false';}
			if($option['expand'] == '') { $option['expand'] = 'false';}
			$myopt = get_option('multifaq_htype');
			if($myopt != '') { 
			update_option('multifaq_htype', $option['htype']);  
			}
			else { 
			add_option( 'multifaq_htype', $option['htype'], '', 'yes' ); 
			}
			
			$myopt2 = get_option('multifaq_expand');
			if($myopt != '') { 
			update_option('multifaq_expand', $option['expand']);  
			}
			else { 
			add_option( 'multifaq_expand', $option['expand'], '', 'yes' ); 
			}
			
			$myopt3 = get_option('multifaq_css');
			if($myopt != '') { 
			update_option('multifaq_css', $option['css']);  
			}
			else { 
			add_option( 'multifaq_css', $option['css'], '', 'yes' ); 
			}
			
			$myopt4 = get_option('multifaq_color');
			
			if($myopt != '') { 
			update_option('multifaq_color', $option['color']);  
			}
			else { 
			add_option( 'multifaq_color', $option['color'], '', 'yes' ); 
			}
			
					
			update_option('headfont_family', $option['ddlHeadFont']);  
			update_option('multifaq_fontcolor', $option['fontcolor']);  
			update_option('multifaq_fontstyle', $option['fontstyle']);  
			update_option('multifaq_fontweight', $option['fontweight']);  
			
			
			
			update_option('content_color', $option['contentcolor']); 
			update_option('content_size', $option['contentfontsize']); 
			update_option('contentfont_family', $option['ddlContentFont']);  
			update_option('content_fontcolor', $option['contentfontcolor']);  
			update_option('content_fontstyle', $option['contentfontstyle']);  
			update_option('content_fontweight', $option['contentfontweight']); 

			update_option('person_color', $option['personcolor']); 
			update_option('person_size', $option['personfontsize']); 
			update_option('personfont_family', $option['ddlPersonFont']);  
			update_option('person_fontcolor', $option['personfontcolor']);  
			update_option('person_fontstyle', $option['personfontstyle']);  
			update_option('person_fontweight', $option['personfontweight']);

			update_option('desig_color', $option['desigcolor']); 
			update_option('desig_size', $option['desigfontsize']); 
			update_option('desigfont_family', $option['ddlDesigFont']);  
			update_option('desig_fontcolor', $option['desigfontcolor']);  
			update_option('desig_fontstyle', $option['desigfontstyle']);  
			update_option('desig_fontweight', $option['desigfontweight']); 
			
			
		
		}
	}
	
	/**
	 * Display main options page structure
	 *
	 * @return FAQ_Post_Type
	 */

	public function settings_asp_testi() {
		if (!current_user_can('manage_options') )
			return;
		if(isset($_POST['save_mutli_faq_setting']))
		{
			$options= array();
			$options= $_POST;
			$option = $options['faq_options'];
			$this->save_options($option);
		}
		?>

        <div class="wrap">
        	<div id="icon-faq-admin" class="icon32"><br /></div>
        	<h2><?php _e('Multiple Testimonial Settings', 'wp_multifaq') ?></h2>

			<?php
			if ( isset( $_GET['settings-updated'] ) )
    			echo '<div id="message" class="updated below-h2"><p>'. __('Testimonial Manager settings updated successfully.', 'wp_multifaq').'</p></div>';
			?>


			<div id="poststuff" class="metabox-holder has-right-sidebar">

			<?php
			//echo $this->settings_side();
			echo $this->settings_open();
			?>
				<style>
					fieldset
					{
						padding: 5px;
						border: 1px solid #808080;
						padding-top: 15px;
						margin-bottom:15px;
					}
					
					legend
					{
						font-weight: bold;
						font-size: 14px;
						background: #C0C0C0;
						padding: 5px;
					}
				</style>
	            <form method="post" action="?post_type=asp_testi&page=asp_testi-options">
                <h2 class="inst-title"><?php _e('Display Options') ?></h2>
				<fieldset>
					<legend>Testimonial Title</legend>
			    <?php
				$heading = get_option('multifaq_htype');
				echo '<select class="faq_htype  $htype; " name="faq_options[htype]" id="faq_htype">';
				for($i=1; $i<=6; $i++)
				{
					$h = 'h'.$i;
					if($h=='')
					{
						$h= 'h1';
					}
				?>
                <option value="<?php echo $h; ?>" <?php if($h == $heading) { echo 'selected'; }?> ><?php echo $h; ?></option>
                <?php
				}
                echo '</select>';
				?>
				<label type="select" for="faq_options[htype]"><?php _e('Choose your heading type for Testimonial title'); ?></label>
				
				<p>
					<script>
						jQuery(function(){
						var currentHeadFont = "<?php echo get_option('headfont_family'); ?>";
							 jQuery.ajax({
								url:'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDj2gERDy-H0Ji6LHEpD0R2CshF93jqBrU',
								cache: true,
								crossDomain: true,
								dataType: 'jsonp',
								type: "GET",
								success:function(data){
									 //  $("#output").html("data="+data.items);
									 	console.log(currentHeadFont);
										for(i=0;i<=data.items.length-1;i++)	
										{
											if(currentHeadFont == data.items[i].family)
											{
												jQuery("#ddlHeadFont").append('<option selected="selected">'+data.items[i].family+'</option>');
											}
											else
											{
												jQuery("#ddlHeadFont").append('<option>'+data.items[i].family+'</option>');
											}
										}
									},
								error:function(){
									alert("error in connection");
									}
							}); 

						});
					</script>
					<select id="ddlHeadFont" name="faq_options[ddlHeadFont]">
						<option>Select font family</option>
					</select>
					<label type="select" for="faq_options[ddlHeadFont]">
						<?php _e('Select font for title'); ?>
					</label>
					
                </p>
				
				 <p>
                <?php $fontstyle = get_option('multifaq_fontstyle'); ?>
                    <select id="faq_options[fontstyle]" name="faq_options[fontstyle]">
						<option <?php if($fontstyle == 'Normal') echo 'selected="selected"';?>>Normal</option>
						<option <?php if($fontstyle == 'Italic') echo 'selected="selected"';?>>Italic</option>
					</select>
                    <label for="faq_options[fontstyle]" rel="checkbox"><?php _e('Font Style', 'wp_multifaq'); ?></label>
                </p>
				
				 <p>
                <?php $fontweight = get_option('multifaq_fontweight'); ?>
                    <select id="faq_options[fontstyle]" name="faq_options[fontweight]">
						<option <?php if($fontweight == '0') echo 'selected="selected"';?>>0</option>
						<option <?php if($fontweight == '100') echo 'selected="selected"';?>>100</option>
						<option <?php if($fontweight == '200') echo 'selected="selected"';?>>200</option>
						<option <?php if($fontweight == '300') echo 'selected="selected"';?>>300</option>
						<option <?php if($fontweight == '400') echo 'selected="selected"';?>>400</option>
						<option <?php if($fontweight == '500') echo 'selected="selected"';?>>500</option>
						<option <?php if($fontweight == '600') echo 'selected="selected"';?>>600</option>
						<option <?php if($fontweight == '700') echo 'selected="selected"';?>>700</option>
						<option <?php if($fontweight == '800') echo 'selected="selected"';?>>800</option>
						<option <?php if($fontweight == '900') echo 'selected="selected"';?>>900</option>
					</select>
                    <label for="faq_options[fontstyle]" rel="checkbox"><?php _e('Font Weight', 'wp_multifaq'); ?></label>
                </p>
				
                <script type="text/javascript">
					jQuery(document).ready(function($){
						$('.my-color-field').wpColorPicker();
					});
				</script>
                <p>
                <?php $color = get_option('multifaq_color'); ?>
                    <input type="text" id="color" name="faq_options[color]" class="my-color-field" value="<?php echo $color; ?>">
                    <label for="faq_options[color]" rel="checkbox"><?php _e('Background Color', 'wp_multifaq'); ?></label>
                </p>
				
				<p>
                <?php $fontcolor = get_option('multifaq_fontcolor'); ?>
                    <input type="text" id="color" name="faq_options[fontcolor]" class="my-color-field" value="<?php echo $fontcolor; ?>">
                    <label for="faq_options[color]" rel="checkbox"><?php _e('Font Color', 'wp_multifaq'); ?></label>
                </p>
					
				</fieldset>	
    			
				
				<fieldset>
					<legend>Testimonial Content</legend>
			    
				<p>
					<script>
						jQuery(function(){
						var currentHeadFont = "<?php echo get_option('contentfont_family'); ?>";
							 jQuery.ajax({
								url:'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDj2gERDy-H0Ji6LHEpD0R2CshF93jqBrU',
								cache: true,
								crossDomain: true,
								dataType: 'jsonp',
								type: "GET",
								success:function(data){
									 //  $("#output").html("data="+data.items);
									 	console.log(currentHeadFont);
										for(i=0;i<=data.items.length-1;i++)	
										{
											if(currentHeadFont == data.items[i].family)
											{
												jQuery("#ddlContentFont").append('<option selected="selected">'+data.items[i].family+'</option>');
											}
											else
											{
												jQuery("#ddlContentFont").append('<option>'+data.items[i].family+'</option>');
											}
										}
									},
								error:function(){
									alert("error in connection");
									}
							}); 

						});
					</script>
					<select id="ddlContentFont" name="faq_options[ddlContentFont]">
						<option>Select font family</option>
					</select>
					<label type="select" for="faq_options[ddlContentFont]">
						<?php _e('Select font for title'); ?>
					</label>
					
                </p>
				
				 <p>
                <?php $contentfontsize = get_option('content_size'); ?>
                    <input type="text" value="<?php echo $contentfontsize;?>" id="faq_options[contentfontsize]" name="faq_options[contentfontsize]" placeholder="(e.g. 20px)" />
                    <label for="faq_options[contentfontsize]" rel="text"><?php _e('Font Size', 'wp_multifaq'); ?></label>
                </p>
				
				 <p>
                <?php $contentfontstyle = get_option('content_fontstyle'); ?>
                    <select id="faq_options[contentfontstyle]" name="faq_options[contentfontstyle]">
						<option <?php if($contentfontstyle == 'Normal') echo 'selected="selected"';?>>Normal</option>
						<option <?php if($contentfontstyle == 'Italic') echo 'selected="selected"';?>>Italic</option>
					</select>
                    <label for="faq_options[contentfontstyle]" rel="checkbox"><?php _e('Font Style', 'wp_multifaq'); ?></label>
                </p>
				
				 <p>
                <?php $contentfontweight = get_option('content_fontweight'); ?>
                    <select id="faq_options[contentfontweight]" name="faq_options[contentfontweight]">
						<option <?php if($contentfontweight == '0') echo 'selected="selected"';?>>0</option>
						<option <?php if($contentfontweight == '100') echo 'selected="selected"';?>>100</option>
						<option <?php if($contentfontweight == '200') echo 'selected="selected"';?>>200</option>
						<option <?php if($contentfontweight == '300') echo 'selected="selected"';?>>300</option>
						<option <?php if($contentfontweight == '400') echo 'selected="selected"';?>>400</option>
						<option <?php if($contentfontweight == '500') echo 'selected="selected"';?>>500</option>
						<option <?php if($contentfontweight == '600') echo 'selected="selected"';?>>600</option>
						<option <?php if($contentfontweight == '700') echo 'selected="selected"';?>>700</option>
						<option <?php if($contentfontweight == '800') echo 'selected="selected"';?>>800</option>
						<option <?php if($contentfontweight == '900') echo 'selected="selected"';?>>900</option>
					</select>
                    <label for="faq_options[contentfontweight]" rel="checkbox"><?php _e('Font Weight', 'wp_multifaq'); ?></label>
                </p>
				
                <script type="text/javascript">
					jQuery(document).ready(function($){
						$('.my-color-field').wpColorPicker();
					});
				</script>
                <p>
                <?php $contentcolor = get_option('content_color'); ?>
                    <input type="text" id="contentcolor" name="faq_options[contentcolor]" class="my-color-field" value="<?php echo $contentcolor; ?>">
                    <label for="faq_options[contentcolor]" rel="checkbox"><?php _e('Background Color', 'wp_multifaq'); ?></label>
                </p>
				
				<p>
                <?php $contentfontcolor = get_option('content_fontcolor'); ?>
                    <input type="text" id="contentfontcolor" name="faq_options[contentfontcolor]" class="my-color-field" value="<?php echo $contentfontcolor; ?>">
                    <label for="faq_options[contentfontcolor]" rel="checkbox"><?php _e('Font Color', 'wp_multifaq'); ?></label>
                </p>
					
				</fieldset>	
				
				<fieldset>
					<legend>Testimonial Person Name</legend>
			    
				<p>
					<script>
						jQuery(function(){
						var currentHeadFont = "<?php echo get_option('personfont_family'); ?>";
							 jQuery.ajax({
								url:'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDj2gERDy-H0Ji6LHEpD0R2CshF93jqBrU',
								cache: true,
								crossDomain: true,
								dataType: 'jsonp',
								type: "GET",
								success:function(data){
									 //  $("#output").html("data="+data.items);
									 	console.log(currentHeadFont);
										for(i=0;i<=data.items.length-1;i++)	
										{
											if(currentHeadFont == data.items[i].family)
											{
												jQuery("#ddlPersonFont").append('<option selected="selected">'+data.items[i].family+'</option>');
											}
											else
											{
												jQuery("#ddlPersonFont").append('<option>'+data.items[i].family+'</option>');
											}
										}
									},
								error:function(){
									alert("error in connection");
									}
							}); 

						});
					</script>
					<select id="ddlPersonFont" name="faq_options[ddlPersonFont]">
						<option>Select font family</option>
					</select>
					<label type="select" for="faq_options[ddlPersonFont]">
						<?php _e('Select font for title'); ?>
					</label>
					
                </p>
				
				 <p>
                <?php $personfontsize = get_option('person_size'); ?>
                    <input type="text" value="<?php echo $personfontsize;?>" id="faq_options[personfontsize]" name="faq_options[personfontsize]" placeholder="(e.g. 20px)" />
                    <label for="faq_options[personfontsize]" rel="text"><?php _e('Font Size', 'wp_multifaq'); ?></label>
                </p>
				
				 <p>
                <?php $personfontstyle = get_option('person_fontstyle'); ?>
                    <select id="faq_options[personfontstyle]" name="faq_options[personfontstyle]">
						<option <?php if($personfontstyle == 'Normal') echo 'selected="selected"';?>>Normal</option>
						<option <?php if($personfontstyle == 'Italic') echo 'selected="selected"';?>>Italic</option>
					</select>
                    <label for="faq_options[personfontstyle]" rel="checkbox"><?php _e('Font Style', 'wp_multifaq'); ?></label>
                </p>
				
				 <p>
                <?php $personfontweight = get_option('person_fontweight'); ?>
                    <select id="faq_options[personfontweight]" name="faq_options[personfontweight]">
						<option <?php if($personfontweight == '0') echo 'selected="selected"';?>>0</option>
						<option <?php if($personfontweight == '100') echo 'selected="selected"';?>>100</option>
						<option <?php if($personfontweight == '200') echo 'selected="selected"';?>>200</option>
						<option <?php if($personfontweight == '300') echo 'selected="selected"';?>>300</option>
						<option <?php if($personfontweight == '400') echo 'selected="selected"';?>>400</option>
						<option <?php if($personfontweight == '500') echo 'selected="selected"';?>>500</option>
						<option <?php if($personfontweight == '600') echo 'selected="selected"';?>>600</option>
						<option <?php if($personfontweight == '700') echo 'selected="selected"';?>>700</option>
						<option <?php if($personfontweight == '800') echo 'selected="selected"';?>>800</option>
						<option <?php if($personfontweight == '900') echo 'selected="selected"';?>>900</option>
					</select>
                    <label for="faq_options[personfontweight]" rel="checkbox"><?php _e('Font Weight', 'wp_multifaq'); ?></label>
                </p>
				
                <script type="text/javascript">
					jQuery(document).ready(function($){
						$('.my-color-field').wpColorPicker();
					});
				</script>
                <p>
                <?php $personcolor = get_option('person_color'); ?>
                    <input type="text" id="personcolor" name="faq_options[personcolor]" class="my-color-field" value="<?php echo $personcolor; ?>">
                    <label for="faq_options[personcolor]" rel="checkbox"><?php _e('Background Color', 'wp_multifaq'); ?></label>
                </p>
				
				<p>
                <?php $personfontcolor = get_option('person_fontcolor'); ?>
                    <input type="text" id="personfontcolor" name="faq_options[personfontcolor]" class="my-color-field" value="<?php echo $personfontcolor; ?>">
                    <label for="faq_options[personfontcolor]" rel="checkbox"><?php _e('Font Color', 'wp_multifaq'); ?></label>
                </p>
					
				</fieldset>	
				
				<fieldset>
					<legend>Testimonial Person Designation</legend>
			    
				<p>
					<script>
						jQuery(function(){
						var currentHeadFont = "<?php echo get_option('desigfont_family'); ?>";
							 jQuery.ajax({
								url:'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDj2gERDy-H0Ji6LHEpD0R2CshF93jqBrU',
								cache: true,
								crossDomain: true,
								dataType: 'jsonp',
								type: "GET",
								success:function(data){
									 //  $("#output").html("data="+data.items);
									 	console.log(currentHeadFont);
										for(i=0;i<=data.items.length-1;i++)	
										{
											if(currentHeadFont == data.items[i].family)
											{
												jQuery("#ddlDesigFont").append('<option selected="selected">'+data.items[i].family+'</option>');
											}
											else
											{
												jQuery("#ddlDesigFont").append('<option>'+data.items[i].family+'</option>');
											}
										}
									},
								error:function(){
									alert("error in connection");
									}
							}); 

						});
					</script>
					<select id="ddlDesigFont" name="faq_options[ddlDesigFont]">
						<option>Select font family</option>
					</select>
					<label type="select" for="faq_options[ddlDesigFont]">
						<?php _e('Select font for title'); ?>
					</label>
					
                </p>
				
				 <p>
                <?php $desigfontsize = get_option('desig_size'); ?>
                    <input type="text" value="<?php echo $desigfontsize;?>" id="faq_options[desigfontsize]" name="faq_options[desigfontsize]" placeholder="(e.g. 20px)" />
                    <label for="faq_options[desigfontsize]" rel="text"><?php _e('Font Size', 'wp_multifaq'); ?></label>
                </p>
				
				 <p>
                <?php $desigfontstyle = get_option('desig_fontstyle'); ?>
                    <select id="faq_options[desigfontstyle]" name="faq_options[desigfontstyle]">
						<option <?php if($desigfontstyle == 'Normal') echo 'selected="selected"';?>>Normal</option>
						<option <?php if($desigfontstyle == 'Italic') echo 'selected="selected"';?>>Italic</option>
					</select>
                    <label for="faq_options[desigfontstyle]" rel="checkbox"><?php _e('Font Style', 'wp_multifaq'); ?></label>
                </p>
				
				 <p>
                <?php $desigfontweight = get_option('desig_fontweight'); ?>
                    <select id="faq_options[desigfontweight]" name="faq_options[desigfontweight]">
						<option <?php if($desigfontweight == '0') echo 'selected="selected"';?>>0</option>
						<option <?php if($desigfontweight == '100') echo 'selected="selected"';?>>100</option>
						<option <?php if($desigfontweight == '200') echo 'selected="selected"';?>>200</option>
						<option <?php if($desigfontweight == '300') echo 'selected="selected"';?>>300</option>
						<option <?php if($desigfontweight == '400') echo 'selected="selected"';?>>400</option>
						<option <?php if($desigfontweight == '500') echo 'selected="selected"';?>>500</option>
						<option <?php if($desigfontweight == '600') echo 'selected="selected"';?>>600</option>
						<option <?php if($desigfontweight == '700') echo 'selected="selected"';?>>700</option>
						<option <?php if($desigfontweight == '800') echo 'selected="selected"';?>>800</option>
						<option <?php if($desigfontweight == '900') echo 'selected="selected"';?>>900</option>
					</select>
                    <label for="faq_options[desigfontweight]" rel="checkbox"><?php _e('Font Weight', 'wp_multifaq'); ?></label>
                </p>
				
                <script type="text/javascript">
					jQuery(document).ready(function($){
						$('.my-color-field').wpColorPicker();
					});
				</script>
                <p>
                <?php $desigcolor = get_option('desig_color'); ?>
                    <input type="text" id="desigcolor" name="faq_options[desigcolor]" class="my-color-field" value="<?php echo $desigcolor; ?>">
                    <label for="faq_options[desigcolor]" rel="checkbox"><?php _e('Background Color', 'wp_multifaq'); ?></label>
                </p>
				
				<p>
                <?php $desigfontcolor = get_option('desig_fontcolor'); ?>
                    <input type="text" id="desigfontcolor" name="faq_options[desigfontcolor]" class="my-color-field" value="<?php echo $desigfontcolor; ?>">
                    <label for="faq_options[desigfontcolor]" rel="checkbox"><?php _e('Font Color', 'wp_multifaq'); ?></label>
                </p>
					
				</fieldset>	
				
				
				
				<!-- submit -->
	    		<p id="faq-submit" class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" name="save_mutli_faq_setting" /></p>

				<p id="faq-desc" class="description"><?php _e('<strong>Note:</strong> You may need to flush your permalinks after changing settings.', 'wp_multifaq'); ?> <a href="<?php echo admin_url( 'options-permalink.php'); ?>"><?php _e('Go to your Permalink Settings here', 'wp_multifaq'); ?></a></p>

				</form>

	<?php echo $this->settings_close(); ?>

	</div>
	</div>


	<?php }
	
	/**
	 * Instructions Page
	 *
	 * @return FAQ_Post_Type
	 */

	public function instructions_asp_testi() {
		?>
        <div class="wrap">
        	<div id="icon-faq-admin" class="icon32"><br /></div>
        	<h2><?php _e('Testimonial Instructions', 'wp_multifaq'); ?></h2>
			<div id="poststuff" class="metabox-holder has-right-sidebar">
			<?php
			//echo $this->settings_side();
			echo $this->settings_open();
			?>
			<p><?php _e('The Smart Testimonails plugin uses a combination of custom post types, and taxonomies. The plugin will automatically create single post using your existing permalink structure. Testimonail categories and tags can be added to the menu by using the WP Menu Manager.', 'wp_asp_testi'); ?></p>

			<h2 class="inst-title"><?php _e('Shortcodes', 'wp_asp_testi'); ?></h2>
			<p><?php _e('The plugin provides ability to add short codes. Please follow the below mentioned syntax accordingly in the HTML tab:', 'asp_testi'); ?></p>
			<ul class="faqinfo">
			<li><strong>For the complete list (including title, image and content) see below:</strong></li><br />
			<li>Shortcode <code>[multi_testimonail]</code> will list all Testimonails in all categories and all tags.</li><br />
            <li>Shortcode <code>[multi_testimonail cat_name="general"]</code> will list all the Testimonails related to a particular category. User must input category's slug and not the category name.</li><br />
            <li>Shortcode <code>[multi_testimonail tag_name="support"]</code> will list all the Testimonails related to a particular tag slug.</li><br />
            <li>Shortcode <code>[multi_testimonail limit="5"]</code> will list Testimonails for a defined limit. In this case only 5 Testimonails will be listed.</li><br />
            <li>Shortcode <code>[multi_testimonail limit="5" cat_name="general"]</code> will list the Testimonails for a defined limit but within a specific category slug.</li><br />
            <li>Shortcode <code>[multi_testimonail limit="5" tag_name="support"]</code>will list the Testimonails for a defined limit but within a specific tag slug.</li><br />
            <li>Shortcode <code>[multi_testimonail cat_name="general, payment, order"]</code> will list all the Testimonails related to multiple categories (comma separated).  User must input category's slug and not the category name in each case.</li><br />
            <li>Shortcode <code>[multi_testimonail tag_name="support, delivery"]</code> will list all the Testimonails related to multiple tags (comma separated).  User must input tag's slug and not the tag name in each case.</li><br />
            <br>
            <li>Shortcode <code>[single_testimonail id="34"] </code> will list the Testimonail of id of 34. </li><br />
            
            <li><strong>Note:</strong> <code>tag_name</code> and <code>cat_name</code>, If both are used in any short code, preference will given to <code>tag_name</code> and related results will be shown.</li><br />
			</ul>
            
            <h2 class="inst-title"><?php _e('Widgets instructions', 'wp_asp_testi'); ?></h2>
            <p><?php _e('With Smart Testimonails plugin we have a widget that will perform multiple functions, below is the explanation.', 'wp_asp_testi'); ?></p>
			<ol class="faqinfo">
            	<li>Smart Testimonails Widget</li>
            </ol>
            <ul class="faqinfo">
            	<li><strong>Smart Testimonails Widget</strong></li>
                <li>This widget has 4 input parameters
                	<ol class="faqinfo">
                    	<li>Widget Title</li>
                        <li>Testimonails ID(s)</li>
                        <li>Display Image Option</li>
                        <li>Custom Css</li>
                    </ol>
                </li>
                <li>Widget Title can be any e.g. 'Testimonail'.</li>
                <li>Id(s) should be a real ID of Testimonail or multiple comma seperated IDs like (1,21,56). </li>
                <li>You need to select either YES or NO for the image display option.</li><br>
                <li>You have an option to add your custom css to style your testimonial widget by default it will pick your theme styling.</li><br>
                
            </ul>
	<?php echo $this->settings_close(); ?>

	</div>
	</div>

	<?php 
}
	
	 /**
     * Some extra stuff for the settings page
     *
     * this is just to keep the area cleaner
     *
     * @return FAQ_Post_Type
     */

    public function settings_side() { ?>

		<div id="side-info-column" class="inner-sidebar">
			<div class="meta-box-sortables">
				<div id="faq-admin-about" class="postbox">
					<h3 class="hndle" id="about-sidebar"><?php _e('About the Plugin', 'wp_multifaq'); ?></h3>
					<div class="inside">
						<p><?php _e('Talk to') ?> <a href="http://twitter.com/norcross" target="_blank">@norcross</a> <?php _e('on twitter or visit the', 'wp_multifaq'); ?> <a href="http://wordpress.org/support/plugin/wordpress-faq-manager/" target="_blank"><?php _e('plugin support form') ?></a> <?php _e('for bugs or feature requests.', 'wp_multifaq'); ?></p>
						<p><?php _e('<strong>Enjoy the plugin?</strong>', 'wp_multifaq'); ?><br />
						<a href="http://twitter.com/?status=I'm using @norcross's WordPress FAQ Manager plugin - check it out! http://l.norc.co/wp_multifaq/" target="_blank"><?php _e('Tweet about it', 'wp_multifaq'); ?></a> <?php _e('and consider donating.', 'wp_multifaq'); ?></p>
						<p><?php _e('<strong>Donate:</strong> A lot of hard work goes into building plugins - support your open source developers. Include your twitter username and I\'ll send you a shout out for your generosity. Thank you!', 'wp_multifaq'); ?><br />
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="11085100">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form></p>
					</div>
				</div>
			</div>

			<div class="meta-box-sortables">
				<div id="faq-admin-more" class="postbox">
					<h3 class="hndle" id="about-sidebar"><?php _e('Links', 'wp_multifaq'); ?></h3>
					<div class="inside">
						<ul>
						<li><a href="http://wordpress.org/extend/plugins/wordpress-faq-manager/" target="_blank"><?php _e('Plugin on WP.org', 'wp_multifaq'); ?></a></li>
						<li><a href="https://github.com/norcross/WordPress-FAQ-Manager" target="_blank"><?php _e('Plugin on GitHub', 'wp_multifaq'); ?></a></li>
						<li><a href="http://wordpress.org/support/plugin/wordpress-faq-manager" target="_blank"><?php _e('Support Forum', 'wp_multifaq'); ?></a><li>
            			<li><a href="<?php echo menu_page_url( 'faq-instructions', 0 ); ?>"><?php _e('Instructions page', 'wp_multifaq'); ?></a></li>
            			</ul>
					</div>
				</div>
			</div>
		</div> <!-- // #side-info-column .inner-sidebar -->

    <?php }

	public function settings_open() { ?>

		<div id="post-body" class="has-sidebar">
			<div id="post-body-content" class="has-sidebar-content">
				<div id="normal-sortables" class="meta-box-sortables">
					<div id="about" class="postbox">
						<div class="inside">

    <?php }

	public function settings_close() { ?>

						<br class="clear" />
						</div>
					</div>
				</div>
			</div>
		</div>

    <?php }
	
	
	/**
	 * Sort Page
	 *
	 * @return FAQ_Post_Type
	 */


	public function sort_asp_testi() {
		$questions = new WP_Query('post_type=asp_testi&posts_per_page=-1&orderby=menu_order&order=ASC');
	?>
		<div id="faq-admin-sort" class="wrap">
		<div id="icon-faq-admin" class="icon32"><br /></div>
		<h2><?php _e('Sort Testimonial', 'wp_multifaq'); ?> <img src=" <?php echo admin_url(); ?>/images/loading.gif" id="loading-animation" /></h2>
			<?php if ( $questions->have_posts() ) : ?>
	    	<p><?php _e('<strong>Note:</strong> This only affects the Testimonails listed using the shortcode functions', 'wp_multifaq'); ?></p>
			<ul id="custom-type-list">
				<?php while ( $questions->have_posts() ) : $questions->the_post(); ?>
					<li id="<?php the_id(); ?>"><?php the_title(); ?></li>
				<?php endwhile; ?>
	    	</ul>
			<?php else: ?>
			<p><?php _e('You have no FAQs to sort.', 'wp_multifaq'); ?></p>
			<?php endif; ?>
		</div>

	<?php }

	/**
	 * Save sort order
	 *
	 * @return FAQ_Post_Type
	 */

	public function save_sort() {
		global $wpdb; // WordPress database class

		$order = explode(',', $_POST['order']);
		$counter = 0;

		foreach ($order as $item_id) {
			$wpdb->update($wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $item_id) );
			$counter++;
		}
		die(1);
	}
	/**
	 * load scripts and styles for front end
	 *
	 * @return WP_FAQ_Manager
	 */

	public function front_style() {

		wp_enqueue_style( 'faq-style', plugins_url('/inc/css/faq-style.css', __FILE__), array(), FAQ_VER, 'all' );

	}

	public function front_script() {

		wp_enqueue_script( 'faq-init', plugins_url('/inc/js/faq.init.js', __FILE__) , array('jquery'), FAQ_VER, true );

	}

	public function scroll_script() {

		wp_enqueue_script( 'faq-scroll', plugins_url('/inc/js/faq.scroll.js', __FILE__) , array('jquery'), FAQ_VER, true );

	}

			
} 


/**
* Icluding Widget Files Aspire Smart Plugin
*/
include 'class.widget_cat.php';

// register Widgets of Aspire Smart Plugin

function register_testimonail_widget_cat() {
	
    register_widget( 'multi_testimonial_widgets_cat' );
}


add_action( 'widgets_init', 'register_testimonail_widget_cat' );

//initializing add_action and coresponding function
function codex2_custom_init() {
  new testimonial_Post_Type();
  // including shortcode's file 
  include dirname(__FILE__).'/mutiple_testimonial_shortcode.php';
}
add_action( 'init', 'codex2_custom_init' );
?>