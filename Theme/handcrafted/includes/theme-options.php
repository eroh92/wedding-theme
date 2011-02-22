<?php /* theme-options */ global $d;


/* General */

$d->options['General'] = array();

$d->options['General'][] = array(
	'name'			=>	'Color Theme',
	'id'			=>	'color_theme',
	'type'			=>	'select',
	'fields'		=>	'Default, Dark, Wood',
	'description'	=>	'Choose your desired color theme.',
	'intro_message' => array(
		'title'		=>	'Setting up ' . der_theme_data('Name') . ' ' . der_theme_data('Version'),
		'content'	=>	'
This is the Administration Interface for your theme. Here you can edit all of the settings &amp; functionality.<br/>
You can switch to the option pages available from the <strong><u>' . DER_OPTIONS_MENU_TITLE . '</u></strong> menu on the left hand side.
'
	)
);

$d->options['General'][] = array(
	'name'			=>	'Navigation Menu Alignment',
	'id'			=>	'nav_menu_alignment',
	'type'			=>	'select',
	'fields'		=>	'center, left, right',
	'selector'		=>	'#header .navigation',
	'property'		=>	'text-align',
	'description'	=>	'Position of the Navigation Menu.'
);

$d->options['General'][] = array(
	'name'			=>	'Favicon',
	'id'			=>	'favicon',
	'type'			=>	'text',
	'default'		=>	'',
	'mime'			=>	'image',
	'description'	=>	'The Favicon to use on your site.'
);

$d->options['General'][] = array(
	'name'			=>	'Logo Image',
	'id'			=>	'logo_image',
	'type'			=>	'text',
	'default'		=>	'',
	'mime'			=>	'image',
	'description'	=>	'The Logo to use on your site.'
);

$d->options['General'][] = array(
	'name'			=>	'Logo Alignment',
	'id'			=>	'logo_alignment',
	'type'			=>	'select',
	'fields'		=>	'center, left, right',
	'selector'		=>	'#header .logo',
	'property'		=>	'text-align',
	'description'	=>	'How the logo will be aligned in the layout.'
);

$d->options['General'][] = array(
	'name'			=>	'Twitter Username',
	'id'			=>	'twitter_username',
	'type'			=>	'text',
	'default'		=>	'',
	'description'	=>	'Twitter username to use on your site.'
);


$d->options['General'][] = array(
	'name'			=>	'RSS Feed URL',
	'id'			=>	'rss_feed',
	'type'			=>	'text',
	'default'		=>	'',
	'description'	=>	'Custom RSS Feed URL. Useful if you make use of <a href="http://feedburner.google.com/">FeedBurner</a> or other Feed Service.'
);

$d->options['General'][] = array(
	'name'			=>	'Excerpt Length <small>(in words)</small>',
	'id'			=>	'excerpt_length',
	'type'			=>	'text',
	'default'		=>	'50',
	'description'	=>	'Amount of words to use for Post Excerpts.'
);

$d->options['General'][] = array(
	'name'			=>	'Excerpt Ending Characters',
	'id'			=>	'excerpt_more',
	'type'			=>	'text',
	'default'		=>	' [&hellip;]',
	'description'	=>	'Characters appended to the end of each trimmed excerpt.'
);

$d->options['General'][] = array(
	'name'			=>	'Copyright Information',
	'id'			=>	'copyright_info',
	'type'			=>	'textarea',
	'rows'			=>	'3',
	'default'		=>	'Designed & Developed by <a href="http://der-design.com">der|Design</a>. Proudly powered by <a href="http://wordpress.org">WordPress</a>',
	'description'	=>	'Footer Copyright Information.'
);

$d->options['General'][] = array(
	'name'			=>	'Analytics (Tracking) Code',
	'id'			=>	'analytics_code',
	'type'			=>	'textarea',
	'rows'			=>	'6',
	'default'		=>	'',
	'description'	=>	'<a href="http://google.com/analytics">Google Analytics</a> (or other) Tracking Code.'
);

$d->options['General'][] = array(
	'name'			=>	'Theme Configuration',
	'type'			=>	'checkbox',
	'fields'		=>	array(
						'disable_homepage_posts'	=>		"Don't display any posts on the Homepage",
						'disable_footer'			=>		'Use Simple Footer (Disable Footer Widgets)',
						'disable_home_menu_item'	=>		'Remove the "Home" menu item from the Navigation',
						'disable_timthumb'			=>		'Disable automatic thumbnail generation (not recommended)',
						'disable_js_caching'		=>		'Disable Caching for AJAX-Enabled features provided by the theme'
						),
);


/* Background */

$d->options['Background'] = array();

$d->options['Background'][] = array(
	'name'			=>	'Background Image',
	'id'			=>	'background_image',
	'type'			=>	'text',
	'default'		=>	'',
	'mime'			=>	'image',
	'description'	=>	'Background Image to be used on your site.',
	'intro_message' => array(
		'title'		=>	'Setting up your Site\'s Background',
		'content'	=>	'
You can change your site\'s Background Image, Background Color &amp; other settings, by using the options on this page. <br/>
To be able to see the background color, it is important that you choose a PNG image with Transparent Pixels.

'
	)
);

$d->options['Background'][] = array(
	'name'			=>	'Background Color',
	'id'			=>	'background_color',
	'type'			=>	'color',
	'default'		=>	'',
	'description'	=>	'Background color for your site.'
);

 $d->options['Background'][] = array(
	'name'			=>	'Background Image Position',
	'id'			=>	'background_position',
	'type'			=>	'select',
	'fields'		=>	'top center, top, right, bottom, left, center, top left, top right, center left, center right, bottom left, bottom center, bottom right',
	'description'	=>	'Choose how you want to align your Background Image to the screen.'
);

$d->options['Background'][] = array(
	'name'			=>	'Background Image Repeat (Tiling)',
	'id'			=>	'background_repeat',
	'type'			=>	'select',
	'fields'		=>	'repeat, repeat-x, repeat-y, no-repeat',
	'description'	=>	'Choose how you want to repeat your Background Image:<br/>
<br/>Repeat-x will repeat your image Horizontally,
<br/>Repeat-y will repeat your image Vertically.
<br/>Repeat will tile your image in both directions.
<br/>No-repeat will not tile your image.'
);

$d->options['Background'][] = array(
	'name'			=>	'Background Image Attachment',
	'id'			=>	'background_attachment',
	'type'			=>	'select',
	'fields'		=>	'scroll, fixed',
	'description'	=>	'Background Image Attachment. <br/><br/>A Fixed background will stay on its same position, even if you scroll the page up/down.'
);


/* Homepage */

$d->options['Homepage'] = array();

$d->options['Homepage'][] = array(
	'name'			=>	'Homepage Posts per Page <small>(multiple of 3)</small>',
	'id'			=>	'homepage_posts_limit',
	'type'			=>	'text',
	'default'		=>	'3',
	'description'	=>	'Amount of Posts to display on the Homepage.',
	'intro_message'	=> array(
		'title'		=>	'Setting up your Homepage',
		'content'	=>	'
You can add content to your Homepage by publishing <a href="' . admin_url('edit.php') . '">Posts</a>, organizing them with <a href="' . admin_url('edit-tags.php?taxonomy=category') . '">Categories</a>.<br/><br/>
If you choose to <u>Exclude Posts</u> from specific categories, the posts from the categories you select will not be displayed on your Homepage.<br/>
By choosing to <u>Only Show Posts</u> from specific categories, only the posts from those categories will be shown (Ignoring the Exclude Posts settings).

<br/>
'
	)
);

$d->options['Homepage'][] = array(
	'name'			=>	'Exclude Posts from the following Categories:',
	'id'			=>	'homepage_exclude_categories',
	'type'			=>	'taxonomies_checkbox',
	'taxonomy'		=>	'category'
);

$d->options['Homepage'][] = array(
	'name'			=>	'Only Show Posts from the Following Categories <small>(Ignores excluded categories)</small>',
	'id'			=>	'homepage_only_categories',
	'type'			=>	'taxonomies_checkbox',
	'taxonomy'		=>	'category',
);

$d->options['Homepage'][] = array(
	'name'			=>	'Recent Posts to be Marked as "NEW"',
	'id'			=>	'homepage_consider_as_new',
	'type'			=>	'text',
	'default'		=>	'2',
	'description'	=>	'Number of Posts in the Homepage to be tagged as "NEW".'
);

$d->options['Homepage'][] = array(
	'name'			=>	'Homepage Teaser',
	'id'			=>	'homepage_teaser',
	'type'			=>	'select',
	'fields'		=>	'Quote, Twitter Feed, Don\'t Show',
	'description'	=>	'Type of information to display on the Teaser Section, below the Slider.<br/><br/>

<strong>Note</strong>: <em>You can set your <u>Twitter Username</u> on the <a href="#" onclick="$(\'ul.sections a[rel=general]\').click(); $(\'input[name=twitter_username]\').focus(); return false;" >General Tab</a>.</em>
'
);

$d->options['Homepage'][] = array(
	'name'			=>	'Homepage Rounded Icon Destination',
	'id'			=>	'homepage_icon_destination',
	'type'			=>	'select',
	'fields'		=>	'Contact Page, Twitter Profile',
	'description'	=>	'Icon to display in the Teaser Section'
);

$d->options['Homepage'][] = array(
	'name'			=>	'Homepage Quote',
	'id'			=>	'homepage_quote',
	'type'			=>	'textarea',
	'rows'			=>	'2',
	'default'		=>	"We were born to do this, and we love doing it.\nCreativity is the liquid that powers our brains.",
	'description'	=>	'Quote to use on the Teaser section.'
);


/* Homepage */

$d->options['Slider'] = array();

$d->options['Slider'][] = array(
	'name'			=>	'Slider Manager',
	'id'			=>	'slider_manager',
	'type'			=>	'select',
	'fields'		=>	'Nivo Slider, Piecemaker',
	'description'	=>	'Choose your Slideshow Manager.',
	'intro_message'	=> array(
		'title'		=>	'Setting up your Slideshow',
		'content'	=>	'
You can add Slides by adding <a href="' . admin_url('edit.php?post_type=slideshow') . '">Slider Posts</a>, which can (optionally) be organized using <a href="' . admin_url('edit-tags.php?taxonomy=slideshow-category&post_type=slideshow') . '">Categories</a>.   <br/>

<br/><a class="button" href="' . admin_url('admin.php?page=nivo') . '">Nivo Slider Settings</a><span style="display: inline-block; width: 20px;"></span><a class="button" href="' . admin_url('admin.php?page=piecemaker') . '">Piecemaker Settings</a>
'
	)
);

$d->options['Slider'][] = array(
	'name'			=>	'Slider Items Limit',
	'id'			=>	'slider_items_limit',
	'type'			=>	'text',
	'default'		=>	'10',
	'description'	=>	'Amount of posts to display in the Slider. This applies to all Slider Managers.<br/><br/>
<u><strong>Note</strong></u>: <em>The Nivo Slider is limited to 10 posts maximum, due to layout reasons.</em>'
);


/* Portfolio */

$d->options['Portfolio'] = array();

$d->options['Portfolio'][] = array(
	'name'			=>	'Single Column Layout posts per page',
	'id'			=>	'single_column_ppp',
	'type'			=>	'text',
	'default'		=>	'2',
	'description'	=>	'Amount of Posts per Page to display in the <strong>Single Column</strong> Portfolio Pages.',
	'intro_message' => array(
		'title'		=>	'Adding Content to your Portfolio',
		'content'	=>	'
To add Content into your Portfolio, you can add <a href="' . admin_url('edit.php?post_type=portfolio_page') . '">Portfolio Pages</a>, which contain a set of <a href="' . admin_url('edit.php?post_type=portfolio') . '">Portfolio Posts</a>, organized by <a href="' . admin_url('edit-tags.php?taxonomy=portfolio-category&post_type=portfolio') . '">Categories</a>. <br/>
For each Portfolio Page, you can select the categories you want to use to populate its content. <br/><br/>
You also have the ability to set the Portfolio Page to use all the images inside it. This means that it will use the images, instead of populating its content from the Portfolio Categories. This way, you don\'t need to crete a Post for every entry on the page.

'
	)
);

$d->options['Portfolio'][] = array(
	'name'			=>	'Two Columns Layout posts per page',
	'id'			=>	'two_columns_ppp',
	'type'			=>	'text',
	'default'		=>	'4',
	'description'	=>	'Amount of Posts per Page to display in the <strong>Two Column</strong> Portfolio Pages.'
);

$d->options['Portfolio'][] = array(
	'name'			=>	'Three Columns Layout posts per page',
	'id'			=>	'three_columns_ppp',
	'type'			=>	'text',
	'default'		=>	'6',
	'description'	=>	'Amount of Posts per Page to display in the <strong>Three Column</strong> Portfolio Pages.'
);

$d->options['Portfolio'][] = array(
	'name'			=>	'Slider Effect',
	'id'			=>	'slider_effect',
	'type'			=>	'select',
	'fields'		=>	'fade, random, fold, sliceDown, sliceDownLeft, sliceUp, sliceUpLeft, sliceUpDown, sliceUpDownLeft',
	'description'	=>	'Effect to use on the Portfolio Gallery Slider.<br/><br/>

<strong>Note:</strong> <em>This setting affects the Portfolio Page Galleries, available in the Single Column Layout</em>.'
);

$d->options['Portfolio'][] = array(
	'name'			=>	'Slider Pieces',
	'id'			=>	'slider_pieces',
	'type'			=>	'text',
	'default'		=>	'15',
	'description'	=>	'Number of pieces to divide the Slides with.<br/><br/>

<strong>Note:</strong> <em>This setting affects the Portfolio Page Galleries, available in the Single Column Layout</em>.'
);

$d->options['Portfolio'][] = array(
	'name'			=>	'Transition Time',
	'id'			=>	'slider_speed',
	'type'			=>	'text',
	'default'		=>	'600',
	'description'	=>	'Amount of miliseconds between Transitions.<br/><br/>

<strong>Note:</strong> <em>This setting affects the Portfolio Page Galleries, available in the Single Column Layout</em>.'
);

$d->options['Portfolio'][] = array(
	'name'			=>	'Slider Pause Time',
	'id'			=>	'slider_timeout',
	'type'			=>	'text',
	'default'		=>	'3000',
	'description'	=>	'Amount of miliseconds to wait before changing Slides.<br/><br/>

<strong>Note:</strong> <em>This setting affects the Portfolio Page Galleries, available in the Single Column Layout</em>.'
);

/* Blog */

$d->options['Blog'] = array();

$d->options['Blog'][] = array(
	'name'			=>	'Blog Page',
	'id'			=>	'blog_page',
	'type'			=>	'page',
	'template'		=>	'template_blog.php',
	'description'	=>	'The WordPress page to be used as your Blog.<br/><br/>

<strong>Note</strong>: <em>You can leave this page blank, since it is only used for Layout Purposes.</em>


',
	'intro_message'	=> array(
		'title'		=>	'Setting up your Blog',
		'content'	=>	'
You can add content to your Blog by publishing <a href="' . admin_url('edit.php') . '">Posts</a>, organizing them with <a href="' . admin_url('edit-tags.php?taxonomy=category') . '">Categories</a>.<br/><br/>
If you choose to <u>Exclude Posts</u> from specific categories, the posts from the categories you select will not be displayed on your Homepage.<br/>
By choosing to <u>Only Show Posts</u> from specific categories, only the posts from those categories will be shown (Ignoring the Exclude Posts settings).

<br/>
'
	)
);

$d->options['Blog'][] = array(
	'name'			=>	'Blog Layout',
	'id'			=>	'blog_layout',
	'type'			=>	'select',
	'fields'		=>	'Normal, Full Width',
	'description'	=>	'Choose your desired Blog Layout.<br/><br/>
The Normal layout includes the Sidebar. The Full Width layout, does not include it.'
);

$d->options['Blog'][] = array(
	'name'			=>	'Blog Posts per Page:',
	'id'			=>	'blog_posts_per_page',
	'type'			=>	'text',
	'default'		=>	'3',
	'description'	=>	'Amount of Posts to display on the Blog.'
);

$d->options['Blog'][] = array(
	'name'			=>	'Blog Post Content',
	'id'			=>	'blog_excerpt',
	'type'			=>	'select',
	'fields'		=>	'Excerpt, Full Contents',
	'description'	=>	'How to show the Posts content on the Blog. Wether to show the Excerpt, or show the Full Post\'s contents.'
);

$d->options['Blog'][] = array(
	'name'			=>	'Exclude Posts from the following Categories:',
	'id'			=>	'blog_exclude_posts',
	'type'			=>	'taxonomies_checkbox',
	'taxonomy'		=>	'category'
);

$d->options['Blog'][] = array(
	'name'			=>	'Only Show Posts from the Following Categories <small>(Ignores excluded categories)</small>',
	'id'			=>	'blog_only_posts',
	'type'			=>	'taxonomies_checkbox',
	'taxonomy'		=>	'category',
);


/* Contact */

$d->options['Contact'] = array();

$d->options['Contact'][] = array(
	'name'			=>	'Contact Page',
	'id'			=>	'contact_page',
	'type'			=>	'page',
	'description'	=>	'Select the WordPress page to be used as your site\'s Contact page.'
);

$d->options['Contact'][] = array(
	'name'			=>	'Recipient Email',
	'id'			=>	'recipient_email',
	'type'			=>	'text',
	'default'		=>	get_bloginfo('admin_email'),
	'description'	=>	'All the messages from the Contact Form, will be sent to this Address.'
);

$d->options['Contact'][] = array(
	'name'			=>	'Contact Page Options',
	'type'			=>	'checkbox',
	'fields'		=>	array(
						'disable_phpmailer'						=>	'Disable PHPMailer. (Emails will not be sent from the Contact Form)'
	)
);


/* Advanced */

$d->options['Advanced'] = array();

$d->options['Advanced'][] = array(
	'name'			=>	'Custom CSS Code',
	'id'			=>	'custom_css',
	'type'			=>	'code',
	'rows'			=>	'15',
	'default'		=>	'',
	'description'	=>	'Specify your <u><strong>Custom CSS Code</strong></u> here. Useful if you want to hide or change the presentation of Elements.<br/>
<u><strong style>Note</strong></u>: <em>You do not need to include &lt;style&gt; tags, they are already included for you</em>.'
);

$d->options['Advanced'][] = array(
	'name'			=>	'Custom JavaScript Code',
	'id'			=>	'custom_javascript',
	'type'			=>	'code',
	'rows'			=>	'15',
	'default'		=>	'',
	'description'	=>	'Specify your <u><strong>Custom JavaScript Code</strong></u> here. Useful if you want to execute code after the page loads.<br
<u><strong>Note</strong></u>: <em>You do not need to include &lt;script&gt; tags, they are already included for you</em>.'
);


// META BOXES
// ==========


/* Posts Metabox */

$d->post_metabox['Long Title'] = array(
	'id'			=>	'long_title',
	'description'	=>	'Title to use instead of the Post name.',
	'type'			=>	'text'
);

$d->post_metabox['Description'] = array(
	'id'			=>	'post_description',
	'description'	=>	'Post Description to complement the title. Usually an explanation of the title. <br/>Replaces the publish date.',
	'type'			=>	'text'
);

$d->post_metabox['Post Image'] = array(
	'id'			=>	'post_image',
	'description'	=>	'Image to use for this post. This image is used to create the thumbnail image.',
	'type'			=>	'text',
	'mime'			=>	'image'
);

$d->post_metabox['Post Configuration'] = array(
	'id'			=>	'post_checkbox',
	'type'			=>	'checkbox',
	'values'		=>	array(
						'use_fullwidth'	=>	'Use Full Width Layout for this post.',
						'add_lightbox'	=>	'Enable Lightbox for Images inside this post.'
		),
);

$d->post_metabox['Redirect URL'] = array(
	'id'			=>	'redirect_url',
	'description'	=>	'URL to Redirect this Post to.',
	'type'			=>	'text'
);


/* Page Metabox */

$d->page_metabox['Long Title'] = array(
	'id'			=>	'long_title',
	'description'	=>	'Title to use instead of the Page name.',
	'type'			=>	'text'
);

$d->page_metabox['Description'] = array(
	'id'			=>	'post_description',
	'description'	=>	'Page Description to complement the title. Usually an explanation of the title.',
	'type'			=>	'text'
);


$d->page_metabox['Post Configuration'] = array(
	'id'			=>	'post_checkbox',
	'type'			=>	'checkbox',
	'values'		=>	array(
						'use_fullwidth'	=>	'Use Full Width Layout for this post.',
						'add_lightbox'	=>	'Enable Lightbox for Images inside this post.'
		),
);


$d->page_metabox['Redirect URL'] = array(
	'id'			=>	'redirect_url',
	'description'	=>	'URL to Redirect this Page to.',
	'type'			=>	'text'
);


/* Slideshow Metabox */

$d->slideshow_metabox['Long Title'] = array(
	'id'			=>	'long_title',
	'description'	=>	'Title to use instead of the Post name.',
	'type'			=>	'text'
);

$d->slideshow_metabox['Description'] = array(
	'id'			=>	'post_description',
	'description'	=>	'Post Description to complement the title. Usually an explanation of the title. <br/>Replaces the publish date.',
	'type'			=>	'text'
);

$d->slideshow_metabox['Slide Image'] = array(
	'id'			=>	'post_image',
	'description'	=>	'Image to use for this Slide.',
	'type'			=>	'text',
	'mime'			=>	'image'
);

$d->slideshow_metabox['Post Configuration'] = array(
	'id'			=>	'post_checkbox',
	'type'			=>	'checkbox',
	'values'		=>	array(
						'use_fullwidth'	=>	'Use Full Width Layout for this post.',
						'add_lightbox'	=>	'Enable Lightbox for Images inside this post.'
		),
);

$d->slideshow_metabox['Slider Position'] = array(
	'id'			=>	'slideshow_position',
	'description'	=>	'Position of this Post in the Slideshow',
	'type'			=>	'text',
	'scope'			=>	'numeric'
);

$d->slideshow_metabox['Redirect URL'] = array(
	'id'			=>	'redirect_url',
	'description'	=>	'URL to Redirect this Post to.',
	'type'			=>	'text'
);


/* Portfolio Page Metabox */

$d->portfolio_page_metabox['Long Title'] = array(
	'id'			=>	'long_title',
	'description'	=>	'Title to use instead of the Page name.',
	'type'			=>	'text'
);

$d->portfolio_page_metabox['Description'] = array(
	'id'			=>	'post_description',
	'description'	=>	'Page Description to complement the title. Usually an explanation of the title.',
	'type'			=>	'text'
);

$d->portfolio_page_metabox['Display <a href="' . admin_url('edit.php?post_type=portfolio') . '">Portfolio Posts</a> from the Following <a href="' . admin_url('edit-tags.php?taxonomy=portfolio-category&post_type=portfolio') . '">Categories</a>:'] = array(
	'id'			=>	'portfolio_categories',
	'type'			=>	'checkbox',
	'taxonomy'		=>	'portfolio-category'
);

$d->portfolio_page_metabox['Portfolio Layout'] = array(
	'id'			=>	'portfolio_layout',
	'type'			=>	'select',
	'values'		=>	'Single Column, Two Columns, Three Columns',
	'description'	=>	'Available Portfolio Layouts:<br/><br/>

<u><strong>Single Column</strong></u>: Will use Portfolio Posts in the Categories selected in this post. <br/>Every Portfolio Post creates a Slideshow, containing all the images inside it.<br/><br/>

<u><strong>Two Columns</strong></u>: Will use Portfolio Posts in the Categories selected in this post. <br/>Every Portfolio Post creates a single Portfolio Entry.<br/><br/>

<u><strong>Three Columns</strong></u>: Will use Portfolio Posts in the Categories selected in this post. <br/>Every Portfolio Post creates a single Portfolio Entry.<br/><br/>'
);

$d->portfolio_page_metabox['Posts per Page'] = array(
	'id'			=>	'posts_per_page',
	'description'	=>	'Amount of Posts per Page to use on this Portfolio Page.<br/>
	This overrides the default value on the <a href="' . admin_url('admin.php?page=theme_options#portfolio') . '">Theme Options</a>.<br/>
	If not set, the default value will be used.
		',
	'type'			=>	'text'
);

$d->portfolio_page_metabox['Recent Posts to be considered as New'] = array(
	'id'			=>	'consider_as_new',
	'description'	=>	'Amount of Recent Posts to be considered as New. Set to -1 to disable.<br/>
<strong>Note</strong>: This option is only effective for the Two &amp; Three Column Layouts.
',
	'type'			=>	'text',
	'default'		=>	'2'
);

$d->portfolio_page_metabox['Post Configuration'] = array(
	'id'			=>	'post_checkbox',
	'type'			=>	'checkbox',
	'values'		=>	array(
						'remove_post_permalinks'		=>	'Remove Links to Posts/Categories in this Gallery.',
		),
);

$d->portfolio_page_metabox['Redirect URL'] = array(
	'id'			=>	'redirect_url',
	'description'	=>	'URL to Redirect this Post to.',
	'type'			=>	'text'
);


/* Portfolio Post Metabox */

$d->portfolio_metabox['Long Title'] = array(
	'id'			=>	'long_title',
	'description'	=>	'Title to use instead of the Post name.',
	'type'			=>	'text'
);

$d->portfolio_metabox['Description'] = array(
	'id'			=>	'post_description',
	'description'	=>	'Post Description to complement the title. Usually an explanation of the title. <br/>Replaces the publish date.',
	'type'			=>	'text'
);

$d->portfolio_metabox['Post Image'] = array(
	'id'			=>	'post_image',
	'description'	=>	'Image to use for this post. This image is used to create the thumbnail image.',
	'type'			=>	'text',
	'mime'			=>	'image'
);

$d->portfolio_metabox['Portfolio Video'] = array(
	'id'			=>	'post_video',
	'description'	=>	'
 Adding a Video to this post will take preference over the Post Images specified.<br/><br/>

To set the video width/height, you can add the following parameters to the <u>end</u> of the video url:   <code>?width=XXX&height=XXX</code><br/><br/>

Allowed video sites are <a href="http://youtube.com">Youtube</a> / <a href="http://vimeo.com">Vimeo</a>. You can also use Quicktime .mov links.


',
	'type'			=>	'text',
);

$d->portfolio_metabox['Post Gallery/Video Height.'] = array(
	'id'			=>	'gallery_height',
	'description'	=>	'Height to use for the Slideshow Gallery created from this post, or its Embedded Video. <br/>This will also set the height of the Video in the Lightbox.',
	'type'			=>	'text',
	'default'		=>	'400'
);

$d->portfolio_metabox['Post Configuration'] = array(
	'id'			=>	'post_checkbox',
	'type'			=>	'checkbox',
	'values'		=>	array(
						'use_fullwidth'	=>	'Use Full Width Layout for this post.',
						'add_lightbox'	=>	'Enable Lightbox for Images inside this post.'
		),
);

$d->portfolio_metabox['Redirect URL'] = array(
	'id'			=>	'redirect_url',
	'description'	=>	'URL to Redirect this Post to.',
	'type'			=>	'text'
);


/* Metalinks */

$d->metalinks['Documentation'] = array(
	'icon'	=>	'help-icon.png',
	'url'	=>	'http://docs.der-design.com/handcrafted',
	'target' => '_blank'
);

$d->metalinks['Widgets'] = array(
	'icon'	=>	'settings-icon.png',
	'url'	=>	admin_url('widgets.php')
);

$d->metalinks['Menus'] = array(
	'icon'	=>	'settings-icon.png',
	'url'	=>	admin_url('nav-menus.php')
);

?>