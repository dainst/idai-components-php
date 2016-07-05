/**
 * 
 * idai-components-php.js
 * 
 * this brings the dropdown menus in the diai-navbar to life,
 * if you don't have bootstrap.js
 * or angular.js
 * it needs jquery, but is compatible to very old versions of it
 * 
 */

jQuery(document).ready(function(){

	var bindingFn = (parseFloat(jQuery.fn.jquery) <= 1.7) ? 'delegate' : 'on';
	
	jQuery('html').click(function(event) {
    	$('.dropdown-menu').toggle(false);
	});
	
	jQuery('#dai_navbar .dropdown-toggle').click(function(event) {
		$('.dropdown-menu').toggle(false);
		$(this).parent().find('.dropdown-menu').toggle();
		event.stopImmediatePropagation();
    });
});