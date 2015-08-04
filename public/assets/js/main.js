var DESKTOP_BREAK_POINT = 960; //px
var TABLET_BREAK_POINT = 768; //px
var MOBILE_BREAK_POINT = 480; //px

function isMobile() {
    if (jQuery(window).width() >= MOBILE_BREAK_POINT) {
      return false;
    }
    return true;
}

function isTablet() {
    if (jQuery(window).width() >= TABLET_BREAK_POINT) {
      return false;
    }
    return true;
}

function isDesktop() {
    if (jQuery(window).width() >= DESKTOP_BREAK_POINT) {
      return true;
    }
    return false;
}

$(document).ready(function() {
    init();
});

function init(){   
    headerSearch();
    initSmoothScrolling();
    mobileMenu();
}

function headerSearch(){
    $(".icon-search").click(function(){
        $(".header-search").toggleClass("header-search-open");
    });
}

function mobileMenu(){
    var menuToggle = $('#js-mobile-menu').unbind();
    $('#js-navigation-menu').removeClass("show");

    menuToggle.on('click', function(e) {
        e.preventDefault();
        $('#js-navigation-menu').slideToggle(function(){
            if($('#js-navigation-menu').is(':hidden')) {
                $('#js-navigation-menu').removeAttr('style');
            }
        });
    });
}

function initSmoothScrolling() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top - 10
        }, 1000);
        return false;
      }
    }
  });
}

