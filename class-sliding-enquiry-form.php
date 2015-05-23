<?php
/**
 * Sliding Enquiry Form Class
 *
 * @package   Sliding Enquiry Form
 * @author    Jaimin Suthar
 * @license   Open Source 
 * @demo link  http://smoexpert.in/
 */

/**
 * @package Sliding Enquiry Form
 * @author  James
 */
 

class Sliding_Enquiry {
	
	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 * 
	 * @since 1.0
	 *
	 * @var string
	 */
	const VERSION = '1.0';

	/**
	 * Unique identifier for plugin.
	 *
	 * @since 1.0
	 * 
	 * @var string
	 */
	protected $plugin_slug = 'sliding_enquiry';

	/**
	 * Instance of this class.
	 *
	 * @since 1.0
	 * 
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Stores enquiry active status
	 *
	 * @since 1.2
	 * 
	 * @var string
	 */
	protected $enquiry_active;

	/**
	 * Stores enquiry title option
	 *
	 * @since 1.0
	 * 
	 * @var string
	 */
	protected $enquiry_title;

	/**
	 * Stores enquiry title Colour
	 *
	 * @since 1.1
	 * 
	 * @var string
	 */
	protected $enquiry_title_color;

	/**
	 * Stores enquiry icon url
	 *
	 * @since 1.0
	 * 
	 * @var string
	 */
	protected $enquiry_title_image;

	
	/**
	 * Stores enquiry email id
	 *
	 * @since 1.0
	 * 
	 * @var string
	 */
	protected $enquiry_emailid;
	
	/**
	 * Stores enquiry header color
	 *
	 * @since 1.0
	 * 
	 * @var string
	 */
	protected $enquiry_header_color;

	/**
	 * Stores enquiry header border color
	 *
	 * @since 1.1
	 * 
	 * @var string
	 */
	protected $enquiry_header_border_color;

	/**
	 * Stores enquiry place
	 *
	 * @since 1.0
	 * 
	 * @var string
	 */
	protected $enquiry_place;

	/**
	 * Stores enquiry top margin in percentage
	 *
	 * @since 1.1
	 * 
	 * @var string
	 */
	protected $enquiry_top_margin;
	
	/**
	 * Stores enquiry content
	 *
	 * @since 1.0
	 * 
	 * @var string
	 */
	protected $enquiry_content;

	/**
	 * Stores enquiry option for show in homepage
	 *
	 * @since 1.1
	 * 
	 * @var string
	 */
	protected $show_on_homepage;	

	/**
	 * Stores enquiry option for show in posts
	 *
	 * @since 1.1
	 * 
	 * @var string
	 */
	protected $show_on_posts;

	/**
	 * Stores enquiry option for show in pages
	 *
	 * @since 1.1
	 * 
	 * @var string
	 */
	protected $show_on_pages;

	/**
	 * Initialize the plugin by loading public scripts and styels or admin page
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->enquiry_active = get_option( 'sp_enquiry_active' );
		$this->enquiry_title  = get_option( 'sp_enquiry_title' );
		$this->enquiry_title_color  = get_option( 'sp_enquiry_title_color' );
		$this->enquiry_title_image = get_option( 'sp_enquiry_title_image' );
		$this->enquiry_emailid = get_option( 'sp_enquiry_emailid' );
		$this->enquiry_header_color = get_option( 'sp_enquiry_header_color' );
		$this->enquiry_header_border_color = get_option( 'sp_enquiry_header_border_color' );
		$this->enquiry_place = get_option( 'sp_enquiry_place' );
		$this->enquiry_top_margin = get_option( 'sp_enquiry_top_margin' );
		
		//$this->enquiry_content = get_option( 'sp_enquiry_content' );

		$this->show_on_homepage = get_option( 'sp_show_on_homepage' );
		$this->show_on_posts = get_option( 'sp_show_on_posts' );
		$this->show_on_pages = get_option( 'sp_show_on_pages' );
		if ( is_admin() ) {
			// Add the settings page and menu item.
			add_action( 'admin_menu', array( $this, 'plugin_admin_menu' ) );
			// Add an action link pointing to the settings page.
			$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
			add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
			
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		} else {

			add_action( 'wp', array( $this, 'load_sliding_enquiry' ) );
		}
	}

	public function load_sliding_enquiry () {
		
		$show_sliding_enquiry=false;
		if($this->enquiry_active)
		{
			if($this->show_on_homepage!=1 && $this->show_on_posts!=1 && $this->show_on_pages!=1 )
			{
				$show_sliding_enquiry=true;
					
			}
			else
			{				
				if( $this->show_on_homepage==1 && is_front_page() )
				{
					$show_sliding_enquiry=true;					
				}
				if( $this->show_on_posts==1 && ( is_single() || is_home() || is_archive() ) )
				{
					$show_sliding_enquiry=true;	
				}
				if( $this->show_on_pages==1 && is_page() )
				{
					$show_sliding_enquiry=true;
				}
			}
			if($show_sliding_enquiry)
			{
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'validation_scripts' ) );
				add_action( 'wp_head', array( $this, 'head_styles' ) );
				add_filter( 'wp_footer', array( $this, 'get_sliding_enquiry' ) );
				add_action( 'wp_footer', array( $this, 'footer_scripts' ) );
			}
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0
	 * 
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register the settings menu for this plugin into the WordPress Settings menu.
	 * 
	 * @since 1.0
	 */
	public function plugin_admin_menu() {
		add_options_page( __( 'Sliding Enquiry Settings', 'sliding-enquiry' ), __( 'Sliding Enquiry', 'sliding-enquiry' ), 'manage_options', $this->plugin_slug, array( $this, 'sliding_enquiry_options' ) );
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
	
		$screen = get_current_screen();
		if ( 'settings_page_'.$this->plugin_slug == $screen->id ) {			
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), Sliding_Enquiry::VERSION );
			wp_enqueue_media();        	
		}
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since  1.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		
		$screen = get_current_screen();		
		if ( 'settings_page_'.$this->plugin_slug == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug . '-admin-style', plugins_url( 'css/admin.css', __FILE__ ), Sliding_Enquiry::VERSION );
			wp_enqueue_style( 'wp-color-picker' );
		}		
	}

	/**
	 * Add settings action link to the plugins page.
	 * 
	 * @param array $links
	 *
	 * @since 1.0
	 *
	 * @return array Plugin settings links
	 */
	public function add_action_links( $links ) {
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);	
	}

	/**
	 * Render the settings page for this plugin.
	 * 
	 * @since 1.0
	 */
	public function sliding_enquiry_options() {
		if ( ! current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		if ( ! empty( $_POST ) && check_admin_referer( 'sliding_enquiry', 'save_sliding_enquiry' ) ) {


			

			//add or update Sliding Enquiry active stats
			if ( $this->enquiry_active !== false ) {
				update_option( 'sp_enquiry_active', $_POST['enquiry_active'] );
			} else {
				add_option( 'sp_enquiry_active', $_POST['enquiry_active'], null, 'no' );
			}

			//add or update Sliding Enquiry title options
			if ( $this->enquiry_title !== false ) {
				update_option( 'sp_enquiry_title', $_POST['enquiry_title'] );
			} else {
				add_option( 'sp_enquiry_title', $_POST['enquiry_title'], null, 'no' );
			}			
			//add or update Sliding Enquiry title colour since version 1.1
			if ( $this->enquiry_title_color !== false ) {
				update_option( 'sp_enquiry_title_color', $_POST['enquiry_title_color'] );
			} else {
				add_option( 'sp_enquiry_title_color', $_POST['enquiry_title_color'], null, 'no' );
			}
			//add or update Sliding Enquiry title icon
			if ( $this->enquiry_title_image !== false ) {
				update_option( 'sp_enquiry_title_image', $_POST['enquiry_title_image'] );
			} else {
				add_option( 'sp_enquiry_title_image', $_POST['enquiry_title_image'], null, 'no' );
			}
			//add or update Sliding Enquiry email id
			if( $this->enquiry_emailid !== false ) { 
				update_option( 'sp_enquiry_emailid', $_POST['enquiry_emailid'] );
			} else {
				add_option( 'sp_enquiry_emailid', $_POST['enquiry_emailid'], null, 'no' );
			}
			//add or update Sliding Enquiry header color
			if ( $this->enquiry_header_color !== false ) {
				update_option( 'sp_enquiry_header_color', $_POST['enquiry_header_color'] );
			} else {
				add_option( 'sp_enquiry_header_color', $_POST['enquiry_header_color'], null, 'no' );
			}
			
			//add or update Sliding Enquiry header border color since version 1.1
			if ( $this->enquiry_header_border_color !== false ) {
				update_option( 'sp_enquiry_header_border_color', $_POST['enquiry_header_border_color'] );
			} else {
				add_option( 'sp_enquiry_header_border_color', $_POST['enquiry_header_border_color'], null, 'no' );
			}

			//add or update Sliding Enquiry place
			if ( $this->enquiry_place !== false ) {
				update_option( 'sp_enquiry_place', $_POST['enquiry_place'] );
			} else {
				add_option( 'sp_enquiry_place', $_POST['enquiry_place'], null, 'no' );
			}
			//add or update Sliding Enquiry Top Margin when position is left or right included since 1.1
			if ( $this->enquiry_top_margin !== false ) {
				update_option( 'sp_enquiry_top_margin', $_POST['enquiry_top_margin'] );
			} else {
				add_option( 'sp_enquiry_top_margin', $_POST['enquiry_top_margin'], null, 'no' );
			}
			
			//add or update Sliding Enquiry content
			
			//if ( $this->enquiry_content !== false ) {				
			//	update_option( 'sp_enquiry_content', wp_unslash( $_POST['enquiry_content'] ) );
			//} else {
			//	add_option( 'sp_enquiry_content', wp_unslash( $_POST['enquiry_content'] ), null, 'no' );
			//}

			//add or update Sliding Enquiry option for show on homepage
			if ( $this->show_on_homepage !== false ) {				
				update_option( 'sp_show_on_homepage', wp_unslash( $_POST['show_on_homepage'] ) );
			} else {
				add_option( 'sp_show_on_homepage', wp_unslash( $_POST['show_on_homepage'] ), null, 'no' );
			}

			//add or update Sliding Enquiry option for show on posts
			if ( $this->show_on_posts !== false ) {				
				update_option( 'sp_show_on_posts', wp_unslash( $_POST['show_on_posts'] ) );
			} else {
				add_option( 'sp_show_on_posts', wp_unslash( $_POST['show_on_posts'] ), null, 'no' );
			}

			//add or update Sliding Enquiry option for show on pages
			if ( $this->show_on_pages !== false ) {				
				update_option( 'sp_show_on_pages', wp_unslash( $_POST['show_on_pages'] ) );
			} else {
				add_option( 'sp_show_on_pages', wp_unslash( $_POST['show_on_pages'] ), null, 'no' );
			}

			wp_redirect( admin_url( 'options-general.php?page='.$_GET['page'].'&updated=1' ) );
		}
		?>
		<div class="wrap">
			<h2><?php _e( 'Sliding Enquiry Settings', 'sliding-enquiry' );?></h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page='.$_GET['page'].'&noheader=true' ) ); ?>" enctype="multipart/form-data">
				<?php wp_nonce_field( 'sliding_enquiry', 'save_sliding_enquiry' ); ?>
				<div class="sticky_popup_form">
					<table class="form-table" width="100%">
						<tr>
							<th scope="row"></th>
							<td>								
								<input type="checkbox" name="enquiry_active" id="enquiry_active" value="1" <?php if($this->enquiry_active)  echo 'checked="checked"'; else '';?>>&nbsp;<label for="enquiry_active"><strong><?php _e( 'Enable', 'sliding-enquiry' );?></strong></label></td>
						</tr>
						<tr>
							<th scope="row"><label for="enquiry_title"><?php _e( 'Enquiry Title', 'sliding-enquiry' );?></label></th>
							<td><input type="text" name="enquiry_title" id="enquiry_title" maxlength="255" size="25" value="<?php echo $this->enquiry_title; ?>"></td>
						</tr>
						<tr>
							<th scope="row"><label for="enquiry_title_color"><?php _e( 'Enquiry Title Color', 'sliding-enquiry' );?></label></th>
							<td><input type="text" name="enquiry_title_color" id="enquiry_title_color" maxlength="255" size="25" value="<?php echo $this->enquiry_title_color; ?>"></td>
						</tr>
						<tr>
							<th scope="row"><label for="enquiry_title_image"><?php _e( 'Enquiry Title Right Side Icon', 'sliding-enquiry' );?></label></th>
							<td><input type="text" name="enquiry_title_image" id="enquiry_title_image" maxlength="255" size="25" value="<?php echo $this->enquiry_title_image; ?>"><input id="enquiry_title_image_button" class="button" type="button" value="Upload Image" />
	    					<br />Enter a URL or upload an image</td>
						</tr>
						<tr>
							<th scope="row"><label for="enquiry_title_image"><?php _e( 'Enquiry Mail Email Id', 'sliding-enquiry' );?></label></th>
							<td><input type="text" name="enquiry_emailid" id="enquiry_emailid" maxlength="255" size="25" value="<?php echo $this->enquiry_emailid; ?>">
	    					<br /></td>
						</tr>
						<tr>
							<th scope="row"><label for="enquiry_header_color"><?php _e( 'Enquiry Header Color', 'sliding-enquiry' );?></label></th>
							<td><input type="text" name="enquiry_header_color" id="enquiry_header_color" maxlength="255" size="25" value="<?php echo $this->enquiry_header_color; ?>"></td>
						</tr>
						<tr>
							<th scope="row"><label for="enquiry_header_border_color"><?php _e( 'Enquiry Header Border Color', 'sliding-enquiry' );?></label></th>
							<td><input type="text" name="enquiry_header_border_color" id="enquiry_header_border_color" maxlength="255" size="25" value="<?php echo $this->enquiry_header_border_color; ?>"></td>
						</tr>
						<tr>
							<th scope="row"><label for="enquiry_place"><?php _e( 'Enquiry Place', 'sliding-enquiry' );?></label></th>
							<td><select name="enquiry_place" id="enquiry_place">
							<?php foreach ( $this->get_popup_place() as $key => $value ): ?>
							<option value="<?php esc_attr_e( $key ); ?>"<?php esc_attr_e( $key == $this->enquiry_place ? ' selected="selected"' : '' ); ?>><?php esc_attr_e( $value ); ?></option>
							<?php endforeach;?>
						</select></td>
						</tr>
						
						<tr>
							<th scope="row"><label for="enquiry_top_margin"><?php _e( 'Enquiry Top Margin', 'sliding-enquiry' );?></label></th>
							<td><input type="number" name="enquiry_top_margin" id="enquiry_top_margin" maxlength="255" size="25" value="<?php echo $this->enquiry_top_margin; ?>">%<br>
								<small>Top margin is only included if Sliding Enquiry place Left or Right is selected. Please enter numeric value.</td>
						</tr>

						<tr>
							<th></th>
							<td>
								<table border="0">
									<tr>
										<td><input type="checkbox" name="show_on_homepage" value="1" <?php if($this->show_on_homepage==1) echo 'checked="checked"';?> ><label for="show_on_homepage"><?php _e( 'Show on Homepage', 'sliding-enquiry' );?></label><br><br>
										<input type="checkbox" name="show_on_posts" value="1" <?php if($this->show_on_posts==1) echo 'checked="checked"';?> ><label for="show_on_posts"><?php _e( 'Show on Posts', 'sliding-enquiry' );?></label>
										<br><br>
										<input type="checkbox" name="show_on_pages" value="1" <?php if($this->show_on_pages==1) echo 'checked="checked"';?> ><label for="show_on_pages"><?php _e( 'Show on Pages', 'sliding-enquiry' );?></label>
									</td>
									</tr>
								</table>
							</td>
						</tr>
						<?php  /*
						<tr>
							<th scope="row"><label for="enquiry_content"><?php _e( 'Enquiry Content', 'sliding-enquiry' );?><br></label><small><?php _e( 'you can add shortcode or html', 'sliding-enquiry' );?></small></th>
							<td></td>
						</tr>							
						<tr>
							<td colspan="2">
								<div style="100%;">
									<?php 							
									$args = array(
										'textarea_name' => 'enquiry_content',
									    'textarea_rows' => 10,
									    'editor_class'	=> 'sp_content',
									    'wpautop'		=> true,
									);
									wp_editor( $this->enquiry_content, 'enquiry_content', $args ); 
									?>
								</div>
							</td>
						</tr> */?>			
					</table>
					<p class="submit">
						<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ) ?>" />
					</p>
				</div>
			</form>
			<?php
			$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) );
			?>
			
			<?php /* Advertise 
			<p>
				<a href="http://wordpress.org/plugins/numix-post-slider/" target="_blank"><img src="<?php echo plugins_url( $plugin_basename ); ?>/images/numix_post_slider.png" /></a>
			</p>
			 */ ?>
		</div>
		<?php
	}	

	/**
	 * Returns list of Enquiry Place
	 * 
	 * @since 1.0
	 *
	 * @return array Enquiry Place
	 */
	public function get_popup_place() {
		return array(
				'right-bottom' => 'Right Bottom',
				'left-bottom' => 'Left Bottom',
				'top-left' => 'Top Left',
				'top-right' => 'Top Right',				
				'right' => 'Right',
				'left' => 'Left',				
			);
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since 1.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-style', plugins_url( 'css/sliding-enquiry.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-modernizr-script', plugins_url( 'js/modernizr.custom.js', __FILE__ ), array(), self::VERSION );		
	}
	public function validation_scripts() { 
		wp_enqueue_script( $this->plugin_slug . '-validation-script', plugins_url( 'js/validation.js', __FILE__ ), array(), self::VERSION );		
	}

	/*
	*  Generate Code
	*
	*
	*/ 
	public function generateCode(){
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < 6) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		
		
		return $code;
	}
	

	/**
	 * Print enquiry html code
	 *	 
	 * @since 1.0
	 */
	public function get_sliding_enquiry(){

	
		$this->enquiry_active = get_option( 'sp_enquiry_active' );
		$this->enquiry_place  = get_option( 'sp_enquiry_place' );
		$this->enquiry_title  = get_option( 'sp_enquiry_title' );
		$this->enquiry_title_color  = get_option( 'sp_enquiry_title_color' );
		$this->enquiry_title_image = get_option( 'sp_enquiry_title_image' );
		$this->enquiry_header_color = get_option( 'sp_enquiry_header_color' );
		$this->enquiry_header_border_color = get_option( 'sp_enquiry_header_border_color' );
		$this->enquiry_top_margin = get_option( 'sp_enquiry_top_margin' );
		$this->enquiry_content = get_option( 'sp_enquiry_content' );
		$this->show_on_homepage = get_option( 'sp_show_on_homepage' );
		$this->show_on_posts = get_option( 'sp_show_on_posts' );
		$this->show_on_pages = get_option( 'sp_show_on_pages' );

		$enquiry_html  = '<div class="sliding-enquiry">';
		$enquiry_html .= '<div class="enquiry-wrap">';
		if($this->enquiry_place!='top-left' && $this->enquiry_place!='top-right')
		{
			$enquiry_html .= '<div class="enquiry-header">';
			$enquiry_html .= '<span class="enquiry-title">';
			if( $this->enquiry_title != '') {
				$enquiry_html .= $this->enquiry_title;
				//$enquiry_html .="Enquiry Now";
			}
			$enquiry_html .= '<div class="enquiry-image">';
			if( $this->enquiry_title_image != '') {
				$enquiry_html .= '<img src="'.$this->enquiry_title_image.'" class="title_chat_icon">';	
				//$enquiry_html .= '<img src="'.plugins_url().'/sliding-enquiry/images/chat-icon.png" class="title_chat_icon">';
			}
			$enquiry_html .= '</div>';
			$enquiry_html .= '</span>';
			$enquiry_html .= '</div>';
		}
		
	    $security_code =   $this->generateCode();
		
	
		?>
		<script>
		jQuery(document).ready(function() {
		
		jQuery('input[type="text"]').focus(function() {
			jQuery(this).removeClass('error');
		});
		 
		jQuery('input[type="text"]').blur(function() {
			jQuery(this).addClass('error');
		});
		jQuery('#message').focus(function() {
			jQuery(this).removeClass('error');
		});
		 
		jQuery('#message').blur(function() {
			jQuery(this).addClass('error');
		});
		
    jQuery("#submit").click(function(){
	var name = jQuery("#name").val();
	var email = jQuery("#email").val();
	var number = jQuery("#number").val();
	var message = jQuery("#message").val();
	var captcha = jQuery("#captcha").val();
	var reg = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
	var pattern = /^\d{10}$/;

		 

		if (jQuery("#name").val()==0) 
		{
				jQuery("#name").addClass("error");
				var ok = false;
		}
		else
		{
				jQuery("#name").removeClass("error");
				var ok = true;
		}

		if (!reg.test(email)) 
		{
				jQuery("#email").addClass("error");
				var ok1 = false;
		}
		else
		{
				jQuery("#email").removeClass("error");
				var ok1 = true;
		}
		  
		
		if (!pattern.test(number)) 
		{
			jQuery("#number").addClass("error");
			var ok2 = false;
		}
		else
		{
			jQuery("#number").removeClass("error");
			var ok2 = true;
		}
		
		if (jQuery("#message").val() == "") 
		{
			jQuery("#message").addClass("error");
			var ok3 = false;	
		}
		else
		{
			jQuery("#message").removeClass("error");
			var ok3 = true;
		}
		
		if (jQuery("#captcha").val() == "<?php echo $security_code; ?>") 
		{
			jQuery("#captcha").removeClass("error");
			var ok4 = true;
			
		}
		else
		{
			jQuery("#captcha").addClass("error");
			var ok4 = false;	
		}
		
		
		if(ok == true && ok1 == true && ok2 == true && ok3 == true && ok4 == true)
		{
			
			jQuery.ajax({
				
                url: "<?php echo plugins_url(); ?>/sliding-enquiry-form/mail.php",
                type: "POST",
                contentType: false,
                processData: false,
                data: function() {
                    var data = new FormData();
                    data.append("name", jQuery("#name").val());
					data.append("email", jQuery("#email").val());
					data.append("number", jQuery("#number").val());
					data.append("message", jQuery("#message").val());
					data.append("captcha", jQuery("#captcha").val());
					data.append("serveremailid", jQuery("#serveremailid").val());
					
                    return data;
                }(),
                error: function(_, textStatus, errorThrown) {
                    
                    console.log(textStatus, errorThrown);
                },
                success: function(response, textStatus) {
					
                    if(response==0)
					{
						jQuery("#enquiryform").hide();
						jQuery("#enquirymsg").show();
					}
					else if(responce==1)
					{
						alert("send not");
					}
                    console.log(response, textStatus);
               }
            });
			return true;
		}
		else
		{
			return false;
		}
		
	

    });
   });
		</script>

		
		<?php
	
		
		$enquiry_html .= '<style type="text/css">';
		$enquiry_html .= '.error{border:1px solid red !important; }';
		$enquiry_html .= '.valid{border:1px solid green !important; }';
		$enquiry_html .= 'input[type=number]::-webkit-inner-spin-button,';
		$enquiry_html .= 'input[type=number]::-webkit-outer-spin-button {';
		$enquiry_html .= '-webkit-appearance: none; ';
		$enquiry_html .= 'margin: 0;';
		$enquiry_html .= '}';
		$enquiry_html .= '</style>';
		
		$enquiry_html .= '<div class="enquiry-content">';
		//echo '<pre/>'; print_r($this->enquiry_emailid); exit;
		$enquiry_html .= '<div class="enquiry-content-pad">';
		//if( $this->enquiry_content != '') {
				
				$enquiry_html .= '<div>';
				$enquiry_html .= '<h2>'.$this->enquiry_title.'</h2>';
				$enquiry_html .= '<hr/>';
				$enquiry_html .= '<div id="enquiryform" style="">';
				$enquiry_html .= '<form action="" id="sliding-form" name="sliding-form" method="post" style="margin: 0 0 0em;">';
				$enquiry_html .= '<input type="hidden" name="serveremailid" id="serveremailid" value="'.$this->enquiry_emailid.'"/>';
				$enquiry_html .= '<ul>';
				$enquiry_html .= '<li>';
				$enquiry_html .= '<input type="text" name="name"  id="name" placeholder="Name" class="textbox_popup" value="">';
				$enquiry_html .= '</li>';
				$enquiry_html .= '<li>';
				$enquiry_html .= '<input type="text" name="email"  id="email" placeholder="Email" class="textbox_popup" value="">';
				$enquiry_html .= '</li>';
				$enquiry_html .= '<li>';
				$enquiry_html .= '<input type="text" name="number" minlength="10"  id="number" placeholder="Number" class="textbox_popup" value="">';
				$enquiry_html .= '</li>';
				$enquiry_html .= '<li>';
				$enquiry_html .= '<textarea name="message" id="message"  placeholder="Message" class="textbox_popup" value=""></textarea>';
				$enquiry_html .= '</li>';
				$enquiry_html .= '<li style="margin-bottom:12px;">';
				$enquiry_html .= '<img src="'.plugins_url().'/sliding-enquiry-form/captha/CaptchaSecurityImages.php?width=100&height=40&characters=5&code='.$security_code.'" height="40px;" width="100px;" />';
				$enquiry_html .= '</li>';
				$enquiry_html .= '<li>';
				$enquiry_html .= '<input type="text" name="captcha" id="captcha" value="" Placeholder="Captcha" class="textbox_popup"/>';
				$enquiry_html .= '</li>';
				$enquiry_html .= '<li>';
				$enquiry_html .= '<input type="button" name="submit" id="submit" class="button1_submit" style="font-size: 20px !important;line-height: 3px;font-family: verdana;letter-spacing: 0px;font-weight: normal !important;" value="Enquiry Now"/>';
				$enquiry_html .= '</li>';
				$enquiry_html .= '</ul>';
				$enquiry_html .= '</form>';
				$enquiry_html .= '</div>';
				$enquiry_html .= '<div id="enquirymsg" style="text-align:center;display:none;">';
				$enquiry_html .= '<div style="clear">&nbsp;</div>';
				$enquiry_html .= '<div style="clear">&nbsp;</div>';
				$enquiry_html .= '<div style="clear">&nbsp;</div>';
				$enquiry_html .= '<div style="font-size: 15px;">Your Message Submitted Successfully.</div>';
				$enquiry_html .= '<div style="clear">&nbsp;</div>';
				$enquiry_html .= '<div style="clear">&nbsp;</div>';
				$enquiry_html .= '<h3>Powered By</h3>';
				$enquiry_html .= '<div style="clear"></div>';
				//$enquiry_html .= '<span><img src="'.plugins_url().'/sliding-enquiry-form/images/small.png" alt="" /></span>';
				$enquiry_html .= '</div>';
				$enquiry_html .= '</div>';
				
			//$this->enquiry_content = apply_filters('the_content', $this->enquiry_content );
			//$this->enquiry_content = str_replace( ']]>', ']]&gt;', $this->enquiry_content );
			//$enquiry_html .= $this->enquiry_content;			
		//}
		$enquiry_html .= '</div>';
		$enquiry_html .= '</div>';
		
		
		
		if($this->enquiry_place == 'top-left' || $this->enquiry_place == 'top-right')
		{
			$enquiry_html .= '<div class="enquiry-header">';
			$enquiry_html .= '<span class="enquiry-title">';
			//if( $this->enquiry_title != '') {
				//$enquiry_html .= $this->enquiry_title;
				$enquiry_html .= "Enquiry Now";
			//}
			$enquiry_html .= '<div class="enquiry-image">';
			if( $this->enquiry_title_image != '') {
				//$enquiry_html .= '<img src="'.$this->enquiry_title_image.'">';	
				$enquiry_html .= '<img src="images/chat-icon.png">';	
			}
			$enquiry_html .= '</div>';
			$enquiry_html .= '</span>';
			$enquiry_html .= '</div>';
		}

		$enquiry_html .= '</div>';
		$enquiry_html .= '</div>';
		echo $enquiry_html;
	}

	/**
	 * Add styles for enquiry header color
	 * 
	 * @since 1.0
	 */
	public function head_styles() {
		$this->enquiry_title_color = get_option( 'sp_enquiry_title_color' );
		$this->enquiry_header_color = get_option( 'sp_enquiry_header_color' );
		$this->enquiry_header_border_color = get_option( 'sp_enquiry_header_border_color' );
		$this->enquiry_place = get_option( 'sp_enquiry_place' );
		$this->enquiry_top_margin = get_option( 'sp_enquiry_top_margin' );
		?>
		<style type="text/css">
			.sliding-enquiry .enquiry-header
			{
			<?php
			if( $this->enquiry_header_color !='' ) {
			?>	
				background-color : <?php echo $this->enquiry_header_color; ?>;		
			<?php
			} else {
			?>
				background-color : #2C5A85;		
			<?php
			}
			?>
			<?php
			if( $this->enquiry_header_border_color !='' ) {
			?>	
				border-color : <?php echo $this->enquiry_header_border_color; ?>;		
			<?php
			} else {
			?>
				background-color : #2c5a85;		
			<?php
			}
			?>			
		}
		.enquiry-title
		{
			<?php
			if( $this->enquiry_title_color !='' ) {
			?>	
				color : <?php echo $this->enquiry_title_color; ?>;		
			<?php
			} else {
			?>
				color : #ffffff;		
			<?php
			}
			?>
		}
		<?php
		if($this->enquiry_place == 'left' || $this->enquiry_place == 'right')
		{
		?>
			.sliding-enquiry-right, .sliding-enquiry-left
			{
				<?php
				if( $this->enquiry_top_margin !='' ) {
				?>	
					top : <?php echo $this->enquiry_top_margin; ?>%;		
				<?php
				} else {
				?>
					top : 25%;		
				<?php
				}
				?>
			}

		<?php } ?>
		</style>
		<?php
	}

	/**
	 * Add Javascript for enquiry place
	 * 
	 * @since 1.0
	 */
	public function footer_scripts() {
		if( $this->enquiry_place == 'right-bottom' ){			
		?>
			<script type="text/javascript">
				jQuery( document ).ready(function() {	
					jQuery( ".sliding-enquiry" ).addClass('right-bottom');
					var contheight = jQuery( ".enquiry-content" ).outerHeight()+2;      	
			      	jQuery( ".sliding-enquiry" ).css( "bottom", "-"+contheight+"px" );

			      	jQuery( ".sliding-enquiry" ).css( "visibility", "visible" );

			      	jQuery('.sliding-enquiry').addClass("open_sliding_enquiry");
			      	jQuery('.sliding-enquiry').addClass("enquiry-content-bounce-in-up");
			      	
			        jQuery( ".enquiry-header" ).click(function() {
			        	if(jQuery('.sliding-enquiry').hasClass("open"))
			        	{
			        		jQuery('.sliding-enquiry').removeClass("open");
			        		jQuery( ".sliding-enquiry" ).css( "bottom", "-"+contheight+"px" );
			        	}
			        	else
			        	{
			        		jQuery('.sliding-enquiry').addClass("open");
			          		jQuery( ".sliding-enquiry" ).css( "bottom", 0 );		
			        	}
			          
			        });		    
				});
			</script>
		<?php	
		} elseif( $this->enquiry_place == 'left-bottom' ) {
		?>
			<script type="text/javascript">
				jQuery( document ).ready(function() {	
					jQuery( ".sliding-enquiry" ).addClass('left-bottom');
					var contheight = jQuery( ".enquiry-content" ).outerHeight()+2;      	
			      	jQuery( ".sliding-enquiry" ).css( "bottom", "-"+contheight+"px" );

			      	jQuery( ".sliding-enquiry" ).css( "visibility", "visible" );

			      	jQuery('.sliding-enquiry').addClass("open_sliding_enquiry");
			      	jQuery('.sliding-enquiry').addClass("enquiry-content-bounce-in-up");
			      	
			        jQuery( ".enquiry-header" ).click(function() {
			        	if(jQuery('.sliding-enquiry').hasClass("open"))
			        	{
			        		jQuery('.sliding-enquiry').removeClass("open");
			        		jQuery( ".sliding-enquiry" ).css( "bottom", "-"+contheight+"px" );
			        	}
			        	else
			        	{
			        		jQuery('.sliding-enquiry').addClass("open");
			          		jQuery( ".sliding-enquiry" ).css( "bottom", 0 );		
			        	}
			          
			        });		    
				});
			</script>
		<?php
		} elseif( $this->enquiry_place == 'left' ) {
		?>
			<script type="text/javascript">
				jQuery( document ).ready(function() {	
					if (/*@cc_on!@*/true) {						
						var ieclass = 'ie' + document.documentMode; 
						jQuery( ".enquiry-wrap" ).addClass(ieclass);
					} 
					jQuery( ".sliding-enquiry" ).addClass('sliding-enquiry-left');
					var contwidth = jQuery( ".enquiry-content" ).outerWidth()+2;      	
			      	jQuery( ".sliding-enquiry" ).css( "left", "-"+contwidth+"px" );

			      	jQuery( ".sliding-enquiry" ).css( "visibility", "visible" );

			      	jQuery('.sliding-enquiry').addClass("open_sliding_enquiry_left");
			      	jQuery('.sliding-enquiry').addClass("enquiry-content-bounce-in-left");
			      	
			        jQuery( ".enquiry-header" ).click(function() {
			        	if(jQuery('.sliding-enquiry').hasClass("open"))
			        	{
			        		jQuery('.sliding-enquiry').removeClass("open");
			        		jQuery( ".sliding-enquiry" ).css( "left", "-"+contwidth+"px" );
			        	}
			        	else
			        	{
			        		jQuery('.sliding-enquiry').addClass("open");
			          		jQuery( ".sliding-enquiry" ).css( "left", 0 );		
			        	}
			          
			        });		    
				});
			</script>
		<?php
		} elseif( $this->enquiry_place == 'right' ) {
		?>
			<script type="text/javascript">
				jQuery( document ).ready(function() {	
					if (/*@cc_on!@*/true) { 						
						var ieclass = 'ie' + document.documentMode; 
						jQuery( ".enquiry-wrap" ).addClass(ieclass);
					} 
					jQuery( ".sliding-enquiry" ).addClass('sliding-enquiry-right');
					
					var contwidth = jQuery( ".enquiry-content" ).outerWidth()+2;      	
			      	jQuery( ".sliding-enquiry" ).css( "right", "-"+contwidth+"px" );

			      	jQuery( ".sliding-enquiry" ).css( "visibility", "visible" );

			      	jQuery('.sliding-enquiry').addClass("open_sliding_enquiry_right");
			      	jQuery('.sliding-enquiry').addClass("enquiry-content-bounce-in-right");
			      	
			        jQuery( ".enquiry-header" ).click(function() {
			        	if(jQuery('.sliding-enquiry').hasClass("open"))
			        	{
			        		jQuery('.sliding-enquiry').removeClass("open");
			        		jQuery( ".sliding-enquiry" ).css( "right", "-"+contwidth+"px" );
			        	}
			        	else
			        	{
			        		jQuery('.sliding-enquiry').addClass("open");
			          		jQuery( ".sliding-enquiry" ).css( "right", 0 );		
			        	}
			          
			        });		    
				});
			</script>
		<?php
		} elseif( $this->enquiry_place == 'top-left' ) {
		?>
			<script type="text/javascript">
				jQuery( document ).ready(function() {	
					jQuery( ".sliding-enquiry" ).addClass('top-left');
					var contheight = jQuery( ".enquiry-content" ).outerHeight()+2;      	
			      	jQuery( ".sliding-enquiry" ).css( "top", "-"+contheight+"px" );
					
			      	jQuery( ".sliding-enquiry" ).css( "visibility", "visible" );

			      	jQuery('.sliding-enquiry').addClass("open_sticky_popup_top");
			      	jQuery('.sliding-enquiry').addClass("enquiry-content-bounce-in-down");
			      	
			        jQuery( ".enquiry-header" ).click(function() {
			        	if(jQuery('.sliding-enquiry').hasClass("open"))
			        	{
			        		jQuery('.sliding-enquiry').removeClass("open");
			        		jQuery( ".sliding-enquiry" ).css( "top", "-"+contheight+"px" );
			        	}
			        	else
			        	{
			        		jQuery('.sliding-enquiry').addClass("open");
			          		jQuery( ".sliding-enquiry" ).css( "top", 0 );		
			        	}
			          
			        });		    
				});
			</script>
		<?php
		} elseif( $this->enquiry_place == 'top-right' ) {
		?>
			<script type="text/javascript">
				jQuery( document ).ready(function() {	
					jQuery( ".sliding-enquiry" ).addClass('top-right');
					var contheight = jQuery( ".enquiry-content" ).outerHeight()+2;      	
			      	jQuery( ".sliding-enquiry" ).css( "top", "-"+contheight+"px" );
					
			      	jQuery( ".sliding-enquiry" ).css( "visibility", "visible" );

			      	jQuery('.sliding-enquiry').addClass("open_sticky_popup_top");
			      	jQuery('.sliding-enquiry').addClass("enquiry-content-bounce-in-down");
			      	
			        jQuery( ".enquiry-header" ).click(function() {
			        	if(jQuery('.sliding-enquiry').hasClass("open"))
			        	{
			        		jQuery('.sliding-enquiry').removeClass("open");
			        		jQuery( ".sliding-enquiry" ).css( "top", "-"+contheight+"px" );
			        	}
			        	else
			        	{
			        		jQuery('.sliding-enquiry').addClass("open");
			          		jQuery( ".sliding-enquiry" ).css( "top", 0 );		
			        	}
			          
			        });		    
				});
			</script>
		<?php
		} else {
		?>
			<script type="text/javascript">
				jQuery( document ).ready(function() {	
					jQuery( ".sliding-enquiry" ).addClass('right-bottom');
					var contheight = jQuery( ".enquiry-content" ).outerHeight()+2;      	
			      	jQuery( ".sliding-enquiry" ).css( "bottom", "-"+contheight+"px" );

			      	jQuery( ".sliding-enquiry" ).css( "visibility", "visible" );

			      	jQuery('.sliding-enquiry').addClass("open_sliding_enquiry");
			      	jQuery('.sliding-enquiry').addClass("enquiry-content-bounce-in-up");
			      	
			        jQuery( ".enquiry-header" ).click(function() {
			        	if(jQuery('.sliding-enquiry').hasClass("open"))
			        	{
			        		jQuery('.sliding-enquiry').removeClass("open");
			        		jQuery( ".sliding-enquiry" ).css( "bottom", "-"+contheight+"px" );
			        	}
			        	else
			        	{
			        		jQuery('.sliding-enquiry').addClass("open");
			          		jQuery( ".sliding-enquiry" ).css( "bottom", 0 );		
			        	}
			          
			        });		    
				});
			</script>
		<?php
		}
	}
}