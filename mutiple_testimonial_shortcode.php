<?php
add_shortcode('multi_testimonail','asp_testi_shortcode');
/**
*intializing multi_faq shortcode function
*/
function asp_testi_shortcode($atts)
{
		$color = get_option('multifaq_color');
		$headFont = get_option('headfont_family');
		$fontcolor = get_option('multifaq_fontcolor');
		$fontstyle = get_option('multifaq_fontstyle');
		$fontweight = get_option('multifaq_fontweight');
		
		$contentcolor = get_option('content_color');
		$contentcolorsize = get_option('content_size');
		$contentheadFont = get_option('contentfont_family');
		$contentfontcolor = get_option('content_fontcolor');
		$contentfontstyle = get_option('content_fontstyle');
		$contentfontweight = get_option('content_fontweight');
		
		$personcolor = get_option('person_color');
		$personcolorsize = get_option('person_size');
		$personheadFont = get_option('personfont_family');
		$personfontcolor = get_option('person_fontcolor');
		$personfontstyle = get_option('person_fontstyle');
		$personfontweight = get_option('person_fontweight');
		
		$desigcolor = get_option('desig_color');
		$desigcolorsize = get_option('desig_size');
		$desigheadFont = get_option('desigfont_family');
		$desigfontcolor = get_option('desig_fontcolor');
		$desigfontstyle = get_option('desig_fontstyle');
		$desigfontweight = get_option('desig_fontweight');
		
		?>
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $headFont; ?>">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $contentheadFont; ?>">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $personheadFont; ?>">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $desigheadFont; ?>">
		<style>
		.newHeadingColor
		{
			background-color: <?php echo $color; ?>  !important;
			background-image: none !important;
			color:<?php echo $fontcolor; ?>;			
			font-family: '<?php echo $headFont; ?>', serif;
			font-style: <?php echo $fontstyle; ?>;
			font-weight: <?php echo $fontweight; ?>;
		}
		
		.heading_accordion
		
		{
			padding:5px;
			padding-left:10px;
			border:1px solid gray;
		}
		
		.content_testi
		{
			display:block;
			float:left;
			width:100%;
			
			background-color: <?php echo $contentcolor; ?>  !important;
			background-image: none !important;
			color:<?php echo $contentfontcolor; ?>;			
			font-family: '<?php echo $contentheadFont; ?>', serif;
			font-style: <?php echo $contentfontstyle; ?>;
			font-weight: <?php echo $contentfontweight; ?>;
			font-size: <?php echo $contentcolorsize; ?>;
			
		}
		
		.content_testi img
		{
			margin-top:0px;
		}
		
		.testi_by
		{
			float:right;
		}
		.testi_by_name
		{
		
			background-color: <?php echo $personcolor; ?>  !important;
			background-image: none !important;
			color:<?php echo $personfontcolor; ?>;			
			font-family: '<?php echo $personheadFont; ?>', serif;
			font-style: <?php echo $personfontstyle; ?>;
			font-weight: <?php echo $personfontweight; ?>;
			font-size: <?php echo $personcolorsize; ?>;
			
		}
		.testi_by_desig
		{
			background-color: <?php echo $desigcolor; ?>  !important;
			background-image: none !important;
			color:<?php echo $desigfontcolor; ?>;			
			font-family: '<?php echo $desigheadFont; ?>', serif;
			font-style: <?php echo $desigfontstyle; ?>;
			font-weight: <?php echo $desigfontweight; ?>;
			font-size: <?php echo $desigcolorsize; ?>;
		}
		
		.testi_box_single
		{
			overflow:hidden;
		}
		</style>
     
        
        <?php
		if(isset($atts['cat_name']) && $atts['cat_name']!='')
				$args =  array(
					'post_type' => 'asp_testi',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'asp_testi_cat' => $atts['cat_name'],
					'posts_per_page' => $atts['limit']		
				);
		else if(isset($atts['tag_name']) && $atts['tag_name']!='')
				$args =  array(
					'post_type' => 'asp_testi',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'asp_testi_tag' => $atts['tag_name'],
					'posts_per_page' => $atts['limit']		
				);
		else
				$args =  array(
					'post_type' => 'asp_testi',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'posts_per_page' => $atts['limit']		
				);
	// WP_Query
	$heading = get_option('multifaq_htype');
	if($heading == '')
	{
		$heading = 'h2';
	}
	$loop = new WP_Query($args);
	echo '<div class="accordions">';
	 while ( $loop->have_posts() ) : $loop->the_post(); 
	 if($color!='')
		{
			
			echo '<div class="testi_box_single"><'.$heading.' class="heading_accordion newHeadingColor">'.get_the_title().'</'.$heading.'>';
		}
	 else
	 {
				echo '<div class="testi_box_single"><'.$heading.' class="heading_accordion">'.get_the_title().'</'.$heading.'>';
	 }
			echo "<div class='content_testi'>";			
				the_content();
			echo "</div>";
			echo "<div class='testi_by'>";
				echo "<span class='testi_by_name'>".get_post_meta( $loop->post->ID, 'testi_by_name', true )."</span> "; 
				echo "<span class='testi_by_desig'>".get_post_meta( $loop->post->ID, 'testi_by_desig', true )."</span>";
			echo "</div> </div>";
				

				endwhile; // end of the loop.
	echo '</div>';
	wp_reset_query();
}

//Single FAQ Shortcode intiailizing
add_shortcode('single_testimonial','single_asptestimonial_shortcode');


/**
*intializing single shortcode function
*/
function single_asptestimonial_shortcode($atts)
{
	
		$color = get_option('multifaq_color');
		$headFont = get_option('headfont_family');
		$fontcolor = get_option('multifaq_fontcolor');
		$fontstyle = get_option('multifaq_fontstyle');
		$fontweight = get_option('multifaq_fontweight');
		
		$contentcolor = get_option('content_color');
		$contentcolorsize = get_option('content_size');
		$contentheadFont = get_option('contentfont_family');
		$contentfontcolor = get_option('content_fontcolor');
		$contentfontstyle = get_option('content_fontstyle');
		$contentfontweight = get_option('content_fontweight');
		
		$personcolor = get_option('person_color');
		$personcolorsize = get_option('person_size');
		$personheadFont = get_option('personfont_family');
		$personfontcolor = get_option('person_fontcolor');
		$personfontstyle = get_option('person_fontstyle');
		$personfontweight = get_option('person_fontweight');
		
		$desigcolor = get_option('desig_color');
		$desigcolorsize = get_option('desig_size');
		$desigheadFont = get_option('desigfont_family');
		$desigfontcolor = get_option('desig_fontcolor');
		$desigfontstyle = get_option('desig_fontstyle');
		$desigfontweight = get_option('desig_fontweight');
		
		?>
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $headFont; ?>">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $contentheadFont; ?>">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $personheadFont; ?>">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $desigheadFont; ?>">
		<style>
		.newHeadingColor
		{
			background-color: <?php echo $color; ?>  !important;
			background-image: none !important;
			color:<?php echo $fontcolor; ?>;			
			font-family: '<?php echo $headFont; ?>', serif;
			font-style: <?php echo $fontstyle; ?>;
			font-weight: <?php echo $fontweight; ?>;
		}
		
		.heading_accordion
		
		{
			padding:5px;
			padding-left:10px;
			border:1px solid gray;
		}
		
		.content_testi
		{
			display:block;
			float:left;
			width:100%;
			
			background-color: <?php echo $contentcolor; ?>  !important;
			background-image: none !important;
			color:<?php echo $contentfontcolor; ?>;			
			font-family: '<?php echo $contentheadFont; ?>', serif;
			font-style: <?php echo $contentfontstyle; ?>;
			font-weight: <?php echo $contentfontweight; ?>;
			font-size: <?php echo $contentcolorsize; ?>;
			
		}
		
		.content_testi img
		{
			margin-top:0px;
		}
		
		.testi_by
		{
			float:right;
		}
		.testi_by_name
		{
		
			background-color: <?php echo $personcolor; ?>  !important;
			background-image: none !important;
			color:<?php echo $personfontcolor; ?>;			
			font-family: '<?php echo $personheadFont; ?>', serif;
			font-style: <?php echo $personfontstyle; ?>;
			font-weight: <?php echo $personfontweight; ?>;
			font-size: <?php echo $personcolorsize; ?>;
			
		}
		.testi_by_desig
		{
			background-color: <?php echo $desigcolor; ?>  !important;
			background-image: none !important;
			color:<?php echo $desigfontcolor; ?>;			
			font-family: '<?php echo $desigheadFont; ?>', serif;
			font-style: <?php echo $desigfontstyle; ?>;
			font-weight: <?php echo $desigfontweight; ?>;
			font-size: <?php echo $desigcolorsize; ?>;
		}
		
		.testi_box_single
		{
			overflow:hidden;
		}
		
		</style>
		<?php 
		
		
		
		
				$args =  array(
					'post_type' => 'asp_testi',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'p' => "".$atts['id']."",
					'posts_per_page' => 1
					
				);
	// WP_Query
	$heading = get_option('multifaq_htype');
	$loop = new WP_Query($args);
	 while ( $loop->have_posts() ) : $loop->the_post(); 
	 if($color !='')
	 {
		 echo '<div class="testi_box_single"><'.$heading.' class="singleHeading newHeadingColor">'.get_the_title().'</'.$heading.'>';
		 }
		 
	else
	{
		echo '<div class="testi_box_single"><'.$heading.' class="singleHeading">'.get_the_title().'</'.$heading.'>';
		}
			echo "<div class='content_testi'>";			
				the_content();
			echo "</div>";
			echo "<div class='testi_by'>";
				echo "<span class='testi_by_name'>".get_post_meta( $loop->post->ID, 'testi_by_name', true )."</span> "; 
				echo "<span class='testi_by_desig'>".get_post_meta( $loop->post->ID, 'testi_by_desig', true )."</span>";
			echo "</div></div>";

				endwhile; // end of the loop.
	wp_reset_query();
}


//All FAQs list Shortcode intiailizing
add_shortcode('list_faq','list_asptesti_shortcode');
/**
*intializing list shortcode function
*/
function list_asptesti_shortcode($atts)
{
		?>
		<style>
			
			.content_testi_widget img
			{
				width:33%;
				<?php
					if($atts['displayimg'] == 'YES')
					{
						echo 'display:block;';
					}
					else
					{
						echo 'display:none;';
					}
				?>
			}
			
			.testi_widget_box
			{
				display:none;
			}
			
			.testi_widget_box_active
			{
				display:block;
			}
			.testi_by_name_widget
			{
				font-weight:bold;
				font-style:italic;
			}
			<?php echo $atts['custcss'];?>
			
			
		</style>

		
		
		<?php
		
		wp_enqueue_script("jquery");

		wp_enqueue_script("front_testi_js",plugins_url("/smart-testimonials/inc/js/custom.js"), __FILE__);
		
		if(isset($atts['cat_name']) && $atts['cat_name']!='')
				$args =  array(
					'post_type' => 'asp_testi',
					'orderby' => 'menu_order',
					'p__in' => "".$atts['limit']."",
					'order' => 'ASC'
					
				);
		else
				$args =  array(
					'post_type' => 'asp_testi',
					'orderby' => 'menu_order',
					'order' => 'ASC'	
				);
	// WP_Query 
	$heading = get_option('multifaq_htype');
	$loop = new WP_Query($args);
	$count = 1;
	 while ( $loop->have_posts() ) : $loop->the_post(); 
				
				if($count == 1)
				{				
					echo '<div class="testi_widget_box_active" id="testi_widget_box'.$count.'">';	
				}
				else
				{
					echo '<div class="testi_widget_box" id="testi_widget_box'.$count.'">';
				}
					echo '<'.$heading.' class="singleHeading_widget">'.get_the_title().'</'.$heading.'><br />';
					echo "<div class='content_testi_widget'>";			
					echo get_the_content();
					echo "</div>";
					
					echo "<br />";
					
					echo "<div class='testi_by_widget'>";
						echo "<b>By:</b> <span class='testi_by_name_widget'>".get_post_meta( $loop->post->ID, 'testi_by_name', true )."</span> "; 
						echo "<span class='testi_by_desig_widget'>(".get_post_meta( $loop->post->ID, 'testi_by_desig', true ).")</span>";
					echo "</div>";
				echo '</div>';
		$count++;
				endwhile; // end of the loop.
				
	wp_reset_query();
}
?>