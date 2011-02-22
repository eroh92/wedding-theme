
/*  JS Helper Functions & Extensions
    Copyright (c) Ernesto Mendez 2010.
    http://der-design.com  */

IE = jQuery.browser.msie;

NOT_IE = ! jQuery.browser.msie;

jQuery(document).ready(function() {common_lib_functions();});

CACHE_TIMEOUT = 5; // Cookies will expire in 5 minutes

function get_metavar(meta) {

	var selector = 'meta[name=' + meta + ']';

	return jQuery(selector).attr('content');

}

function object_copy(obj) {

	var newob = {};

	for (var i in obj) {newob[i] = obj[i];}

	return newob;

}

function append_slash(url) {

	url += ( url[url.length-1] != '/' ) ? '/' : '';

	return url;

}

function add_action(where, callback) {

	try {hooks;} catch(e) {hooks = [];}

	if ( ! hooks[where] ) {hooks[where] = [];}

	hooks[where].push(callback);

}

function do_action(where) {

	try {hooks} catch(e) {return false;}

	if ( ! hooks[where] )  {return false;}

	for ( var i in hooks[where] ) {

		var c = hooks[where][i];

		c();

	}
}

function is_ie7() {

	return (navigator.appVersion.indexOf("MSIE 7.")==-1) ? false : true;

}

function is_ie8() {

	return (navigator.appVersion.indexOf("MSIE 8.")==-1) ? false : true;

}

function maxval(values) {

	var max = 0;

	for ( var i in values ) {

		max = ( values[i] >= max ) ? values[i] : max;

	}

	return max;

}

function cycle(increment, current, length) {

	increment = parseInt(increment);

	current = parseInt(current);

	length = parseInt(length);

    var pos = current + increment;

    if (pos == 0) {return length;}

    if (pos > length) {return 1;}

    return pos;

}

function basename(url) {

	url = url.split('/');

	var name = url[url.length-1];

	return name;
}

function in_array(needle, haystack) {

	for ( var i=0; i < haystack.length; i++) {

		if ( needle == haystack[i]) {return true;}

	}

	return false;
}

function common_lib_functions() {

	color_theme = get_metavar('theme');

}

function console_log(data) {

	if (NOT_IE) {
		console.log(data);
	}

}

function is_apple_safari() {

	try {return IS_APPLE_SAFARI;} catch(e) {

		if (jQuery.browser.safari ) {
			var userAgent = navigator.userAgent.toLowerCase();
			IS_APPLE_SAFARI = ( userAgent.match(/version\//) ) ? true : false;
			return IS_APPLE_SAFARI;
		} else {
			return false;
		}

	}

}