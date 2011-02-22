<?php /* mod-nivo */ global $d;

// Nivo Module
// ===========

// Create Module Instance
new DerModule('nivo', 'Nivo Slider');

add_action('der_style', 'mod_nivo_styles');

/* Logo */

$d->nivo['Nivo Slider Settings'] = array();

$d->nivo['Nivo Slider Settings'][] = array(
	'name'			=>	'Slider Height',
	'id'			=>	'slider_height',
	'type'			=>	'text',
	'default'		=>	'323',
	'description'	=>	'Height for the Nivo Slider.'
);

$d->nivo['Nivo Slider Settings'][] = array(
	'name'			=>	'Slider Effect',
	'id'			=>	'slider_effect',
	'type'			=>	'select',
	'fields'		=>	'fade, random, fold, sliceDown, sliceDownLeft, sliceUp, sliceUpLeft, sliceUpDown, sliceUpDownLeft',
	'description'	=>	'Effect to use on your Slider.'
);

$d->nivo['Nivo Slider Settings'][] = array(
	'name'			=>	'Slider Pieces',
	'id'			=>	'slider_pieces',
	'type'			=>	'text',
	'default'		=>	'15',
	'description'	=>	'Number of pieces to divide the Slides with.'
);

$d->nivo['Nivo Slider Settings'][] = array(
	'name'			=>	'Transition Time',
	'id'			=>	'slider_speed',
	'type'			=>	'text',
	'default'		=>	'600',
	'description'	=>	'Amount of miliseconds between Transitions.'
);

$d->nivo['Nivo Slider Settings'][] = array(
	'name'			=>	'Slider Pause Time',
	'id'			=>	'slider_timeout',
	'type'			=>	'text',
	'default'		=>	'3000',
	'description'	=>	'Amount of miliseconds to wait before changing Slides.'
);

$d->nivo['Nivo Slider Settings'][] = array(
	'name'			=>	'Slider Options',
	'type'			=>	'checkbox',
	'fields'		=>	array(
						'remove_slideshow_title' => 'Don\'t show the Slider Title',
						'remove_slideshow_controls' => 'Don\'t show the Slider Controls',
						'slideshow_permalink' => 'Add the Post Permalink to the Slider Title',
						'enable_direction_nav' => 'Enable the Directional Navigation on the Slider',
						'direction_nav_autohide' => 'Automatically hide the Directional Navigation',
						'pause_on_hover' => 'Pause the Slideshow when the mouse is over the Slider',
						'manual_advance' => 'Disable Autoplay for the Slider'
	)
);


// Functions

function mod_nivo_effects($context=MOD_NIVO) { global $_der;

	$_der->set_options_context($context);

	$effect_ids = array('sliceDown', 'sliceDownLeft', 'sliceUp', 'sliceUpLeft', 'sliceUpDown', 'sliceUpDownLeft', 'fold', 'fade', 'random');

	if ( $_der->getval('random') ) { $effects = 'random'; } else {

		$effects = array();

		foreach($effect_ids as $id) { $effects[] = ( $_der->getval($id) ) ? $id : null; }

		der_clean_array($effects);

		if ( empty($effects) ) { $effects[] = 'fade'; }

		$effects = implode(',', $effects);

	}

	$_der->reset_options_context();

	echo $effects;
	
}

function mod_nivo_styles() { global $_der;

	if ( $_der->getval('remove_slideshow_title', MOD_NIVO) ) {

		echo "#slideshow .title { visibility: hidden }\n";

	}

}


?>
