// FBSage - Firebelly 2015
/*jshint latedef:false*/

// Good Design for Good Reason for Good Namespace
var FBSage = (function($) {

  var screen_width = 0,
      breakpoint_small = false,
      breakpoint_medium = false,
      breakpoint_large = false,
      breakpoint_array = [480,768,900],
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

    // Whether we are animating (to elsewhere block certain behaviors in this case)
    _isAnimating = false;

    // Add global overlay for clickouts
    _initGlobalOverlay();

    // Set screen size vars
    _resize();

    // Fit them vids!
    $('main').fitVids();

    _initMobileNav();
    // _initSearch();
    // _initLoadMore();

    // Inject all of our svgs so we can grab them throughout the page with <svg class="..." role="img"><use xlink:href="#..."></use></svg> commands.
    _injectSvgSprite();

    // Esc handlers
    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        // _hideSearch();
        // _hideMobileNav();
        _closePopup();
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
    _initPopup();

    // Add html markup and behavior for venetian blinds
    _initBlinds();

    // Add html markup and behavior for lines
    _initLines();

    // Place and dictate behavior for images
    _initImages();

    // Add functions to supplement CF7 form handling
    _initContactForm();

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

  // function _initSearch() {
  //   $('.search-form:not(.mobile-search) .search-submit').on('click', function (e) {
  //     if ($('.search-form').hasClass('active')) {

  //     } else {
  //       e.preventDefault();
  //       $('.search-form').addClass('active');
  //       $('.search-field:first').focus();
  //     }
  //   });
  //   $('.search-form .close-button').on('click', function() {
  //     _hideSearch();
  //     _hideMobileNav();
  //   });
  // }

  // function _hideSearch() {
  //   $('.search-form').removeClass('active');
  // }

  // Handles main nav
  function _initMobileNav() {
    // SEO-useless nav toggler
    $('<button class="open-popup mobile-nav-open" aria-hidden="true" data-content=".menu-main-menu-container"><svg class="hamburger" role="img"><use xlink:href="#hamburger"></use></svg></div>')
      .appendTo('.site-header').click(function() {
        $('.popup').addClass('holding-mobile-nav');
      });
  }
  function _resizeMobileNav() {
    if(breakpoint_medium && $('.popup ul#menu-main-menu').length) {
      _closePopup();
    }
  }

  // function _showMobileNav() {
  //   $('.menu-toggle').addClass('menu-open');
  //   $('.site-nav').addClass('active');
  // }

  // function _hideMobileNav() {
  //   $('.menu-toggle').removeClass('menu-open');
  //   $('.site-nav').removeClass('active');
  // }

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

  // // Track ajax pages in Analytics
  // function _trackPage() {
  //   if (typeof ga !== 'undefined') { ga('send', 'pageview', document.location.href); }
  // }

  // // Track events in Analytics
  // function _trackEvent(category, action) {
  //   if (typeof ga !== 'undefined') { ga('send', 'event', category, action); }
  // }

  // Called in quick succession as window is resized
  function _resize() {
    screenWidth = document.documentElement.clientWidth;
    breakpoint_small = (screenWidth > breakpoint_array[0]);
    breakpoint_medium = (screenWidth > breakpoint_array[1]);
    breakpoint_large = (screenWidth > breakpoint_array[2]);

    _resizeMobileNav();
    _resizeFooter();
  }

  // Add global overlay for clickouts
  function _initGlobalOverlay() {
    var html = '<div class="almighty-global-overlay -hidden" aria-hidden="true"></div>';
    $(html).appendTo('body');
  }
  function _awakenTheAlmightyOverlay() {
    $('.almighty-global-overlay').removeClass('-hidden');
  }
  function _returnToYourSlumberAlmightyOverlay() {
    $('.almighty-global-overlay').addClass('-hidden');
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

    _resizeFooter();

    $footerWaypoint = $('<div class="invisible-waypoint -footer" aria-hidden="true"></div>')
    .appendTo('#primary-site-content');
    _resizeFooter();
    $footerWaypoint.waypoint({
      offset: 'bottom-in-view',
      handler: function(direction) {
        if(direction==='down' && $(document).height > $(window.width)) {
          _openFooter();
        }
        if(direction==='up'){
          _closeFooter();
        }
      }
    });
  }  
  function _closeFooter(animDur) {
    animDur = animDur || 200;
    $('.site-footer').addClass('closed');
    setTimeout(function() { $('.footer-toggle').empty().append('+'); }, 200);
  }
  function _openFooter(animDur) {
    if(!$('.popup.showing').length) {
      animDur = animDur || 200;
      $('.site-footer').removeClass('closed');
      $('.footer-toggle').empty().append('–');
    }
  }
  function _resizeFooter() {
    var footerHeight = $('.site-footer').outerHeight();
    $('#primary-site-content').css('margin-bottom',(breakpoint_medium ? footerHeight : 0));
    $('.invisible-waypoint.-footer').css('bottom', -footerHeight+1);
  }

  // Add color information to URL we are going to, handle transition effect
  function _initPageTransition() {
    // Hijack links
    $('a:not(.fake-link)').each(function() {
      $(this).click(function(e) {

        if(_isAnimating) { // Don't be able to click links while we are already transitioning to a new page
          e.preventDefault();
        } else {

          var linkUrl = $(this)[0].href; // Get my dest url
          if( _get_hostname(linkUrl) === document.location.hostname ) { // Apply transition only if its an in-site URL!  Otherwise, skip this and proceed to default link behavior
            _isAnimating = true;
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
  function _blinds($blinds,showOrHideTheBlinds,startingBlindNum,onDone,useHiddenClass,duration) {
    // Defaults
    $blinds = $blinds || $('.blind');
    showOrHideTheBlinds = showOrHideTheBlinds || 'show';
    startingBlindNum = startingBlindNum || 0;
    onDone = onDone || undefined;
    duration = duration || 200;
    useHiddenClass = typeof useHiddenClass === 'undefined' ? true : useHiddenClass;

    // We gotta find the container because so we can add/remove classes.
    $container = $blinds.closest('.blinds');

    // Which is the final onscreen blind to open or close?
    var numBlinds = $blinds.filter(function () {
      return $(this).offset().left < $(window).width();
    }).length; // Number of on-screen blinds
    var finalBlindNum = startingBlindNum > numBlinds - startingBlindNum ? 0 : numBlinds-1;

    if (useHiddenClass && showOrHideTheBlinds === 'show') { $container.removeClass('-hidden'); }

    // Animate each blind.
    $($blinds).each(function() {
      var thisBlindNum = $(this).data('blind-num');
      $(this).velocity( (showOrHideTheBlinds === 'show' ? 'transition.blindShow' : 'transition.blindHide') , { 
        delay: (Math.abs(startingBlindNum-thisBlindNum)*70), 
        duration: duration,
        easing: [1,0.75,0.5,1],
        complete: (thisBlindNum!==finalBlindNum) ? undefined : function() {
          if (useHiddenClass && showOrHideTheBlinds === 'hide') { $container.addClass('-hidden'); } //display: none so no interfering with pointer events
          if (onDone) { onDone(); }
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
  function _initPopup() {
    // Here is a global variable to remember which blind was the first to open;
    _popupStartingBlindNum = 0;
    var html = '<div class="popup" aria-hidden="true"><div class="body-wrap"><div class="content-holder"></div><button class="close" aria-hidden="true">x</button></div></div>';
    $(html).appendTo('body');
    $('.popup .body-wrap').velocity('fadeOut',0);

    $('.open-popup').click(function(e) {
      e.preventDefault();
      if(!_isAnimating) {
        _popupStartingBlindNum = _closestX( $('.popup .blind'), $(this).offset().left ).data('blind-num');
        var $content = $($(this).data('content'));
        _openPopup($content);
      }
    });
    $('.switch-content').click(function(e) {
      e.preventDefault();
      if(!_isAnimating) {
        var $content = $($(this).data('content'));
        _switchPopupContent($content);
      }
    });

    $('.almighty-global-overlay, .popup .close').click(function() {
      if(!_isAnimating) {
        _closePopup();
      }
    });
  }  
  function _closePopup() {
    _isAnimating = true;
    _returnToYourSlumberAlmightyOverlay();
    $('.popup .body-wrap').velocity('fadeOut',{
      duration: 200,
      complete: function () {
        $('.popup .lines').velocity('fadeOut',{ 
          duration: 200
        });
        _blinds($('.popup .blind'),'hide',_popupStartingBlindNum, function() {
            _isAnimating = false;
            $('.popup').removeClass('showing').removeClass('holding-mobile-nav');
        });
      }
    });
  }
  function _openPopup($content) {
    _closeFooter();
    _isAnimating = true;
    $('.popup').addClass('showing');
    _awakenTheAlmightyOverlay();
    $('.popup .lines').velocity('fadeIn',200);
    _blinds($('.popup .blind'),'show',_popupStartingBlindNum,function () {
      $('.popup .content-holder').empty();
      $content.clone(true, true).contents().appendTo('.popup .content-holder');
      $('.popup .body-wrap').velocity('fadeIn',{ 
        duration: 200,
        complete: function() {
          $('.popup').velocity('scroll',200);
          _isAnimating = false;
        }
      });
    });
  }
  function _switchPopupContent($content) {
    _isAnimating = true;
    $('.popup .body-wrap').velocity('fadeOut',{ 
      duration: 200,
      complete: function() {
        $('.popup .content-holder').empty();
        $content.clone(true, true).contents().appendTo('.popup .content-holder');
        $('.popup .body-wrap').velocity('fadeIn',{ 
          duration: 200,
          complete: function() {
            $('.popup').velocity('scroll',200);
            _isAnimating = false;
          }
        });
      }
    });
  }

    // Add html markup and behavior for venetian blinds.  We have two sets of these.
  function _initBlinds() {
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

    // Add main section content blinds
    _makeBlinds(6,'.popup','-hidden');

    // Ditto page transition blinds
    _makeBlinds(15,'body','-page -hidden');

        //We want to open them with velocity.  They close as expected when you do this.  Otherwise, they can be buggy.
    $('.blind').velocity("transition.blindHide", {duration: 0});

    // Add image content blinds
    // _makeBlinds(3,'.floater-image.-portrait, .inline-image:not(.mobile-image).-portrait:not(.-one):not(.-one)');
    _makeBlinds(5,'.inline-image.-one');
    // _makeBlinds(4,'.floater-image.-landscape, .inline-image:not(.mobile-image).-landscape');
    _makeBlinds(8,'.mobile-image');


  }
  function _makeBlinds(n,container,cssClass) {
    cssClass = cssClass || '';
    var html = '<div class="blinds '+cssClass+'" aria-hidden="true">';
    for (i=0; i<n; i++) { 
      html+='<div class="blind" data-blind-num="'+i+'"></div>'; 
    }
    html+='</div>';
    return $(html).prependTo(container);
  }

    // Add html markup and behavior for lines
  function _initLines() {
    // Page Lines
    _makeLines(15,'body');
    // Nav Lines
    _makeLines(15,'.site-nav');
    // Lines behind popups
    _makeLines(5,'.popup').velocity('fadeOut',0);
    // Lines in front of images
    _makeLines(5,'.floater-image');
  }
  function _makeLines(n,container){
    html = '<div class="lines" aria-hidden="true">';
    for (i=0; i<n; i++) { 
      html+='<div class="line"></div>'; 
    }
    html+='</div>';
    return $(html).prependTo(container);

  }

function FloaterImage($image,order) {

  console.log('new FloaterImage '+order);

  // Settings
  this.maxHealthy = 2;

  // My self referential vars
  var me = this;
  var $me = $image;

  // My stats
  this.neverShown = true;
  this.order = order;
  this.portrait = $me.hasClass('-portrait');
  this.alive = false;
  this.healthy = false;
  this.animating = false;

  // Insert elements for waypoints into the dom.
  var r = Math.floor(Math.random()*255); var g = Math.floor(Math.random()*255); var b = Math.floor(Math.random()*255);
  this.$waypointTop = $('<div class="invisible-waypoint" aria-hidden="true" style="background: rgb('+r+','+g+','+b+');"></div>').appendTo('body');
  this.$waypointBottom =  $('<div class="invisible-waypoint" aria-hidden="true" style="background: rgb('+r+','+g+','+b+');"></div>').appendTo('body');

  // Position those elements appropriately
  this.positionWaypoints = function() {
    var numImages = $('.floater-image').length;
    var scrollableHeight = $(document).height()-$(window).height(); // How many pixels can a user scroll on this page?
    // Top waypoint
    var pos = ((order-0.5)*scrollableHeight/numImages-5)+'px';
    me.$waypointTop.css('top',pos);
    // Bottom waypoint
    pos = ((order+0.5+me.maxHealthy)*scrollableHeight/numImages)+'px'; 
    me.$waypointBottom.css('top',pos);
  };
  // Do it now and on resize
  this.positionWaypoints();
  $(window).resize(function() {
    me.positionWaypoints();
  });

  // Init waypoints
  this.waypointTop =  me.$waypointTop.waypoint({
    handler: function(direction) {
      if(direction==='down'){
        me.healthy = true;
        console.log(me.order+' is healthy!');
      }
      if(direction==='up'){
        me.healthy = false;
        console.log(me.order+' is unhealthy!');
      }
      // me.liveOrDie();
    }
  });
  this.waypointBottom = me.$waypointBottom.waypoint({
    handler: function(direction) {
      if(direction==='down'){
        me.healthy = false;
        console.log(me.order+' is unhealthy!');
      }
      if(direction==='up'){
        me.healthy = true;
        console.log(me.order+' is healthy!');
      }
      // me.liveOrDie();
    }
  });

  // Decide to Live Or Die
  this.liveOrDie = function () {
    if(!me.animating) {
      if(me.alive && !me.healthy) {
        me.die();
      } 
      if(!me.alive && me.healthy) {
        me.live();
      }
    }
  };
  setInterval(me.liveOrDie, 100);

  // Handle Living and Dying
  this.live = function() {
    me.position();
    me.neverShown = false;
    $me.addClass('alive');
    me.animating = true;
    console.log(me.order+' is becoming alive!');
    _blinds($me.find('.blind'),'hide',0,function() { //'hide' refers to hide the blinds
      me.animating = false;
      console.log(me.order+' is alive!');
      me.alive = true;
    }, false);
  };
  this.die = function()  {
    me.animating = true;
    console.log(me.order+' is dying!');
    _blinds($me.find('.blind'),'show',2,function() { //'show' refers to show the blinds
      me.animating = false;
      console.log(me.order+' is dead!');
      $me.removeClass('alive');
      me.alive = false;
    }, false);
  };

  // Positioning
  // We are (fix) positioned by a choice of col (set to a data-attr and handled in css) and an inline top position
  this.position = function () {
    $me.attr( 'data-col', me.chooseCol() );
    $me.css( 'top', me.choosePosTop() );
  };
  this.choosePosTop = function() {
    // if(me.order===0) { // If I'm first, top=0
    //   return '0';
    // } 
    var randPercent = Math.random()*100;
    return 'calc('+randPercent+'vh - '+($me.height()/2)+'px)';
  };
  this.chooseCol = function() {
    var possibleCols = me.getPossibleCols();
    // if(me.order===0) { // If I'm first, get leftmost col  //me.neverShown && 
    //   return (me.portrait ? 0: -1);
    // } 

    var col = possibleCols[Math.floor(Math.random()*possibleCols.length)];
    return col;
  };
  // The choice of what columns are OK to positiong the image in can be an affair...
  this.getPossibleCols = function() {
    var screenWidth = $(window).width();

    var badCols = [];
    // Mark content area off-limits
    if(!breakpoint_large) {
      badCols = $.merge(badCols,[-1,0,1,2,3,4,5,6]);
      if(me.portrait) {
        $.merge(badCols,[-3]);
      } else {
        $.merge(badCols,[-2,-1]);
      }
    } else {
      badCols = $.merge(badCols,[0,1,2,3,4,5,6,7]);
      if(me.portrait) {
        $.merge(badCols,[-3]);
      } else {
        $.merge(badCols,[-3,-1]);
      }
    }

    var goodCols = [];
    var colWidth = $('.blind').width();
    //Loop through all cols where image would be visible
    for (i=-3; i<Math.floor(screenWidth/colWidth); i++) { 
      if($.inArray(i, badCols)===-1) { goodCols.push(i); } // If this i isn't bad, it's good.  Add it on, then.
    }
    return goodCols;
  };

  // Handle Window Resizing
  this.windowWasResized = function() {
    var goodCols = me.getPossibleCols();
    var col = parseInt($me.attr('data-col'));
    if($.inArray(col,goodCols)===-1) { // If I'm not in a good col, reposition me.
      me.position();
    }
  };
  $(window).resize(function() {
    me.windowWasResized();
  });
}

function InlineImage($image,order) {

  // My self referential vars
  var me = this;
  var $me = $image;

  // My stats
  this.portrait = $me.hasClass('-portrait');
  this.alive = false;
  this.order = order;

  console.log('new InlineImage '+order);

  // Handle Living
  this.live = function() {
    $me.addClass('alive');
    console.log(me.order+' is becoming alive!');
    _blinds($me.find('.blind'),'hide',0,function() { //'hide' refers to hide the blinds
      console.log(me.order+' is alive!');
      me.alive = true;
    }, false);
  };

  // Init waypoints
  this.waypoint =  $me.waypoint({
    offset: '100%',
    handler: function(direction) {
      if(direction==='down' && !me.alive){
        me.live();
      }
    }
  });

}

  // Create floaterImage objects for all "floater" images
  function _initImages() {
    var floaterImages = [];
    $('.floater-image').each( function(i) {
      floaterImages[i] = new FloaterImage($(this),i);
    });

    var inlineImages = [];
    $('.inline-image').each( function(i) {
      inlineImages[i] = new InlineImage($(this),i);
    });
  }

  // Add functions to supplement CF7 form handling
  function _initContactForm() {
    $('.contact-form-submit').click(function(e){
      $('#wpcf7-f91-o1 .wpcf7-form').submit();
      $('.wpcf7-response-output').velocity('scroll',300);
    });
    $('.wpcf7').on('wpcf7:mailsent', function(e) {
      $('.form-accordian').velocity('slideUp',300);
      $('.form-wrap').css('padding-bottom','15px');
    });
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
