// FBSage - Firebelly 2015
/*jshint latedef:false*/

// Good Design for Good Reason for Good Namespace
var FBSage = (function($) {

  var screen_width = 0,
      breakpoint_nano = false,
      breakpoint_tiny = false,
      breakpoint_small = false,
      breakpoint_medium = false,
      breakpoint_large = false,
      breakpoint_huge = false,
      $document,
      $sidebar,
      loadingTimer,
      page_at,
      isProbablySafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/);

  function _init() {
    // touch-friendly fast clicks
    FastClick.attach(document.body);

    // Cache some common DOM queries
    $document = $(document);
    $('body').addClass('loaded');

    // Whether we are animating (to elsewhere block certain behaviors in this case)
    _isAnimating = false;

    // Inject svg into arrow classes
    _initArrows();

    // Add html markup and behavior for footer
    _initFooter();

    // Add global overlay for clickouts
    _initGlobalOverlay();

    // Set screen size vars
    _resize();

    // Fit them vids!
    $('main').fitVids();

    _initMobileNav();
    _initSearch();
    _initLoadMore();

    // Inject all of our svgs so we can grab them throughout the page with <svg class="..." role="img"><use xlink:href="#..."></use></svg> commands.
    _injectSvgSprite();

    // Esc handlers
    $(document).keyup(function(e) {
      if (e.keyCode === 27) {
        _closePopup();
        _closeSearch();
      }
    });

    // Smoothscroll links
    $('a.smoothscroll').click(function(e) {
      e.preventDefault();
      var href = $(this).attr('href');
      _scrollBody($(href));
    });

    // Add color information to URL we are going to, handle transition effect
    _initPageTransitionLinks();

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

    // Detect IE and add a class
    _ieDetect();

    // Scroll down to hash afer page load OR open popup with hash content
    $(window).load(function() {
      if (window.location.hash) {
        if($(window.location.hash).hasClass('linkable-popup')){
          _scrollBody($('body'));
          setTimeout(function() {
            _openPopup($(window.location.hash));
          },1000);
        } else {
          _scrollBody($(window.location.hash));
        }
      }
    });

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

  // Handles main nav
  function _initMobileNav() {
    // SEO-useless nav toggler
    $('<button class="open-popup mobile-nav-open" aria-hidden="true" data-content=".menu-main-menu-container"><svg class="hamburger" role="img"><use xlink:href="#hamburger"></use></svg></div>')
      .appendTo('.site-header').click(function() {
        $('.popup').addClass('holding-mobile-nav');
      });
  }
  function _resizeMobileNav() {
    if(breakpoint_medium && $('.popup.showing ul#menu-main-menu').length) {
      _closePopup();
    }
  }

  function _initLoadMore() {
    $document.on('click', '.load-more', function(e) {
      e.preventDefault();
      var $load_more = $(this);
      var post_type = $load_more.attr('data-post-type') ? $load_more.attr('data-post-type') : 'post';
      var page = parseInt($load_more.attr('data-page-at'));
      var per_page = parseInt($load_more.attr('data-per-page'));
      var category = $load_more.attr('data-category');
      var search = $load_more.attr('data-search');
      var $more_container = $load_more.parents('section,main').find('.load-more-container');
      loadingTimer = setTimeout(function() { $more_container.addClass('loading'); }, 500);

      $load_more.velocity('fadeOut',200); // Hide the button

      $.ajax({
          url: wp_ajax_url,
          method: 'post',
          data: {
              action: 'load_more_posts',
              page: page+1,
              per_page: per_page,
              category: category,
              search: search
          },
          success: function(data) {
            var $data = $(data);
            if (loadingTimer) { clearTimeout(loadingTimer); }
            $data.appendTo($more_container).velocity('fadeIn',400); // Fade in the content
            _initPageTransitionLinks();
            $load_more.attr('data-page-at', page+1);
            // Hide load more if last page
            if ($load_more.attr('data-total-pages') <= page + 1) {
                $load_more.addClass('hide');
            } else {
              $load_more.velocity('fadeIn',400); // Otherwise fade it in, because we faded it out
            }

            _resize(); //document size has changed -- trigger any function possibly dependent
            _initArrows();
          }
      });
    });
  }

  // Called in quick succession as window is resized
  function _resize() {
    screenWidth = document.documentElement.clientWidth;

    // Check breakpoint indicator in DOM ( :after { content } is controlled by CSS media queries )
    var breakpointIndicatorString = window.getComputedStyle(
      document.querySelector('#breakpoint-indicator'), ':after'
    ).getPropertyValue('content')
    .replace(/['"]+/g, '');

    breakpoint_huge = breakpointIndicatorString === 'huge';
    breakpoint_large = breakpointIndicatorString === 'large' || breakpoint_huge;
    breakpoint_medium = breakpointIndicatorString === 'medium' || breakpoint_large;
    breakpoint_small = breakpointIndicatorString === 'small' || breakpoint_medium;
    breakpoint_tiny = breakpointIndicatorString === 'tiny' || breakpoint_small;
    breakpoint_nano = breakpointIndicatorString === 'nano' || breakpoint_tiny;

    _resizeMobileNav();
    _resizeFooter();
    _fitBodyToPopup();

  }

  // Add global overlay for clickouts of the mobile nav, popups and footer
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

  // Build the footer
  function _initFooter() {
    // Add some non-semantic markup
    var html = '<div class="footer-tab" aria-hidden="true"><button class="footer-toggle"><svg class="icon-plus" aria-hidden="true"><use xlink:href="#icon-plus"></use></svg></button><svg class="footer-hole" aria-hidden="true"><use xlink:href="#footer-hole"></use></svg></div>';
    $(html).prependTo('.site-footer').click(function(e) { // Open or close the footer on click
      e.preventDefault();
      if( $('.site-footer').hasClass('closed') ){
        _openFooter();
      } else {
        _closeFooter();
      }
    });

    // Add a waypoint to handle the auto closing of the footer
    $footerWaypoint = $('<div class="invisible-waypoint -footer" aria-hidden="true"></div>')
    .appendTo('#primary-site-content');
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

    // Do all the resizing chores once up front
    _resizeFooter();
  }
  function _closeFooter() {
    $('.site-footer').addClass('closed');
    // Replace +/-
    $('.footer-toggle').empty().append('<svg class="icon-plus" aria-hidden="true"><use xlink:href="#icon-plus"></use></svg>');
  }
  function _openFooter() {
    if(!$('.popup.showing').length) {
      $('.site-footer').removeClass('closed');
      // Replace +/-
      $('.footer-toggle').empty().append('<svg class="icon-minus" aria-hidden="true"><use xlink:href="#icon-minus"></use></svg>');
    }
  }
  function _resizeFooter() {
    var footerHeight = $('.site-footer').outerHeight();
    // Add a margin to the content the size of the footer
    $('#primary-site-content').css('margin-bottom',(breakpoint_medium ? footerHeight : 0));
    // Position the waypoint that auto opens and closes the footer
    var adminBarCorrection = $('#wpadminbar').length ? 32 : 0;
    $('.invisible-waypoint.-footer').css('bottom', -footerHeight+1 + adminBarCorrection);
  }

  // Add color information to URL we are going to, handle transition effect
  function _initPageTransitionLinks() {
    // Hijack links
    $('a:not(.fake-link)').each(function() {
      $(this).click(function(e) {
        if(_isAnimating) { // Don't be able to click links while we are already transitioning to a new page
          e.preventDefault();
        } else {
          var linkUrl = $(this)[0].href; // Get my destination url
          // Apply transition only if its an in-site URL (or metaKey is held down)!  Otherwise, skip this and proceed to default link behavior
          if( !e.metaKey && _get_hostname(linkUrl) === document.location.hostname ) {
            _isAnimating = true;
            e.preventDefault();

            // Throw lines to front and change their color
            $('.lines').addClass('page-transitioning');

            // Find starting blind
            var startingBlindNum = _closestX( $('.blinds.-page .blind'), $(this).offset().left ).data('blind-num'); //Math.floor($(this).offset().left / $('.blinds.-page .blind').width()); // Which blind # corresponds to the X location of this link

            // Trigger blinds
            _blinds( $('.blinds.-page .blind'), 'show', startingBlindNum, function() {
              // The below timeout seems to be too quick for Safari.  
              // So if our user agent sniffing thinks we are Safari, 
              // we'll be boy scouts and change href EXACTLY when animation ends
              if(isProbablySafari) {
                // console.time('transition');
                window.location.href = linkUrl;
                // console.timeEnd('transition');
              }
            });

            // The default is to just wait 200ms.  This seems to work best for most browsers that are not Safari.  On those browsers, firing this on the animation finished callback feels too sluggish.
            if(!isProbablySafari) {
              setTimeout(function() {
                location.href = linkUrl;
              }, 200);
            }
          }
        }
      });
    });

    // Similarly for search form submits....
    $('.search-form').submit(function(e) {
      e.preventDefault();
      if(!_isAnimating) { // Don't be able to click links while we are already transitioning to a new page

        _isAnimating = true;

        // Throw lines to front and change their color
        $('.lines').addClass('page-transitioning');

        // Find starting blind
        var startingBlindNum = _closestX( $('.blinds.-page .blind'), $(this).offset().left ).data('blind-num'); //Math.floor($(this).offset().left / $('.blinds.-page .blind').width()); // Which blind # corresponds to the X location of this link

        // Reference var
        var searchForm = this;

        // Trigger blinds, submit on complete
        _blinds( $('.blinds.-page .blind'), 'show', startingBlindNum, function() {
          searchForm.submit();
        });

      }
    });
  }
  function _get_hostname(url) {
    // Get the hostname from url using regexp
    var base = url.match(/^http:\/\/[^/]+/);
    var host = base[0] ? base[0].split('//')[1] : null;
    return host ? host : null;
  }



  function _initSearch() {

    // If we are on the search page, focus the in-page search bar and move cursor to end.
    var $inputOnSearchPage = $('.page-header .search-field');
    if($inputOnSearchPage.length) {
      $inputOnSearchPage.attr('placeholder','...').focus();
      var len = $inputOnSearchPage.val().length;
      $inputOnSearchPage[0].setSelectionRange(len, len);
    }

    // Hide the search popup
    $('.search-popup .body-wrap').velocity('fadeOut',0);

    // Add a class to the search item in the main nav (to select easily and also to make it disappear at tablet size where there will be an icon)
    $('a[href="#search"]').closest('li').addClass('menu-item-search');

    // Trigger open and close the search popup when clicking appropriate things
    $('.open-search, a[href="#search"]').click(function(e) {
      e.preventDefault();
      _openSearch();
    });
    $('.search-popup .close').click(function(e) {
      e.preventDefault();
      _closeSearch();
    });
  }

  function _openSearch() {
    if(!_isAnimating) { // Don't do this if we are already animating something else important
      _isAnimating = true;

      // Get that footer outta here
      _closeFooter();

      // Fade in and add a class
      $('.search-popup').addClass('showing');
      $('.search-popup .lines').velocity('fadeIn',200);

      // Find the blind thats closest to the open search button
      var searchLink = $('.open-search:visible,.menu-item-search:visible').first();
      var startingBlindNum = _closestX( $('.search-popup > .blinds .blind'), searchLink.offset().left ).data('blind-num'); // Which blind # corresponds to the X location of this link

      // Trigger the blinds
      _blinds($('.search-popup > .blinds .blind'),'show',startingBlindNum ,function () {
        // When done fade in the content
        $('.search-popup .body-wrap').velocity('fadeIn',{
          duration: 200,
          complete: function() {
            // When done fading in content, focus and release _isAnimating
            $('.search-popup .search-field').focus();
            _isAnimating = false;
          }
        });
      });
    }
  }

  function _closeSearch() {
    if(!_isAnimating && $('.search-popup.showing').length){  // Don't do this if there is no search popup OR we are already animating something else important
      _isAnimating = true;

      // Hide content
      $('.search-popup .body-wrap').velocity('fadeOut',{
        duration: 200,
        complete: function () {
          // Hide popup lines
          $('.search-popup .lines').velocity('fadeOut',{
            duration: 200
          });
          // Unblind the blinds
          _blinds($('.search-popup > .blinds .blind'),'hide',0, function() {
            // Cleanup classes and release _isAnimating
            $('.search-popup').removeClass('showing').removeClass('holding-mobile-nav');
            _isAnimating = false;
          });
        }
      });
    }
  }


  // Initialize popup for modals and for mobile nav
  function _initPopup() {
    // Here is a global variable to remember which blind was the first to open
    _popupStartingBlindNum = 0;

    // Inject popup html and hide it
    var html = '<div class="popup" aria-hidden="true"><div class="body-wrap"><div class="content-holder"></div><button class="close" aria-hidden="true">x</button></div></div>';
    $(html).appendTo('body');
    $('.popup .body-wrap').velocity('fadeOut',0);

    // Open popups when clicking .open-popup
    $('.open-popup').click(function(e) {
      e.preventDefault();
      if(!_isAnimating) {
        // Choose the blind closest to the .open-popup link
        _popupStartingBlindNum = _closestX( $('.popup > .blinds .blind'), $(this).offset().left ).data('blind-num');
        // Open the popup with the corresponding content
        var $content = $($(this).data('content'));
        _openPopup($content);
      }
    });
    // Swith popup content when a popup is already open
    $('.switch-content').click(function(e) {
      e.preventDefault();
      if(!_isAnimating) {
        var $content = $($(this).data('content'));
        _switchPopupContent($content);
      }
    });

    // Close popups on clickout or when clicking the X
    $('.almighty-global-overlay, .popup .close').click(function() {
        _closePopup();
    });
  }
  function _closePopup() {
    if(!_isAnimating && $('.popup.showing').length) {
      _isAnimating = true;

      // Edit browser history (this will change nothing if it was not a linkable popup, otherwise it removes the popup from history)
      history.replaceState(null, null, window.location.pathname);

      // Close the clickout overlay
      _returnToYourSlumberAlmightyOverlay();
      $('.popup .body-wrap').velocity('fadeOut',{ // Fade out content
        duration: 200,
        complete: function () { // When done...
          // Fade out lines
          $('.popup .lines').velocity('fadeOut',{
            duration: 200
          });
          // Animate blinfs
          _blinds($('.popup > .blinds .blind'),'hide',0, function() {
              // When done clean up classes and release _isAnimating
              _isAnimating = false;
              $('.popup').removeClass('showing').removeClass('holding-mobile-nav');
              _fitBodyToPopup();
          });
        }
      });
    }
  }
  function _openPopup($content) {
    //This opens a popup with the content contained in $content

    _isAnimating = true;
    _closeFooter(); // Get pesky footer outta the way

    // If we are a linkable popup put us in browser history
    if($content.hasClass('linkable-popup')){ history.replaceState(null, null, '#'+$content.attr('id')); }

    $('.popup').addClass('showing');
    _awakenTheAlmightyOverlay(); // Add an overlay outside the popup to close on click
    $('.popup .lines').velocity('fadeIn',200); // Fade in the lines

    // Animate the blinds
    _blinds($('.popup > .blinds .blind'),'show',_popupStartingBlindNum,function () {
      // When done animating the last blind
      $('.popup .content-holder').empty(); // Get rid of last content
      $content.clone(true, true).contents().appendTo('.popup .content-holder'); // Add new content
      $('.popup').attr('id', $content.attr('id') + 'Popup');

      _fitBodyToPopup();

      $('.popup .body-wrap').velocity('fadeIn',{ // Fade in content
        duration: 200,
        complete: function() { // When faded in...
          $popup = $('.popup');
          if(!$popup.hasClass('holding-mobile-nav')) { $popup.velocity('scroll',200); } // Scroll to top if not fixed nav
          _isAnimating = false; // Release _isAnimating
        }
      });
    });
  }
  function _switchPopupContent($content) {
    //This switches an open popups content with the content contained in $content

    _isAnimating = true;

    // If we are a linkable popup put us in browser history
    if($content.hasClass('linkable-popup')){ history.replaceState(null, null, '#'+$content.attr('id')); }

    // Fade out the content
    $('.popup .body-wrap').velocity('fadeOut',{
      duration: 200,
      complete: function() {
        $('.popup .content-holder').empty(); // Out with the old
        $content.clone(true, true).contents().appendTo('.popup .content-holder'); // In with the new
        $('.popup').attr('id', $content.attr('id') + 'Popup');

        _fitBodyToPopup();

        $('.popup .body-wrap').velocity('fadeIn',{ // Fade in new content
          duration: 200,
          complete: function() {
            $('.popup').velocity('scroll',200); // Scroll to it
            _isAnimating = false;
          }
        });
      }
    });
  }

  function _fitBodyToPopup() {
    if ($('.popup').hasClass('showing')) {
      var popupHeight = $('.popup .body-wrap').outerHeight() + (breakpoint_medium ? 105+52+105 : 0);
      $('body').css('min-height',popupHeight);
    } else {
      $('body').css('min-height','');
    }
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

    var singleBlindDuration = 30; 

    // Animate each blind.
    $($blinds).each(function() {
      var thisBlindNum = $(this).data('blind-num');
      $(this).velocity( (showOrHideTheBlinds === 'show' ? 'transition.blindIn' : 'transition.blindOut') , {
        delay: (Math.abs(startingBlindNum-thisBlindNum)*singleBlindDuration),
        duration: duration,
        easing: [1,0.75,0.5,1],
        complete: (thisBlindNum!==finalBlindNum) ? undefined  : function() { // This function fires when the last blind has finished animating
          if (useHiddenClass && showOrHideTheBlinds === 'hide') {
            $container.addClass('-hidden'); //display: none so no interfering with pointer events
          }
          if (onDone) {
            onDone();
          }
        }
      });
    });
  }
  function _closestX($elements,x){
    // Finds the closest element in the set $elements to a given x position
    $elements.show();
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
    $elements.hide();
    return closestElement;
  }

    // Add html markup and behavior for venetian blinds.  We have two sets of these.
  function _initBlinds() {
    // Register velocity animations
    // Why handle this with velocity?  Why not add/remove a class?  Because velocity has a 'complete' callback and we need to time some things to fire precisely with animation completion (like going to destination url). That's why.
    $.Velocity
    .RegisterEffect("transition.blindOut", {
      defaultDuration: 500,
      calls: [
        [ {translateX: '-50%', scaleX: '0.001'} ]
      ]
    });

    $.Velocity
    .RegisterEffect("transition.blindIn", {
      defaultDuration: 500,
      calls: [
        [ { translateX: '0', scaleX: '1'} ]
      ]
    });

    // Add main section content blinds
    _makeBlinds(6,'.popup','-hidden');

    // Ditto page transition blinds
    _makeBlinds(15,'body','-page -hidden');

    // Ditto page transition blinds
    _makeBlinds(15,'.search-popup','-hidden');

    // We want to open them with velocity.  They close as expected when you do this.  Otherwise, they can be buggy.
    $('.blind').velocity("transition.blindOut", {duration: 0});

    // Add image content blinds
    _makeBlinds(8,'.floater-image, .inline-image');

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
    _makeLines(7,'.popup').velocity('fadeOut',0);
    // Lines behind popups
    _makeLines(15,'.search-popup').velocity('fadeOut',0);
    // Lines in front of images
    _makeLines(6,'.floater-image');
  }
  function _makeLines(n,container){
    html = '<div class="lines" aria-hidden="true">';
    for (i=0; i<n; i++) {
      html+='<div class="line"></div>';
    }
    html+='</div>';
    return $(html).prependTo(container);
  }

function FloaterImage($image,orderNum) {
  // Each FloaterImage has 2 waypoints.  It is either healthy or unhealthy, alive or dead.  Being between the waypoints makes it healthy.
  // If it is unhealthy and alive, it will die as soon as it can (it's not animating).  Also vice versa.

  // My self referential vars
  var me = this;
  var $me = $image;

  // My stats
  this.orderNum = orderNum;
  this.portrait = $me.hasClass('-portrait');
  this.alive = false;
  this.healthy = false;
  this.animating = false;
  this.invincible = false;

  // Init....
  // Insert elements for waypoints into the dom.  The waypoints will show/hide the images
  var r = Math.floor(Math.random()*255); var g = Math.floor(Math.random()*255); var b = Math.floor(Math.random()*255);  // I gave these random colors (but the same color for each image) to aid debugging
  this.$waypointTop = $('<div class="invisible-waypoint" aria-hidden="true" style="background: rgb('+r+','+g+','+b+');"></div>').appendTo('body');
  this.$waypointBottom =  $('<div class="invisible-waypoint" aria-hidden="true" style="background: rgb('+r+','+g+','+b+');"></div>').appendTo('body');

  // Position those elements appropriately
  this.positionWaypoints = function() {
    var numImages = $('.floater-image').length;
    var docHeight = $(document).height();
    var adminBarCorrection = $('#wpadminbar').length ? 32 : 0; // Wp admin bar for logged in user
    var scrollableHeight = docHeight-$(window).height(); // How many pixels can a user scroll on this page?
    // Top waypoint
    var pos = ((orderNum-0.5)*scrollableHeight/numImages-5-adminBarCorrection)+'px';
    me.$waypointTop.css('top',pos);
    // Bottom waypoint
    pos = Math.min(((orderNum+1.5)*scrollableHeight/numImages-adminBarCorrection),$('#primary-site-content').height())+'px';
    me.$waypointBottom.css('top',pos);
  };
  // Do it now and on resize
  $(window).resize(function() {
    me.positionWaypoints();
  });
  this.positionWaypoints();

  // Init waypoints
  this.waypointTop =  me.$waypointTop.waypoint({
    handler: function(direction) {
      if(direction==='down'){
        me.healthy = true;
      }
      if(direction==='up'){
        me.healthy = false;
      }
    }
  });
  this.waypointBottom = me.$waypointBottom.waypoint({
    handler: function(direction) {
      if(direction==='down'){
        me.healthy = false;
      }
      if(direction==='up'){
        me.healthy = true;
      }
    }
  });

  // Decide to Live Or Die
  this.liveOrDie = function () {
    if(!me.animating) {
      if(me.alive && !me.healthy && !me.invincible) {
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
    $me.addClass('alive');
    me.animating = true;
    _blinds($me.find('.blind'),'hide',0,function() { //'hide' refers to hide the blinds
      me.animating = false;
      me.alive = true;
    }, false);
    me.invincible = true;
    setTimeout(function() {
      me.invincible = false;
    }, 4000); // I didn't like that images could appear and instantly disappear.  So I make them temporarily invincible to ensure a min lifespan.
  };
  this.die = function()  {
    me.animating = true;
    _blinds($me.find('.blind'),'show',2,function() { //'show' refers to show the blinds
      me.animating = false;
      $me.removeClass('alive');
      me.alive = false;
    }, false);
  };

  // Positioning
  // We are (fix) positioned by a choice of col (set to a data-attr and handled in css) and an inline top position
  this.position = function () {
    if(breakpoint_medium){
      $me.attr( 'data-col', me.chooseCol() );
      $me.css( 'top', me.choosePosTop() );
    }
  };
  this.choosePosTop = function() {
    var randPercent = Math.random()*60+20;
    return 'calc('+randPercent+'vh - '+($me.height()/2)+'px)';
  };
  this.chooseCol = function() {
    var possibleCols = me.getPossibleCols();

    var col = possibleCols[Math.floor(Math.random()*possibleCols.length)];
    return col;
  };
  // The choice of what columns are OK to position the image in can be an affair...
  this.getPossibleCols = function() {
    var screenWidth = $(window).width();

    var badCols = [];
    // What are the bad columns?
    if ($('.single-post').length && $(window).width() >= 800 && $(window).width() < 900 ) {
      badCols = $.merge(badCols,[-3,-2,-1,0,1,2,3,4,5]);
    } else if(breakpoint_large) {
      badCols = $.merge(badCols,[0,1,2,3,4,5,6,7]);
      if(me.portrait) {
        $.merge(badCols,[-3]);
      } else {
        $.merge(badCols,[-3,-1]);
      }
    } else {
      badCols = $.merge(badCols,[-1,0,1,2,3,4,5,6]);
      if(me.portrait) {
        $.merge(badCols,[-3]);
      } else {
        $.merge(badCols,[-2,-1]);
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
  this.onResize = function() {
    var goodCols = me.getPossibleCols();
    var col = parseInt($me.attr('data-col'));
    if($.inArray(col,goodCols)===-1) { // If I'm not in a good col, reposition me.
      me.position();
    }
  };
  $(window).resize(function() {
    me.onResize();
  });

  //Position
  this.position();
}

function InlineImage($image,delay) {

  if(typeof delay==='undefined') { delay = 500; }
  this.delay = delay;

  // My self referential vars
  var me = this;
  var $me = $image;

  // My stats
  this.portrait = $me.hasClass('-portrait');
  this.alive = false;

  // Handle Living (No dying for these inline images)
  this.live = function() {
    $me.addClass('alive'); // Class me
    // Open the blinds
    _blinds($me.find('.blind'),'hide',0,function() { //'hide' refers to hide the blinds
      me.alive = true;
    }, false);
  };

  // Init the waypoint that makes me become alive
  setTimeout(function() { // But wait for delay to do so
    me.waypoint =  $me.waypoint({
      offset: '100%',
      handler: function(direction) {
        if(direction==='down' && !me.alive){
          me.live();
        }
      }
    });
  },this.delay);

}

  // Init images
  function _initImages() {
    // Make the fix-position floating images
    var floaterImages = [];
    $('.floater-image').each( function(i) {
      floaterImages[i] = new FloaterImage($(this),i);
    });

    // Make the absolute positioned images
    var inlineImages = [];
    $('.inline-image').each( function(i) {
      inlineImages[i] = new InlineImage($(this),i*250+500); // Stagger the delays 250ms with the first one at 500ms.
    });
  }


  function _initContactForm() {
    // Mess up CF7s forced markup so the damn thing will work on iphone.
    // Spans/Checkboxes cannot be nested inside labels
    // If they are clicking on the label text will not check/uncheck the box.  This breaks custom checkboxes
    $('.wpcf7-checkbox .wpcf7-list-item-label').each(function() {
      $(this).replaceWith($('<label class="wpcf7-list-item-label">' + this.innerHTML + '</label>'));
    });
    $('.wpcf7-checkbox .wpcf7-list-item').each(function(i) {
      var id = 'wpcf7-contact-form-checkbox-'+i;
      $(this).find('.wpcf7-list-item-label').attr('for',id);
      $(this).find('input[type="checkbox"]').attr('id',id);
    });

    // We have our own custom submit form
    $('.contact-form-submit').click(function(e){
      e.preventDefault();
      $('#wpcf7-f91-o1 .wpcf7-form').submit(); // Submit the form from our custom button
      $('.wpcf7-response-output').velocity('scroll',300); //Scroll to form response message if there is one
    });
    // On successful submission and mail sent
    $('.wpcf7').on('wpcf7:mailsent', function(e) {
      $('.form-accordion').velocity('slideUp',300); // Fold up the form
      $('.form-wrap').css('padding-bottom','15px'); // Pad the bottom
    });
  }

  // Detect IE and add a class for ie fixes (mainly flexbox stuff)
  function _ieDetect() {  //http://stackoverflow.com/a/19868056
    if( "ActiveXObject" in window ) {
      $('html').addClass('ie');
    }
  }

  // Inject svg into arrow classes
  function _initArrows() {
    $('.arrow.-right').each(function() {
      if(!$(this).find('.arrowhead').length) {
        $('<svg class="arrowhead -right" aria-hidden="true"><use xlink:href="#arrowhead-right"></use></svg>').appendTo($(this));
      }
    });
    $('.arrow.-left').each(function() {
      if(!$(this).find('.arrowhead').length) {
        $('<svg class="arrowhead -left" aria-hidden="true"><use xlink:href="#arrowhead-left"></use></svg>').appendTo($(this));
      }
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
