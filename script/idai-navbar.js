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

window.isCollapsed = true;

jQuery(document).ready(function(){
	
	var openDropdown = function(event) {
		$('.dropdown-menu').toggle(false);
		$(this).parent().find('.dropdown-menu').toggle();
		event.stopImmediatePropagation();
    }
	
	var closeDropDown = function(event) {
		$('.dropdown-menu').toggle(false);
	}
	
	var navbarToggle = function(event) {
		window.isCollapsed = !window.isCollapsed;
		$('#collapsable_navbar').toggleClass('in', !window.isCollapsed);
	}
	
	jQuery('#dai_navbar .dropdown-toggle').mouseenter(openDropdown);
	jQuery('#dai_navbar .dropdown-toggle').click(openDropdown);
	jQuery('.dropdown-menu').parent().mouseleave(closeDropDown);
	jQuery('body').click(closeDropDown);
	jQuery('#dai_navbar .navbar-toggle').click(navbarToggle);
	
	jQuery('.idai-infobox-toggle').click(function(evt) {
		var box = jQuery(this).find('.idai-infobox');
		if (box.css('display') != 'block') {
			jQuery('.idai-infobox-toggle .idai-infobox').toggle(false);
		}
		box.toggle();
	})
	
});