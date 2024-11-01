<?php
/**
 * Slide-Out
 *
 * Plugin Name:         Shameless Slide-Out
 * Description:         Displays a tab on the side of the screen which slides out to reveal additional content when clicked.
 * Version:             1.0
 * Author:              Shameless Promotion LLC
 * License:             GPLv2
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 */

define('SLIDEINPATH', plugin_dir_path(__FILE__));
define('SLIDEINURL', plugin_dir_url(__FILE__));

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style('slidein', SLIDEINURL . 'style.css', array(), filemtime(SLIDEINPATH . 'style.css'));
    wp_enqueue_script('slidein', SLIDEINURL . 'slide-in.js', array(), filemtime(SLIDEINPATH . 'slide-in.js'));
});

add_action('admin_enqueue_scripts', function(){
	wp_enqueue_style('slidein-admin', SLIDEINURL . 'style-admin.css', array(), filemtime(SLIDEINPATH . 'style-admin.css'));
	wp_enqueue_script('slidein-admin', SLIDEINURL . 'slide-in-admin.js', array(), filemtime(SLIDEINPATH . 'slide-in-admin.js'));
});

add_action('init', 'slidein_register_types');
function slidein_register_types() {
	register_post_type('slidein_slide',
		array(
			'labels'      => array(
				'name'          => 'Slide-Outs',
				'singular_name' => 'Slide-Out'
			),
			'public'      => false,
			'has_archive' => false,
            'show_ui' => true,
			'supports' => array('author'),
		)
	);
}

add_action( 'add_meta_boxes', 'slidein_meta_boxes' );
function slidein_meta_boxes($post_type) {
    if ($post_type == 'slidein_slide')
    {
        echo('added?');

	    add_meta_box(
		    'slidein-settings',
		    'Slide Out Settings',
		    'slidein_metabox_contents',
            'slidein_slide'
	    );
    }
}

function slidein_get_option($Post, $OptionName, $DefaultValue)
{
	if ( metadata_exists( 'post', $Post, $OptionName) ) {
		return get_post_meta($Post, $OptionName, true);
	}
    return $DefaultValue;
}

function slidein_metabox_contents()
{
    $post = get_the_ID();

	$att_id = slidein_get_option($post,'slidein-image', '');
	$url = wp_get_attachment_url($att_id);

	$att_id = slidein_get_option($post,'slidein-image-content', '');
	$url2 = wp_get_attachment_url($att_id);

	wp_enqueue_media();

	$UseBGImage = slidein_get_option($post, 'slidein-image-bg', 'no') == 'yes';
	$UseBGImage2 = slidein_get_option($post,'slidein-image-content-enable', 'no') == 'yes';
	$Position = slidein_get_option($post,'slidein-position', 'right');
	$Sticky = slidein_get_option($post, 'slidein-sticky', 'yes');
    $ButtonEnabled = slidein_get_option($post,'slidein-button-enable', 'yes') == 'yes';

    print_r(get_post_meta($post));

    ?>

    <div class="slidein-section">
        <h2>Position Settings</h2>

        <div class="slidein-row">
            <label for="slidein-position">Slide-Out Position</label>
            <select id="slidein-position" name="slidein-position">
                <option value="left" <?php echo(esc_attr($Position == 'left'? 'selected' : ''))  ?>>Left Side</option>
                <option value="right" <?php echo(esc_attr($Position == 'right'? 'selected' : ''))  ?>>Right Side</option>
                <option value="top" <?php echo(esc_attr($Position == 'top'? 'selected' : ''))  ?>>Top</option>
            </select>
        </div>

        <div class="slidein-row">
            <label for="slidein-sticky">Sticky Tab</label>
            <p class="slidein-note">A sticky tab will stay in the same place as the page is scrolled.</p>
            <select id="slidein-sticky" name="slidein-sticky">
                <option value="yes" <?php echo(esc_attr($Sticky == 'yes'? 'selected' : ''))  ?>>Sticky</option>
                <option value="no" <?php echo(esc_attr($Sticky == 'no'? 'selected' : ''))  ?>>Not Sticky</option>
            </select>
        </div>
    </div>

    <div class="slidein-section">
        <h2>Colors</h2>
        <div class="slidein-grid">
            <div class="slidein-row">
                <label for="slidein-tab-bg-col">Tab Background</label>
                <input type="color" name='slidein-tab-bg-col' id='slidein-tab-bg-col' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-tab-bg-col', '#2c3e50'))) ?>">
            </div>

            <div class="slidein-row">
                <label for="slidein-tab-text-col">Tab Text</label>
                <input type="color" name='slidein-tab-text-col' id='slidein-tab-text-col' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-tab-text-col', '#ffffff'))) ?>">
            </div>

            <div class="slidein-row">
                <label for="slidein-bg-col">Content Background</label>
                <input type="color" name='slidein-bg-col' id='slidein-bg-col' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-bg-col', '#2c3e50'))) ?>">
            </div>

            <div class="slidein-row">
                <label for="slidein-title-col">Title</label>
                <input type="color" name='slidein-title-col' id='slidein-title-col' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-title-col', '#ffffff'))) ?>">
            </div>

            <div class="slidein-row">
                <label for="slidein-sub-col">Subtitle</label>
                <input type="color" name='slidein-sub-col' id='slidein-sub-col' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-sub-col', '#ffffff'))) ?>">
            </div>

            <div class="slidein-row">
                <label for="slidein-text-col">Content Text</label>
                <input type="color" name='slidein-text-col' id='slidein-text-col' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-text-col', '#ffffff'))) ?>">
            </div>

            <div class="slidein-row">
                <label for="slidein-button-bg">Button Background</label>
                <input type="color" name='slidein-button-bg' id='slidein-button-bg' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-button-bg', '#ffffff'))) ?>">
            </div>

            <div class="slidein-row">
                <label for="slidein-button-col">Button Text</label>
                <input type="color" name='slidein-button-col' id='slidein-button-col' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-button-col', '#000000'))) ?>">
            </div>

            <div class="slidein-row">
                <label for="slidein-button-hover-bg">Button Background (Hover)</label>
                <input type="color" name='slidein-button-hover-bg' id='slidein-button-hover-bg' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-button-hover-bg', '#e6e6e6'))) ?>">
            </div>

            <div class="slidein-row">
                <label for="slidein-button-hover-col">Button Text (Hover)</label>
                <input type="color" name='slidein-button-hover-col' id='slidein-button-hover-col' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-button-hover-col', '#000000'))) ?>">
            </div>
        </div>
    </div>

    <div class="slidein-section">
        <h2>Appearance</h2>

        <div class="slidein-row slidein-flex">
            <div>
                <label for="slidein-tab-width">Tab Width (px)</label><br>
                <input type="number" name='slidein-tab-width' id='slidein-tab-width' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-tab-width', '100'))) ?>">
            </div>

            <div>
                <label for="slidein-tab-height">Tab Height (px)</label><br>
                <input type="number" name='slidein-tab-height' id='slidein-tab-height' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-tab-height', '100'))) ?>">
            </div>

            <div>
                <label for="slidein-width">Content Width (px)</label><br>
                <input type="number" name='slidein-width' id='slidein-width' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-width', '450'))) ?>">
            </div>

            <div>
                <label for="slidein-height">Content Height (px)</label><br>
                <input type="number" name='slidein-height' id='slidein-height' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-height', '300'))) ?>">
            </div>
        </div>

        <div class="slidein-spacer"></div>

        <div class="slidein-row">
            <label for="slidein-image-bg">Use Image Background on Tab</label>
            <input type="checkbox" id="slidein-image-bg" name="slidein-image-bg" <?php echo(esc_Attr($UseBGImage? " checked" : "")) ?>/>
        </div>

        <div class="slidein-row row-image-tab" style="<?php echo(esc_attr($UseBGImage? "" : "display: none")) ?>">
            <label for="slidein-image">Tab Background Image</label>
            <div class='image-preview-wrapper'>
                <img alt="Tab Background Image" class='image-preview' data-field="slidein-image" src='<?php echo(esc_url($url)); ?>' style='height: 150px; width: auto;'>
            </div>
            <input class="upload_image_button button" data-field="slidein-image" type="button" value="<?php _e( 'Change image' ); ?>" />
            <input type='hidden' name='slidein-image' id='slidein-image' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-image', ''))) ?>">
        </div>

        <div class="slidein-spacer"></div>

        <div class="slidein-row">
            <label for="slidein-image-content-enable">Use Image Background on Content</label>
            <input type="checkbox" id="slidein-image-content-enable" name="slidein-image-content-enable" <?php echo(esc_attr($UseBGImage2? " checked" : "")) ?>/>
        </div>

        <div class="slidein-row row-image-content" style="<?php echo(esc_attr($UseBGImage2? "" : "display: none")) ?>">
            <label for="slidein-image-content">Content Background Image</label>
            <div class='image-preview-wrapper'>
                <img alt="Content Background Image" class='image-preview' data-field="slidein-image-content" src='<?php echo(esc_url($url2)); ?>' style='height: 150px; width: auto;'>
            </div>
            <input class="upload_image_button button" data-field="slidein-image-content" type="button" value="<?php _e( 'Change image' ); ?>" />
            <input type='hidden' name='slidein-image-content' id='slidein-image-content' value="<?php echo(esc_attr(slidein_get_option($post,'slidein-image-content', ''))) ?>">
        </div>
    </div>

    <div class="slidein-section">
        <h2>Slide-out Contents</h2>

        <div class="slidein-row">
            <label for="slidein-tab-text">Text on Tab</label>
            <input type="text" id="slidein-tab-text" name="slidein-tab-text" value="<?php echo(esc_attr(slidein_get_option($post,'slidein-tab-text', 'Tab Text'))) ?>"/>
        </div>

        <div class="slidein-row">
            <label for="slidein-title">Title</label>
            <input type="text" id="slidein-title" name="slidein-title" value="<?php echo(esc_attr(slidein_get_option($post,'slidein-title', 'Slide Out title'))) ?>"/>
        </div>

        <div class="slidein-row">
            <label for="slidein-subtitle">Subtitle</label>
            <input type="text" id="slidein-subtitle" name="slidein-subtitle" value="<?php echo(esc_attr(slidein_get_option($post,'slidein-subtitle', 'Subtitle'))) ?>"/>
        </div>

        <div class="slidein-row">
            <label for="slidein-content">Main Text</label>
		    <?php wp_editor(slidein_get_option($post,'slidein-content', 'Put your content here.'), 'slidein-content'); ?>
        </div>
    </div>

    <div class="slidein-section">
        <h2>Button</h2>

        <div class="slidein-row">
            <label for="slidein-button-enable">Enable Button</label>
            <input type="checkbox" id="slidein-button-enable" name="slidein-button-enable" <?php echo(esc_Attr($ButtonEnabled? " checked" : "")) ?>/>
        </div>

        <div class="slidein-row">
            <label for="slidein-button-title">Button Title</label>
            <input type="text" id="slidein-button-title" name="slidein-button-title" value="<?php echo(esc_attr(slidein_get_option($post,'slidein-button-title', 'Click Here'))) ?>"/>
        </div>

        <div class="slidein-row">
            <label for="slidein-button-link">Button Link</label>
            <input type="text" id="slidein-button-link" name="slidein-button-link" value="<?php echo(esc_attr(slidein_get_option($post,'slidein-button-link', '#'))) ?>"/>
        </div>
    </div>

    <?php
}

add_action( 'save_post_slidein_slide', 'slidein_save_post' );
function slidein_save_post( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	if ( $parent_id = wp_is_post_revision( $post_id ) ) {
		$post_id = $parent_id;
	}

	$fields = [
        'slidein-image',
        'slidein-image-content',
        'slidein-position',
        'slidein-sticky',
        'slidein-tab-bg-col',
        'slidein-tab-text-col',
        'slidein-bg-col',
        'slidein-title-col',
        'slidein-sub-col',
        'slidein-text-col',
        'slidein-button-bg',
        'slidein-button-col',
        'slidein-button-hover-bg',
        'slidein-button-hover-col',
        'slidein-tab-width',
        'slidein-tab-height',
        'slidein-tab-text',
        'slidein-title',
        'slidein-subtitle',
        'slidein-content',
        'slidein-button-title',
        'slidein-button-link',
        'slidein-width',
        'slidein-height'

	];

    $checkboxes = [
	    'slidein-image-bg',
	    'slidein-button-enable',
	    'slidein-image-content-enable',
    ];

	foreach ( $fields as $field ) {
		if ( array_key_exists( $field, $_POST ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
		}
	}

	foreach ( $checkboxes as $field ) {
		if ( array_key_exists( $field, $_POST ) ) {
			update_post_meta( $post_id, $field, 'yes' );
		}
        else
        {
	        update_post_meta( $post_id, $field, 'no' );
        }
	}
}

add_action( 'admin_init', 'shameless_slidein_settings' );
function shameless_slidein_settings() { // whitelist options
	register_setting( 'slidein-group', 'slidein-enable' );
}

add_action( 'admin_menu', 'shameless_slidein_menu' );
function shameless_slidein_menu() {
	add_menu_page( 'Slide-Out', 'Slide-Out', 'manage_options', 'slide-in-admin', 'shameless_slidein_options' );
}

function shameless_slidein_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo(wp_kses_post('<div class="wrap">'));
	echo(wp_kses_post('<form method="post" action="options.php">'));
	
	settings_fields( 'slidein-group' );

	?>

	<h2>Slide-Out Settings</h2>

	<div class="slide-in-settings">
  				
		<div class="slidein-row">
  			<label for="slidein-enable">Enable Slide-Outs</label>
            <span>Enable or disable all Slide Outs across the site</span><br>
			<input type="checkbox" id="slidein-enable" name="slidein-enable" <?php echo(esc_attr(get_option('slidein-enable') == 'on'? " checked" : "")) ?>/>
  		</div>

        <div class="slidein-row">
            <a class="slidein-button" target="_blank" href="<?php echo(esc_url(admin_url('post-new.php?post_type=slidein_slide'))) ?>">New Slide Out</a>
        </div>

	</div>
	
	<?
	submit_button(); 
	echo(wp_kses_post('</form>'));
	echo(wp_kses_post('</div>'));
}

add_action( 'wp_footer', 'shameless_slidein_inject_html' );
function shameless_slidein_inject_html() {
	if (get_option('slidein-enable') != 'on') return;

	$posts = get_posts([
		'post_type' => 'slidein_slide',
		'post_status' => 'publish',
		'numberposts' => -1
	]);

    foreach ($posts as $post)
    {
        if (slidein_get_option($post->ID,'slidein-login-only', 'off') == 'on')
        {
            if (!is_user_logged_in()) continue;
        }

        $Position = slidein_get_option($post->ID,'slidein-position', 'right');
        $Sticky = slidein_get_option($post->ID,'slidein-sticky', 'yes');
        $TabBG = slidein_get_option($post->ID,'slidein-tab-bg-col', '#8bccf0');
        $ContentBG = slidein_get_option($post->ID,'slidein-bg-col', '#8bccf0');

        $TitleColor = slidein_get_option($post->ID,'slidein-title-col', '#ffffff');
        $SubTitleColor = slidein_get_option($post->ID,'slidein-sub-col', '#ffffff');
        $ContentColor = slidein_get_option($post->ID,'slidein-text-col', '#ffffff');

        $UseTabImage = slidein_get_option($post->ID,'slidein-image-bg', 'no') == 'yes';
        $UseContentImage = slidein_get_option($post->ID,'slidein-image-content-enable', 'no') == 'yes';
        $TabImage = wp_get_attachment_url(slidein_get_option($post->ID,'slidein-image', ''));
        $ContentImage = wp_get_attachment_url(slidein_get_option($post->ID,'slidein-image-content', ''));

        $Title = slidein_get_option($post->ID,'slidein-title', 'Slide Out title');
        $SubTitle = slidein_get_option($post->ID,'slidein-subtitle', 'Subtitle');
        $Content = slidein_get_option($post->ID,'slidein-content', 'Put your content here.');

        $UseButton = slidein_get_option($post->ID,'slidein-button-enable', 'yes') == 'yes';
        $ButtonTitle = slidein_get_option($post->ID,'slidein-button-title', 'Click Here');
        $ButtonLink = slidein_get_option($post->ID,'slidein-button-link', '#');
        $ButtonColor = slidein_get_option($post->ID,'slidein-button-bg', '#dd7200');
        $ButtonTextColor = slidein_get_option($post->ID,'slidein-button-col', '#ffffff');

        $ButtonHoverColor = slidein_get_option($post->ID,'slidein-button-hover-bg', '#dd7200');
        $ButtonTextHoverColor = slidein_get_option($post->ID,'slidein-button-hover-col', '#ffffff');

        $TabText = slidein_get_option($post->ID,'slidein-tab-text', 'Tab Text');
        $TabTextCol = slidein_get_option($post->ID,'slidein-tab-text-col', '#ffffff');

        $TabWidth = slidein_get_option($post->ID,'slidein-tab-width', '100');
        $TabHeight = slidein_get_option($post->ID,'slidein-tab-height', '100');
        $ContentWidth =  slidein_get_option($post->ID,'slidein-width', '450');
	    $ContentHeight =  slidein_get_option($post->ID,'slidein-height', '300');

        $TotalWidth = intval($TabWidth) + $ContentWidth;

        $BGImageTab = $UseTabImage? "background-image: url('" . $TabImage . "');" : "";
        $BGImageMain = $UseContentImage? "background-image: url('" . $ContentImage . "');" : "";

        ?>

        <style>
            @media only screen and (min-width: 571px) {
                .slidein_<?php echo(esc_attr($post->ID)) ?> {
                    width: <?php echo(esc_attr($TotalWidth)) ?>px;
                }
            }

            .slidein_<?php echo(esc_attr($post->ID)) ?> a.listen
            {
                background-color: <?php echo(esc_attr($ButtonColor)) ?>;
                color: <?php echo(esc_attr($ButtonTextColor)) ?>;
            }

            .slidein_<?php echo(esc_attr($post->ID)) ?> a.listen:hover
            {
                background-color: <?php echo(esc_attr($ButtonHoverColor)) ?>;
                color: <?php echo(esc_attr($ButtonTextHoverColor)) ?>;
            }
        </style>

        <div class="slidein_<?php echo(esc_attr($post->ID)) ?> slidein-outer slidein-pos-<?php echo(esc_attr($Position)) ?> <?php if ($Sticky == 'yes') echo(esc_attr("slidein-sticky-disabled")) ?>">
            <a class="slidein-tab" style="width: <?php echo(esc_attr($TabWidth)) ?>px; height: <?php echo(esc_attr($TabHeight)) ?>px; color: <?php echo(esc_attr($TabTextCol)) ?>; background-color: <?php echo(esc_attr($TabBG)) ?>; <?php echo(esc_attr($BGImageTab)) ?>">
                <?php echo(esc_html($TabText)) ?>
            </a>
            <div class="slidein-inner" style="<?php echo(esc_attr($BGImageMain . "background-color: " . $ContentBG . ';' . 'left: ' . $TabWidth . 'px; width: ' . $ContentWidth . 'px; min-height: ' . $ContentHeight . 'px;')) ?>">
                <h3 style="color: <?php echo(esc_attr($TitleColor)) ?>"><?php echo(esc_html($Title)); ?></h3>
                <p class="subtitle" style="color: <?php echo(esc_attr($SubTitleColor)); ?>"><?php echo(esc_html($SubTitle)); ?></p>
                <p style="color: <?php echo(esc_attr($ContentColor)) ?>;">
                    <?php echo(esc_html($Content)); ?>
                </p>

                <?php if($UseButton) { ?>
                <a class="listen" target="_blank" href="<?php echo(esc_url($ButtonLink)); ?>"><?php echo(esc_html($ButtonTitle)); ?></a>
                <?php } ?>
            </div>
        </div>

        <?php
    }
}