// FBSage - Firebelly 2015
/*jshint latedef:false*/

// Good Design for Good Reason for Good Namespace
var FBSage = (function($) {

  var screen_width = 0,
      breakpoint_small = false,
      breakpoint_medium = false,
      breakpoint_large = false,
      breakpoint_array = [480,1000,1200],
      $document,
      $sidebar,
      loadingTimer,
      page_at;

  function _init() {
    // touch-friendly fast clicks
    FastClick.attach(document.body);

    // Cache some common DOM queries
    $document = $(document);
    $('body').addClass('loaded');

    // Set screen size vars
    _resize();

    // Fit them vids!
    $('main').fitVids();

    // _initNav();
    // _initSearch();
    // _initLoadMore();

    // Inject all of our svgs so we can grab them throughout the page with <svg class="..." role="img"><use xlink:href="#..."></use></svg> commands.
    _injectSvgSprite();

    // Esc handlers
    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        _hideSearch();
        _hideMobileNav();
        _hideContent();
      }
    });

    // Smoothscroll links
    $('a.smoothscroll').click(function(e) {
      e.preventDefault();
      var href = $(this).attr('href');
      _scrollBody($(href));
    });

    // Scroll down to hash afer page load
    $(window).load(function() {
      if (window.location.hash) {
        _scrollBody($(window.location.hash));
      }
    });

    // Add html markup and behavior for footer
    _initFooter();

    // Add color information to URL we are going to, handle transition effect
    _initPageTransition();

    // Handle revealing content of subsections
    _initContentReveal();

    // Add html markup and behavior for venetian blinds
    _initBlinds();

    // Add html markup and behavior for lines
    _initLines();

    // Add global overlay for clickouts
    _initGlobalOverlay();

  } // end init()

  function _scrollBody(element, duration, delay) {
    if ($('#wpadminbar').length) {
      wpOffset = $('#wpadminbar').height();
    } else {
      wpOffset = 0;
    }
    element.velocity("scroll", {
      duration: duration,
      delay: delay,
      offset: -wpOffset
    }, "easeOutSine");
  }

  // Inject all of our svgs so we can grab them throughout the page with <use xlink:href="#..."> commands.
  function _injectSvgSprite() {
    boomsvgloader.load('/app/themes/brian-thompson/assets/svgs/build/svgs-defs.svg');
  }

  function _initSearch() {
    $('.search-form:not(.mobile-search) .search-submit').on('click', function (e) {
      if ($('.search-form').hasClass('active')) {

      } else {
        e.preventDefault();
        $('.search-form').addClass('active');
        $('.search-field:first').focus();
      }
    });
    $('.search-form .close-button').on('click', function() {
      _hideSearch();
      _hideMobileNav();
    });
  }

  function _hideSearch() {
    $('.search-form').removeClass('active');
  }

  // Handles main nav
  function _initNav() {
    // SEO-useless nav toggler
    $('<div class="menu-toggle"><div class="menu-bar"><span class="sr-only">Menu</span></div></div>')
      .prependTo('header.banner')
      .on('click', function(e) {
        _showMobileNav();
      });
    var mobileSearch = $('.search-form').clone().addClass('mobile-search');
    mobileSearch.prependTo('.site-nav');
  }

  function _showMobileNav() {
    $('.menu-toggle').addClass('menu-open');
    $('.site-nav').addClass('active');
  }

  function _hideMobileNav() {
    $('.menu-toggle').removeClass('menu-open');
    $('.site-nav').removeClass('active');
  }

  function _initLoadMore() {
    $document.on('click', '.load-more a', function(e) {
      e.preventDefault();
      var $load_more = $(this).closest('.load-more');
      var post_type = $load_more.attr('data-post-type') ? $load_more.attr('data-post-type') : 'news';
      var page = parseInt($load_more.attr('data-page-at'));
      var per_page = parseInt($load_more.attr('data-per-page'));
      var category = $load_more.attr('data-category');
      var more_container = $load_more.parents('section,main').find('.load-more-container');
      loadingTimer = setTimeout(function() { more_container.addClass('loading'); }, 500);

      $.ajax({
          url: wp_ajax_url,
          method: 'post',
          data: {
              action: 'load_more_posts',
              post_type: post_type,
              page: page+1,
              per_page: per_page,
              category: category
          },
          success: function(data) {
            var $data = $(data);
            if (loadingTimer) { clearTimeout(loadingTimer); }
            more_container.append($data).removeClass('loading');
            if (breakpoint_medium) {
              more_container.masonry('appended', $data, true);
            }
            $load_more.attr('data-page-at', page+1);

            // Hide load more if last page
            if ($load_more.attr('data-total-pages') <= page + 1) {
                $load_more.addClass('hide');
            }
          }
      });
    });
  }

  // Track ajax pages in Analytics
  function _trackPage() {
    if (typeof ga !== 'undefined') { ga('send', 'pageview', document.location.href); }
  }

  // Track events in Analytics
  function _trackEvent(category, action) {
    if (typeof ga !== 'undefined') { ga('send', 'event', category, action); }
  }

  // Called in quick succession as window is resized
  function _resize() {
    screenWidth = document.documentElement.clientWidth;
    breakpoint_small = (screenWidth > breakpoint_array[0]);
    breakpoint_medium = (screenWidth > breakpoint_array[1]);
    breakpoint_large = (screenWidth > breakpoint_array[2]);
  }

  function _initFooter() {
    var html = '<div class="footer-tab" aria-hidden="true"><button class="footer-toggle">+</button></div>';  
    $(html).prependTo('.site-footer').click(function(e) {
      e.preventDefault();
      if( $('.site-footer').hasClass('closed') ){
        _openFooter();
      } else {
        _closeFooter();
      }
    });
  }  
  function _closeFooter(animDur) {
    animDur = animDur || 200;
    $('.site-footer').addClass('closed');
    setTimeout(function() { $('.footer-toggle').empty().append('+'); }, 200);
  }
  function _openFooter(animDur) {
    animDur = animDur || 200;
    $('.site-footer').removeClass('closed');
    $('.footer-toggle').empty().append('–');
  }


  // Add color information to URL we are going to, handle transition effect
  function _initPageTransition() {

    // Define a constant to store whether we are transitioning between pages (to elsewhere block certain behaviors in this case)
    _isTransitioning = false;

    // Hijack links
    $('a:not(.fake-link)').each(function() {
      $(this).click(function(e) {

        if(_isTransitioning) { // Don't be able to click links while we are already transitioning to a new page
          e.preventDefault();
        } else {

          var linkUrl = $(this)[0].href; // Get my dest url
          if( _get_hostname(linkUrl) === document.location.hostname ) { // Apply transition only if its an in-site URL!  Otherwise, skip this and proceed to default link behavior
            _isTransitioning = true;
            e.preventDefault();

            // Throw lines to front and change their color
            $('.lines').addClass('page-transitioning');

            // Find starting blind
            var startingBlindNum = _closestX( $('.blinds.-page .blind'), $(this).offset().left ).data('blind-num'); //Math.floor($(this).offset().left / $('.blinds.-page .blind').width()); // Which blind # corresponds to the X location of this link

            // Trigger blinds, go to href on complete
            _blinds( $('.blinds.-page .blind'), 'show', startingBlindNum, function() {
              window.location.href = linkUrl;
            });

          }
        }
      });
    });
  }
  function _get_hostname(url) {
    // Get the hostname from url using regexp
    var base = url.match(/^http:\/\/[^/]+/);
    var host = base[0] ? base[0].split('//')[1] : null;
    return host ? host : null;
  }

  // Handle animating blinds
  function _blinds($blinds,showOrHide,startingBlindNum,onDone,duration) {
    // Defaults
    $blinds = $blinds || $('.blind');
    showOrHide = showOrHide || 'show';
    startingBlindNum = startingBlindNum || 0;
    onDone = onDone || undefined;
    duration = duration || 500;

    // We gotta find the container because so we can add/remove classes.
    $container = $blinds.closest('.blinds');

    // Which is the final onscreen blind to open or close?
    var numBlinds = $blinds.filter(function () {
      return $(this).offset().left < $(window).width();
    }).length; // Number of on-screen blinds
    var finalBlindNum = startingBlindNum > numBlinds - startingBlindNum ? 0 : numBlinds-1;

    if (showOrHide === 'show') { $container.removeClass('-hidden'); }

    // Animate each blind.
    $($blinds).each(function() {
      var thisBlindNum = $(this).data('blind-num');
      $(this).velocity( (showOrHide === 'show' ? 'transition.blindShow' : 'transition.blindHide') , { 
        delay: (Math.abs(startingBlindNum-thisBlindNum)*100), 
        duration: duration,
        easing: [1,0.75,0.5,1],
        complete: (thisBlindNum!==finalBlindNum) ? undefined : function() {
          if (showOrHide === 'hide') { $container.addClass('-hidden'); } //display: none so no interfering with pointer events
          onDone();
        }
      });
    });
  }
  function _closestX($elements,x){
    var closestDist = 9999;
    var closestElement = false;
    if ($elements.length) {
      $elements.each(function() {
        var dist = Math.abs( $(this).offset().left-x );
        if (dist < closestDist) {
          closestDist = dist;
          closestElement = $(this);
        }
      });
    }
    return closestElement;
  }


  // Add color information to URL we are going to, handle transition effect
  function _initContentReveal() {
    // Here is a global variable to remember which blind was the first to open;
    _contentBlindStartingBlindNum = 0;
    var html = '<div class="revealed-content main-area-wrap" aria-hidden="true"><div class="body-wrap"></div></div>';
    $(html).appendTo('body');
    $('.revealed-content').velocity('fadeOut',0);

    $('.reveal-content').click(function(e) {
      e.preventDefault();
      _contentBlindStartingBlindNum = _closestX( $('.blinds.-content .blind'), $(this).offset().left ).data('blind-num');
      var $content = $(this).closest('.step').find('.content-to-reveal');
      _revealContent($content);
    });
  }  
  function _hideContent() {
    _returnToYourSlumberAlmightyOverlay();
    $('.revealed-content').velocity('fadeOut',{
      duration: 100,
      complete: function () {
        $('.lines.-content').velocity('fadeOut',100);
        _blinds($('.blinds.-content .blind'),'hide',_contentBlindStartingBlindNum);
      }
    });
  }
  function _revealContent($content) {
    _awakenTheAlmightyOverlay();
    $('.lines.-content').velocity('fadeIn',100);
    $('body').velocity('scroll',200);
    _blinds($('.blinds.-content .blind'),'show',_contentBlindStartingBlindNum,function () {
      $('.revealed-content .body-wrap').empty();
      $content.clone(true, true).contents().appendTo('.revealed-content .body-wrap');
      $('.revealed-content').velocity('fadeIn',{ 
        duration: 100
      });
    });
  }

  // Add global overlay for clickouts
  function _initGlobalOverlay() {
    var html = '<div class="almighty-global-overlay -hidden" aria-hidden="true"></div>';
    $(html).appendTo('body');
    $('.almighty-global-overlay').click(function() {
      _returnToYourSlumberAlmightyOverlay();
      _hideContent();
    });
  }
  function _awakenTheAlmightyOverlay() {
    $('.almighty-global-overlay').removeClass('-hidden');
  }
  function _returnToYourSlumberAlmightyOverlay() {
    $('.almighty-global-overlay').addClass('-hidden');
  }

    // Add html markup and behavior for venetian blinds.  We have two sets of these.
  function _initBlinds() {
    // Add main section content blinds
    var html = '<div class="blinds -content -hidden" aria-hidden="true">';
    for (i=0; i<6; i++) { 
      html+='<div class="blind" data-blind-num="'+i+'"></div>'; 
    }
    html+='</div>';
    $(html).appendTo('body');

    // Ditto page transition blinds
    html = '<div class="blinds -page -hidden" aria-hidden="true">';
    for (i=0; i<15; i++) { 
      html+='<div class="blind" data-blind-num="'+i+'"></div>'; 
    }
    html+='</div>';
    $(html).appendTo('body');

    // Register velocity animations
    // Why handle this with velocity?  Why not add/remove a class?  Because velocity has a 'complete' callback and we need to time some things to fire precisely with animation completion (like going to destination url). That's why.
    $.Velocity
    .RegisterEffect("transition.blindHide", {
      defaultDuration: 500,
      calls: [
        [ { translateX: '-50%', scaleX: '0.0001'} ]
      ]
    });

    $.Velocity
    .RegisterEffect("transition.blindShow", {
      defaultDuration: 500,
      calls: [
        [ { translateX: '0', scaleX: '1'} ]
      ]
    });

    //We want to open them with velocity.  They close as expected when you do this.  Otherwise, they can be buggy.
    $('.blind').velocity("transition.blindHide", { 
      duration: 0 // MY GOD MAN, THERE'S NO TIME. DO IT NOW!!!!
    });
  }

    // Add html markup and behavior for lines
  function _initLines() {
    var html = '<div class="lines" aria-hidden="true">';
    for (i=0; i<15; i++) { 
      html+='<div class="line"></div>'; 
    }
    html+='</div>';
    $(html).prependTo('body');
    $(html).appendTo('.site-nav-wrap');

    html = '<div class="lines -content" aria-hidden="true">';
    for (i=0; i<5; i++) { 
      html+='<div class="line"></div>'; 
    }
    html+='</div>';
    $(html).appendTo('body');
    $('.lines.-content').velocity('fadeOut',0);
  }

  // Public functions
  return {
    init: _init,
    resize: _resize,
    scrollBody: function(section, duration, delay) {
      _scrollBody(section, duration, delay);
    }
  };

})(jQuery);

// Fire up the mothership
jQuery(document).ready(FBSage.init);

// Zig-zag the mothership
jQuery(window).resize(FBSage.resize);
