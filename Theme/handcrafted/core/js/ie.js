
/*  Internet Explorer specific JS Code
    Copyright (c) Ernesto Mendez 2010.
    http://der-design.com  */

(function($) {

$(document).ready(function() {

	last_child_fix();

});

function last_child_fix() {
	$('#header .navigation > ul > li:last-child').css({marginRight: '0'});
	$('#home ul.posts > li.clear:last-child').css({visibility: 'hidden', marginBottom: '-1px'});
	$('blockquote p:last-child').css({marginBottom: 0});
	$('#header .navigation > ul > li.special:last-child ').css({marginRight: 0});
	$('ul.posts li.post .excerpt p:last-child').css({marginBottom: 0, paddingBottom: 0});
	$('.widget > p:last-child, .widget > ul:last-child, .widget > ul:last-child > li:last-child, .widget > form:last-child, .widget > table:last-child, .widget > blockquote:last-child, .widget > blockquote p:last-child').css({marginBottom: 0});
	$('.widget ul li ul li:last-child ').css({marginBottom: 0, paddingBottom: 0, borderBottom: 'none'});
	$('#footer .column .widget:last-child').css({marginBottom: 0});
	//$('').css({});

}

})(jQuery);

