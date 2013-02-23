<?php
/*
Plugin Name: Hot Newsflash
Plugin URI: http://www.hotwptemplates.com/
Description: The "HOT Newsflash" plugin is a fully configurable, featured articles rotator, based on jQuery.
Author: HOT WordPress Themes
Author URI: http://www.hotwptemplates.com
Version: 1.0
Tags: content, widget, jquery
License: GNU/GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'hot_newsflash_load_widgets' );
add_action('admin_init', 'hot_newsflash_textdomain');
/**
 * Register our widget.
 * 'HotEffectsRotator' is the widget class used below.
 *
 * @since 0.1
 */
function hot_newsflash_load_widgets() {
	register_widget( 'HotNewsflash' );
}

function hot_newsflash_textdomain() {
	load_plugin_textdomain('hot_newsflash', false, dirname(plugin_basename(__FILE__) ) . '/languages');
}
	
/**
 * HotNewsflash Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */


 
class HotNewsflash extends WP_Widget {
     
	/**
	 * Widget setup.
	 */

	function GetDefaults()
	{
	  return array(   
				//////////////////////////////////////
				 'enablejQuery' => '1'
				,'enablejQueryUI' => '1'
				,'noConflictMode' => '0'
				,'moduleWidth' => '600'
				,'moduleBackground' => '#ffffff'
				,'borderWidth' => '0'
				,'borderColor' => '#000000'
				,'tabNumber' => '4'
				,'readMore' => '1'
				,'readMoreText' => 'Full story'
				,'speed' => '5000'
				,'animationDuration' => '600'
				,'headingTextColor' => '#000000'
				,'mainTextColor' => '#000000'
				,'tabWidth' => '150'
				,'tabBgColor' => '#ffffff'
				,'tabBgColorHover' => '#f2f2f2'
				,'tabBgColorActive' => '#000000'
				,'tabFontColor' => '#000000'
				,'tabFontColorHover' => '#000000'
				,'tabFontColorActive' => '#ffffff'
				,'tabDelimiterColor' => '#cccccc'
				,'tabMultiline' => '1'
				,'imageWidth' => '254'
				,'imageHeight' => '169'
				,'imageLink' => '1'
				,'heading1' => ''
				,'link1' => ''
				,'info1' => ''
				,'image1' => plugins_url('/images/demo/picture1.jpg', __FILE__)
				,'heading2' => ''
				,'link2' => ''
				,'info2' => ''
				,'image2' => plugins_url('/images/demo/picture2.jpg', __FILE__)
				,'heading3' => ''
				,'link3' => ''
				,'info3' => ''
				,'image3' => plugins_url('/images/demo/picture3.jpg', __FILE__)
				,'heading4' => ''
				,'link4' => ''
				,'info4' => ''
				,'image4' => plugins_url('/images/demo/picture4.jpg', __FILE__)
				,'heading5' => ''
				,'link5' => ''
				,'info5' => ''
				,'image5' => plugins_url('/images/demo/picture5.jpg', __FILE__)
			 );
	}
	 
	function HotNewsflash() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Hot_newsflash', 'description' => __('Hot Newsflash', 'hot_newsflash') );

		/* Widget control settings. */
		$control_ops = array(  'id_base' => 'hot-newsflash' );

		/* Create the widget. */
		$this->WP_Widget( 'hot-newsflash', __('Hot Newsflash', 'hot_newsflash'), $widget_ops, $control_ops );
		
    	add_action('wp_print_styles', array( $this, 'HotNewsflash_styles'),12);
		add_action('wp_head', array( $this, 'HotNewsflash_inline_scripts_and_styles'),13);
	    add_action('admin_init', array( $this,'admin_utils'));
    }

	function admin_utils(){
			wp_enqueue_script( 'jquery-colorpicker', plugins_url('/js/jscolor.js', __FILE__),array('jquery'),'1.0.0');
	        wp_enqueue_script( 'jquery-hotutil', plugins_url('/js/hotutil.js', __FILE__),array('jquery'),'1.0.0');
			wp_enqueue_style( 'hot-newsflash-style', plugins_url('/style.css', __FILE__));
	}
	
	function HotNewsflash_styles(){
	   wp_enqueue_style( 'hot-newsflash-style', plugins_url('/style.css', __FILE__));
	   $all_options = parent::get_settings();
	   
	   foreach ($all_options as $key => $value){
	    $options = $all_options[$key];
		if(isset($options['enablejQuery'])){
			if($options['enablejQuery']){
			  wp_enqueue_script( 'jquery', plugins_url('/js/jquery.min.js', __FILE__),false,'1.8.3');
			}
		}
		if(isset($options['enablejQueryUI'])){
			if($options['enablejQueryUI']){
			  wp_enqueue_script( 'jquery', plugins_url('/js/jquery-ui.min.js', __FILE__),array('jquery'),'1.9.2');
			}
		}
	  }
	}
	
	function ToColor($val){
	  if(strcasecmp($val,'transparent') == 0) return $val;
	  $rval = str_replace('#','',$val);
	  return '#'.$rval;
	}
	
	function HotNewsflash_inline_scripts_and_styles(){
	   // MULTIPLE WIDGETS ON PAGE ARE SUPPORTED !!!
	   $all_options = parent::get_settings();
	   $echo_noconflict = false;

	   	echo '
		    <script type="text/javascript" src="'.plugins_url().'/hot_newsflash/js/jquery-ui-tabs-rotate.js">	
			</script>
		';
	   
	   echo '<style type="text/css">';
	   echo '/*HOT NEWSFLASH INLINE STYLES START*/';
	   foreach ($all_options as $key => $value){
	    $options = $all_options[$key];
		if(!isset($options['tabWidth'])) continue;
		if(!$options['tabWidth'])continue;
		
		if(!$echo_noconflict && $options['noConflictMode'] )
			$echo_noconflict = true;


		$imageHeight = $options['imageHeight'];
		$tabNumber = $options['tabNumber'];
		$tabHeight = $imageHeight / $tabNumber;

		$infoWidth = $options['moduleWidth'] - $options['tabWidth'] - $options['imageWidth'] - 10;
		
		echo '
				#featured-'.$key.' ul.ui-tabs-nav li, #featured-'.$key.' li.ui-tabs-nav-item a { 
					background:'.$this->ToColor($options['tabBgColor']).';
					color:'.$this->ToColor($options['tabFontColor']).';
					font-weight:bold;
				}

				#featured-'.$key.' li.ui-tabs-nav-item a:hover { 
					background:'.$this->ToColor($options['tabBgColorHover']).'; 
					color:'.$this->ToColor($options['tabFontColorHover']).';
				}

				#featured-'.$key.' ul.ui-tabs-nav li.ui-tabs-active a { 
					background:'.$this->ToColor($options['tabBgColorActive']).';
					color:'.$this->ToColor($options['tabFontColorActive']).';
				}

				#featured-'.$key.' { 
					width:'.$options['moduleWidth'].'px;
					position:relative; 
					border:'.$options['borderWidth'].'px solid '.$this->ToColor($options['borderColor']).'; 
					height:'.$options['imageHeight'].'px;
					background: '.$this->ToColor($options['moduleBackground']).';
				}

				#featured-'.$key.' ul.ui-tabs-nav { 
					width:'.$options['tabWidth'].'px;
				}

				#featured-'.$key.' li.ui-tabs-nav-item a { 
					height:'.floor($tabHeight - 1).'px;
					line-height:'.floor($tabHeight - 1).'px;
					border-bottom:1px solid '.$this->ToColor($options['tabDelimiterColor']).';
				}

				#featured-'.$key.' .ui-tabs-panel .infotext { 
					position:absolute; 
					top:0;
					left:'.$options['imageWidth'].'px;
				}

				#featured-'.$key.' .infotext {
					width:'.$infoWidth.'px;
					height:'.$options['imageHeight'].'px;
					overflow:hidden;
				}

				#featured-'.$key.' .infotext, #featured-'.$key.' .infotext p, #featured-'.$key.' .infotext div, #featured-'.$key.' .infotext tr {
					color:'.$this->ToColor($options['mainTextColor']).';
				}

				#featured-'.$key.' div.infotext h2 a {
					color:'.$this->ToColor($options['headingTextColor']).';
				}

	   ';
	   }
	 
	   echo '
	   /*HOT NEWSFLASH INLINE STYLES END*/
	   </style>';
	   
	   if($echo_noconflict){
	     echo '
		    <script type="text/javascript">
				jQuery.noConflict();
			</script>
		 ';
	   }
	}

	
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
	   extract( $args );
       echo $before_widget;
       //--------------------------------------------------------------------------------------------------------------------------------------------
	   //--------------------------------------------------------------------------------------------------------------------------------------------
	 
        $defaults = $this->GetDefaults();
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$tabNumber = $instance['tabNumber'];
		
		?>
		
		
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#featured-<?php echo $this->number; ?>").tabs({fx:{opacity: "toggle",duration: '<?php echo $instance['animationDuration']; ?>' }}).tabs("rotate", <?php echo $instance['speed']; ?>,true);
			});
		</script>

		<div id="featured-<?php echo $this->number; ?>" class="hjt_newsflash" >
			<!-- Tabs -->
			<ul class="ui-tabs-nav hjt_newsflash_nav">
				<?php for ($loop = 1; $loop <= $tabNumber; $loop += 1) if($instance['image'.$loop]){ ?>
				<li class="ui-tabs-nav-item" id="nav-fragment-<?php echo $loop; ?>"><a href="#fragment-<?php echo $loop; ?>"><?php echo $instance['heading'.$loop]; ?></a></li>
				<?php } ?>
			</ul>

			<!-- Content -->
			<?php for ($loop = 1; $loop <= $tabNumber; $loop += 1) if($instance['image'.$loop]) { ?>
			<div id="fragment-<?php echo $loop; ?>" class="ui-tabs-panel<?php if ($loop != 1) { ?> ui-tabs-hide<?php } ?>">
				<?php if($instance['imageLink']) { ?><a href="<?php echo $instance['link'.$loop]; ?>"><?php } ?><img src="<?php echo $instance['image'.$loop]; ?>" alt="" width="<?php echo $instance['imageWidth']; ?>" height="<?php echo $instance['imageHeight']; ?>" /><?php if($instance['imageLink']) { ?></a><?php } ?>
				<div class="infotext">
					<h2><a href="<?php echo $instance['link'.$loop]; ?>"><?php echo $instance['heading'.$loop]; ?></a></h2>
					<p><?php echo $instance['info'.$loop]; ?></p>
					<?php if ($instance['readMore']) { ?><p><a href="<?php echo $instance['link'.$loop]; ?>" class="readon"><?php echo $instance['readMoreText']; ?></a></p><?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
		
	   <?php
       //--------------------------------------------------------------------------------------------------------------------------------------------
	   //--------------------------------------------------------------------------------------------------------------------------------------------
	   echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    	
		foreach($new_instance as $key => $option)
		{
		  $instance[$key]     = $new_instance[$key];
		} 
		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
	    $defaults = $this->GetDefaults();
		$instance = wp_parse_args( (array) $instance, $defaults );  ?>

		<!-- Widget Title: Text Input -->
	
<div style="width:756px;margin-left:-530px;background:white;border:Solid 1px Gray;border-top:Solid 4px Gray;" class="hot_newsflash_property_table_<?php echo $this->number ;?>" >
				<table>
				<tr>
				<td>
				
				<fieldset   >
						<legend>
							<h3>
								System Settings
                			</h3>
						</legend>
					
		<table>
		
							<tr>
							<td>
							<?php _e('Load jQuery','hot_newsflash'); ?>?
							</td>
							<td>
							<select class="select" id="<?php echo $this->get_field_id( 'enablejQuery' ); ?>" name="<?php echo $this->get_field_name( 'enablejQuery' ); ?>" >
							
								<option value="0"  >
								<?php _e('Disable', 'hot_newsflash'); ?>
								</option>
							
								<option value="1"  >
								<?php _e('Enable', 'hot_newsflash'); ?>
								</option>
							
							</select>
							<script type="text/javascript">
							document.getElementById('<?php echo $this->get_field_id( 'enablejQuery' ); ?>').value = "<?php echo $instance['enablejQuery']; ?>";
							</script>
							</td>
							</tr>
						
						    <tr>
							<td>
							<?php _e('Load jQuery UI','hot_newsflash'); ?>?
							</td>
							<td>
							<select class="select" id="<?php echo $this->get_field_id( 'enablejQueryUI' ); ?>" name="<?php echo $this->get_field_name( 'enablejQueryUI' ); ?>" >
							
								<option value="0"  >
								<?php _e('Disable', 'hot_newsflash'); ?>
								</option>
							
								<option value="1"  >
								<?php _e('Enable', 'hot_newsflash'); ?>
								</option>
							
							</select>
							<script type="text/javascript">
							document.getElementById('<?php echo $this->get_field_id( 'enablejQueryUI' ); ?>').value = "<?php echo $instance['enablejQueryUI']; ?>";
							</script>
							</td>
							</tr>
					
							<tr>
							<td>
							<?php _e('Enable no conflict mode','hot_newsflash'); ?>?
							</td>
							<td>
							<select class="select" id="<?php echo $this->get_field_id( 'noConflictMode' ); ?>" name="<?php echo $this->get_field_name( 'noConflictMode' ); ?>" >
							
								<option value="0"  >
								<?php _e('Disable', 'hot_newsflash'); ?>
								</option>
							
								<option value="1"  >
								<?php _e('Enable', 'hot_newsflash'); ?>
								</option>
							
							</select>
							<script type="text/javascript">
							document.getElementById('<?php echo $this->get_field_id( 'noConflictMode' ); ?>').value = "<?php echo $instance['noConflictMode']; ?>";
							</script>
							</td>
							</tr>
						
		</table>
	
					</fieldset>
		
					<fieldset   >
						<legend>
							<h3>
								Widget Properties
                			</h3>
						</legend>
					
		<table>
		
							<tr>
							<td>
							<?php _e('Widget Width','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'moduleWidth' ); ?>" id="<?php echo $this->get_field_id( 'moduleWidth' ); ?>" value="<?php echo $instance['moduleWidth']; ?>" class="numeric" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Background Color','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'moduleBackground' ); ?>" id="<?php echo $this->get_field_id( 'moduleBackground' ); ?>" value="<?php echo $instance['moduleBackground']; ?>" class="color" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Border Width','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'borderWidth' ); ?>" id="<?php echo $this->get_field_id( 'borderWidth' ); ?>" value="<?php echo $instance['borderWidth']; ?>" class="numeric" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Border Color','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'borderColor' ); ?>" id="<?php echo $this->get_field_id( 'borderColor' ); ?>" value="<?php echo $instance['borderColor']; ?>" class="color" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Number of articles','hot_newsflash'); ?>?
							</td>
							<td>
							<select class="select" id="<?php echo $this->get_field_id( 'tabNumber' ); ?>" name="<?php echo $this->get_field_name( 'tabNumber' ); ?>" >
							
								<option value="1"  >
								<?php _e('1', 'hot_newsflash'); ?>
								</option>
							
								<option value="2"  >
								<?php _e('2', 'hot_newsflash'); ?>
								</option>
							
								<option value="3"  >
								<?php _e('3', 'hot_newsflash'); ?>
								</option>
							
								<option value="4"  >
								<?php _e('4', 'hot_newsflash'); ?>
								</option>
							
								<option value="5"  >
								<?php _e('5', 'hot_newsflash'); ?>
								</option>
							
							</select>
							<script type="text/javascript">
							document.getElementById('<?php echo $this->get_field_id( 'tabNumber' ); ?>').value = "<?php echo $instance['tabNumber']; ?>";
							</script>
							</td>
							</tr>	

							<tr>
							<td>
							<?php _e('Display read more links','hot_newsflash'); ?>?
							</td>
							<td>
							<select class="select" id="<?php echo $this->get_field_id( 'readMore' ); ?>" name="<?php echo $this->get_field_name( 'readMore' ); ?>" >
							
								<option value="0"  >
								<?php _e('No', 'hot_newsflash'); ?>
								</option>
							
								<option value="1"  >
								<?php _e('Yes', 'hot_newsflash'); ?>
								</option>
							
							</select>
							<script type="text/javascript">
							document.getElementById('<?php echo $this->get_field_id( 'readMore' ); ?>').value = "<?php echo $instance['readMore']; ?>";
							</script>
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Read more link text','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'readMoreText' ); ?>"   id="<?php echo $this->get_field_id( 'readMoreText' ); ?>"  value="<?php echo $instance['readMoreText']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Rotation speed','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'speed' ); ?>" id="<?php echo $this->get_field_id( 'speed' ); ?>" value="<?php echo $instance['speed']; ?>" class="numeric" />
							</td>
							</tr>
							
							<tr>
							<td>
							<?php _e('Animation duration','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'animationDuration' ); ?>" id="<?php echo $this->get_field_id( 'animationDuration' ); ?>" value="<?php echo $instance['animationDuration']; ?>" class="numeric" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Heading text color','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'headingTextColor' ); ?>" id="<?php echo $this->get_field_id( 'headingTextColor' ); ?>" value="<?php echo $instance['headingTextColor']; ?>" class="color" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Text color','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'mainTextColor' ); ?>" id="<?php echo $this->get_field_id( 'mainTextColor' ); ?>" value="<?php echo $instance['mainTextColor']; ?>" class="color" />
							</td>
							</tr>
						
		</table>
	
					</fieldset>
		
					<fieldset  >
						<legend>
							<h3>
								Tabs Properties
                			</h3>
						</legend>
					
		<table>
		
							<tr>
							<td>
							<?php _e('Tabs width','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'tabWidth' ); ?>" id="<?php echo $this->get_field_id( 'tabWidth' ); ?>" value="<?php echo $instance['tabWidth']; ?>" class="numeric" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Tabs background color','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'tabBgColor' ); ?>" id="<?php echo $this->get_field_id( 'tabBgColor' ); ?>" value="<?php echo $instance['tabBgColor']; ?>" class="color" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Tabs background color on hover','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'tabBgColorHover' ); ?>" id="<?php echo $this->get_field_id( 'tabBgColorHover' ); ?>" value="<?php echo $instance['tabBgColorHover']; ?>" class="color" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Active tab background color','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'tabBgColorActive' ); ?>" id="<?php echo $this->get_field_id( 'tabBgColorActive' ); ?>" value="<?php echo $instance['tabBgColorActive']; ?>" class="color" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Tabs font color','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'tabFontColor' ); ?>" id="<?php echo $this->get_field_id( 'tabFontColor' ); ?>" value="<?php echo $instance['tabFontColor']; ?>" class="color" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Tabs font color on hover','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'tabFontColorHover' ); ?>" id="<?php echo $this->get_field_id( 'tabFontColorHover' ); ?>" value="<?php echo $instance['tabFontColorHover']; ?>" class="color" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Active tab font color','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'tabFontColorActive' ); ?>" id="<?php echo $this->get_field_id( 'tabFontColorActive' ); ?>" value="<?php echo $instance['tabFontColorActive']; ?>" class="color" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Tabs delimiter color','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'tabDelimiterColor' ); ?>" id="<?php echo $this->get_field_id( 'tabDelimiterColor' ); ?>" value="<?php echo $instance['tabDelimiterColor']; ?>" class="color" />
							</td>
							</tr>
						

							<tr>
							<td>
							<?php _e('Tabs text in one line','hot_newsflash'); ?>?
							</td>
							<td>
							<select class="select" id="<?php echo $this->get_field_id( 'tabMultiline' ); ?>" name="<?php echo $this->get_field_name( 'tabMultiline' ); ?>" >
							
								<option value="0"  >
								<?php _e('No', 'hot_newsflash'); ?>
								</option>
							
								<option value="1"  >
								<?php _e('Yes', 'hot_newsflash'); ?>
								</option>
							
							</select>
							<script type="text/javascript">
							document.getElementById('<?php echo $this->get_field_id( 'tabMultiline' ); ?>').value = "<?php echo $instance['tabMultiline']; ?>";
							</script>
							</td>
							</tr>
						
		</table>
	
					</fieldset>
		
					<fieldset >
						<legend>
							<h3>
								Image Properties
                			</h3>
						</legend>
					
		<table>
		
							<tr>
							<td>
							<?php _e('Width of the images','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'imageWidth' ); ?>" id="<?php echo $this->get_field_id( 'imageWidth' ); ?>" value="<?php echo $instance['imageWidth']; ?>" class="numeric" />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Height of the images','hot_newsflash'); ?>
							</td>
							<td>
							<input type="text" name="<?php echo $this->get_field_name( 'imageHeight' ); ?>" id="<?php echo $this->get_field_id( 'imageHeight' ); ?>" value="<?php echo $instance['imageHeight']; ?>" class="numeric" />
							</td>
							</tr>
						

							<tr>
							<td>
							<?php _e('Linked images','hot_newsflash'); ?>?
							</td>
							<td>
							<select class="select" id="<?php echo $this->get_field_id( 'imageLink' ); ?>" name="<?php echo $this->get_field_name( 'imageLink' ); ?>" >
							
								<option value="0"  >
								<?php _e('No', 'hot_newsflash'); ?>
								</option>
							
								<option value="1"  >
								<?php _e('Yes', 'hot_newsflash'); ?>
								</option>
							
							</select>
							<script type="text/javascript">
							document.getElementById('<?php echo $this->get_field_id( 'imageLink' ); ?>').value = "<?php echo $instance['imageLink']; ?>";
							</script>
							</td>
							</tr>
						
		</table>
	
					</fieldset>
					
					</td>
					<td style="padding-left:50px;" >
		            <span style="font-size:10px;font-weight:bold;" class="note">(**If "Article image" field is empty, slot will be inactive)</span>
					<fieldset >
						<legend>
							<h3>
							   Article 1
                			</h3>
						</legend>
					
		<table>
		
							<tr>
							<td>
							<?php _e('Heading of article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'heading1' ); ?>"   id="<?php echo $this->get_field_id( 'heading1' ); ?>"  value="<?php echo $instance['heading1']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Link to full article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'link1' ); ?>"   id="<?php echo $this->get_field_id( 'link1' ); ?>"  value="<?php echo $instance['link1']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article info text','hot_newsflash'); ?>
							</td>
							<td>
							<textarea type="text" name="<?php echo $this->get_field_name( 'info1' ); ?>"	id="<?php echo $this->get_field_id( 'info1'  ); ?>" ><?php echo $instance['info1' ]; ?></textarea>
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article image','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'image1' ); ?>"   id="<?php echo $this->get_field_id( 'image1' ); ?>"  value="<?php echo $instance['image1']; ?>"  />
							</td>
							</tr>
						
		</table>
	
					</fieldset>
		
					<fieldset  >
						<legend>
							<h3>
								Article 2
                			</h3>
						</legend>
					
		<table>
		
							<tr>
							<td>
							<?php _e('Heading of article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'heading2' ); ?>"   id="<?php echo $this->get_field_id( 'heading2' ); ?>"  value="<?php echo $instance['heading2']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Link to full article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'link2' ); ?>"   id="<?php echo $this->get_field_id( 'link2' ); ?>"  value="<?php echo $instance['link2']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article info text','hot_newsflash'); ?>
							</td>
							<td>
							<textarea type="text" name="<?php echo $this->get_field_name( 'info2' ); ?>"	id="<?php echo $this->get_field_id( 'info2'  ); ?>" ><?php echo $instance['info2' ]; ?></textarea>
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article image','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'image2' ); ?>"   id="<?php echo $this->get_field_id( 'image2' ); ?>"  value="<?php echo $instance['image2']; ?>"  />
							</td>
							</tr>
						
		</table>
	
					</fieldset>
		
					<fieldset >
						<legend>
							<h3>
								Article 3
                			</h3>
						</legend>
					
		<table>
		
							<tr>
							<td>
							<?php _e('Heading of article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'heading3' ); ?>"   id="<?php echo $this->get_field_id( 'heading3' ); ?>"  value="<?php echo $instance['heading3']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Link to full article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'link3' ); ?>"   id="<?php echo $this->get_field_id( 'link3' ); ?>"  value="<?php echo $instance['link3']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article info text','hot_newsflash'); ?>
							</td>
							<td>
							<textarea type="text" name="<?php echo $this->get_field_name( 'info3' ); ?>"	id="<?php echo $this->get_field_id( 'info3'  ); ?>" ><?php echo $instance['info3' ]; ?></textarea>
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article image','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'image3' ); ?>"   id="<?php echo $this->get_field_id( 'image3' ); ?>"  value="<?php echo $instance['image3']; ?>"  />
							</td>
							</tr>
						
		</table>
	
					</fieldset>
		
					<fieldset>
						<legend>
							<h3>
								Article 4
                			</h3>
						</legend>
					
		<table>
		
							<tr>
							<td>
							<?php _e('Heading of article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'heading4' ); ?>"   id="<?php echo $this->get_field_id( 'heading4' ); ?>"  value="<?php echo $instance['heading4']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Link to full article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'link4' ); ?>"   id="<?php echo $this->get_field_id( 'link4' ); ?>"  value="<?php echo $instance['link4']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article info text','hot_newsflash'); ?>
							</td>
							<td>
							<textarea type="text" name="<?php echo $this->get_field_name( 'info4' ); ?>"	id="<?php echo $this->get_field_id( 'info4'  ); ?>" ><?php echo $instance['info4' ]; ?></textarea>
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article image','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'image4' ); ?>"   id="<?php echo $this->get_field_id( 'image4' ); ?>"  value="<?php echo $instance['image4']; ?>"  />
							</td>
							</tr>
						
		</table>
	
					</fieldset>
		
					<fieldset >
						<legend>
							<h3>
								Article 5
                			</h3>
						</legend>
					
		<table>
		
							<tr>
							<td>
							<?php _e('Heading of article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'heading5' ); ?>"   id="<?php echo $this->get_field_id( 'heading5' ); ?>"  value="<?php echo $instance['heading5']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Link to full article','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'link5' ); ?>"   id="<?php echo $this->get_field_id( 'link5' ); ?>"  value="<?php echo $instance['link5']; ?>"  />
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article info text','hot_newsflash'); ?>
							</td>
							<td>
							<textarea type="text" name="<?php echo $this->get_field_name( 'info5' ); ?>"	id="<?php echo $this->get_field_id( 'info5'  ); ?>" ><?php echo $instance['info5' ]; ?></textarea>
							</td>
							</tr>
						
							<tr>
							<td>
							<?php _e('Article image','hot_newsflash'); ?>
							</td>
							<td>
							<input  type="text" name="<?php echo $this->get_field_name( 'image5' ); ?>"   id="<?php echo $this->get_field_id( 'image5' ); ?>"  value="<?php echo $instance['image5']; ?>"  />
							</td>
							</tr>
						
		</table>
	
					</fieldset>
					
					</td>
					</tr>
					</table>
		
</div>

<script type="text/javascript" >
	
	try{
		jscolor.init();
	}catch(exc){}

	try{
		HWT_Utilise('.hot_newsflash_property_table_<?php echo $this->number ;?>');
	}catch(exc){
	}
	
</script>
	<?php  
	}
}

?>