<?php /* mod-template */ global $d;

// Template Module
// ===============

// Create Module Instance
$layout_module = new DerModule('piecemaker', 'Piecemaker 3D');


/* Logo */

$d->piecemaker['Piecemaker Settings'][] = array(
	'name'			=>	'Slider Height',
	'id'			=>	'slider_height',
	'type'			=>	'text',
	'default'		=>	'323',
	'description'	=>	'Height for the Piecemaker 3D Slider.'
);

$d->piecemaker['Piecemaker Settings'][] = array(
	'name'			=>	'Tween Type',
	'id'			=>	'tween_type',
	'type'			=>	'select',
	'fields'		=>	'
		easeOutCubic, easeInCubic, easeInOutCubic, easeInCirc, easeInOutCirc, easeOutCirc, easeInSine, easeOutSine, easeInOutSine, easeInQuad, easeOutQuad, easeInOutQuad,
		easeInQuart, easeOutQuart, easeInOutQuart, easeInQuint, easeOutQuint, easeInOutQuint, easeInExpo,
		easeOutExpo, easeInOutExpo, easeInElastic, easeOutElastic, easeInOutElastic, easeInBack, easeOutBack, easeInOutBack, easeInBounce, easeOutBounce,
		easeInOutBounce',
	'description'	=>	'Easing to use in the Transitions. This will determine how the cubes will move in time. <br/><br/>See <a href="http://hosted.zeh.com.br/tweener/docs/en-us/misc/transitions.html">Transition Reference</a>.'
);

$d->piecemaker['Piecemaker Settings'][] = array(
	'name'			=>	'Segments',
	'id'			=>	'segments',
	'type'			=>	'text',
	'default'		=>	'10',
	'description'	=>	'Number of segments, in which the images will be sliced.'
);

$d->piecemaker['Piecemaker Settings'][] = array(
	'name'			=>	'Pause Time',
	'id'			=>	'autoplay',
	'type'			=>	'text',
	'default'		=>	'5',
	'description'	=>	'Number of seconds to wait before changing to the next image.'
);

$d->piecemaker['Piecemaker Settings'][] = array(
	'name'			=>	'Tween Time',
	'id'			=>	'tween_time',
	'type'			=>	'text',
	'default'		=>	'1.2',
	'description'	=>	'Number of seconds for each element to be turned.'
);

$d->piecemaker['Piecemaker Settings'][] = array(
	'name'			=>	'Tween Delay',
	'id'			=>	'tween_delay',
	'type'			=>	'text',
	'default'		=>	'0.1',
	'description'	=>	'Number of seconds from one element starting to turn to the next element starting.'
);

$d->piecemaker['Piecemaker Settings'][] = array(
	'name'			=>	'Expand',
	'id'			=>	'expand',
	'type'			=>	'text',
	'default'		=>	'30',
	'description'	=>	'To which extent are the cubes moved away from each other when tweening.'
);

$d->piecemaker['Piecemaker Settings'][] = array(
	'name'			=>	'Z-Distance',
	'id'			=>	'z_distance',
	'type'			=>	'text',
	'default'		=>	'0',
	'description'	=>	'To which extent are the cubes moved on z axis when being tweened. <br/><br/>Negative values bring the cube closer to the camera, positive values take it further away. <br/><br/>A good range is roughly between -200 and 700.'
);

?>
