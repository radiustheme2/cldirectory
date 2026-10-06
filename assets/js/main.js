(function ($) {

    function rtPreloader() {
        $('#preloader').fadeOut('slow', function () {
            $(this).remove();
        });
    
    }

    rtPreloader();

    function rdtheme_wc_scripts() {
        /* Shop change view */
        $('#shop-view-mode li a').on('click', function () {
            $('body').removeClass('product-grid-view').removeClass('product-list-view');

            if ($(this).closest('li').hasClass('list-view-nav')) {
                $('body').addClass('product-list-view');
                Cookies.set('shopview', 'list');
            } else {
                $('body').addClass('product-grid-view');
                Cookies.remove('shopview');
            }
            return false;
        });
    }


    /*-------------------------------------
    On Scroll
    -------------------------------------*/
    $(window).on('scroll', function () {
        // Sticky Header
        if ($('body').hasClass('sticky-header')) {
            // Sticky header
            var stickyPlaceHolder = $("#rt-sticky-placeholder"),
                menu = $("#header-menu"),
                menuH = menu.outerHeight(),
                topHeaderH = $('#tophead').outerHeight() || 0,
                middleHeaderH = $('#middleHeader').outerHeight() || 0,
                targrtScroll = topHeaderH + middleHeaderH;
            if ($(window).scrollTop() > targrtScroll) {
                menu.addClass('rt-sticky');
                stickyPlaceHolder.height(menuH);
            } else {
                menu.removeClass('rt-sticky');
                stickyPlaceHolder.height(0);
            }

            // Sticky mobile header
            var stickyPlaceHolder = $("#mobile-sticky-placeholder"),
                menubar = $("#mobile-men-bar"),
                menubarH = menubar.outerHeight(),
                topHeaderH = $('#mobile-top-fix').outerHeight() || 0,
                total_height =topHeaderH;
            if ($(window).scrollTop() > total_height) {
                $("#meanmenu").addClass('mobile-sticky');
                stickyPlaceHolder.height(menubarH);             
            } else {
                $("#meanmenu").removeClass('mobile-sticky');
                stickyPlaceHolder.height(0);
            }
        }
    });

     /* Listing Details Custom Nav Menu */

    $(window).scroll(function () {
        var listing_menu_upper_height=$('#listing-content-top').outerHeight();

        var listingStickyPlaceHolder = $("#listing-sticky-placeholder"),
            listing_menu = $(".rtcl-single-nav-menu-wrapper"),
            listing_menuH = listing_menu.outerHeight();

        if ($(this).scrollTop() > listing_menu_upper_height) {
            $('.rtcl-single-nav-menu-wrapper').addClass('listing-sticky');
            listingStickyPlaceHolder.height(listing_menuH); 
        }
        else{
            $('.rtcl-single-nav-menu-wrapper').removeClass('listing-sticky');
            listingStickyPlaceHolder.height(0); 
        }
        
        
    });


    /* Listing - Toggle Filter */
    $('#cldirectory-toggle-sidebar').on('click',function(){

        var $main = $('.listing-archive-sidebar .main-sidebar-wrapper');
        var display = $main.css('display');
        if ( display == 'block' ) {
            $main.hide();
        }
        if ( display == 'none' ) {
            $main.show();
        }

        return false;
    });

    /*-------------------------------------
    On load and resize
    -------------------------------------*/
    $(window).on("load resize", function () {
        if (ClDirectoryObj.rtStickySidebar === 'enable') {
            $('#sticky_sidebar').rtStickySidebar({
                additionalMarginTop: Number(ClDirectoryObj.lsSideOffset) + 10,
                additionalMarginBottom: 20,
            });
        }
    });
    
   /*-------------------------------------
        Tooltip
        -------------------------------------*/

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    /*-------------------------------------
            PhotoSwipe Lightbox
    -------------------------------------*/
    function cldirectory_lightbox() {
        // Init empty gallery array
        var container = [];
        $(".single-details-thumb .photoswip-item a").attr( 
            {width: "1920",  height:"560"}
        );
        // Loop over gallery items and push it to the array
        $('.single-details-thumb').find('.photoswip-item').each(function() {
            var $link = $(this).find('a'),
            
            item = {
                src: $link.attr('href'),
                w: $link.attr('width'),
                h: $link.attr('height')
            };
            container.push(item);
        });

        // Define click event on gallery item
        $('.single-details-thumb .photoswip-item a').click(function(event) {

            // Prevent location change
            event.preventDefault();

            // Define object and gallery options
            var $pswp = $('.pswp')[0],
            options = {
                index: $(this).parent('.photoswip-item').index(),
                bgOpacity: 0.85,
                showHideOpacity: true
            };

            // Initialize PhotoSwipe
            var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
            gallery.init();
        });
    }

    function cldirectory_lightbox2(){

        var initPhotoSwipeFromDOM = function(gallerySelector) {

            // parse slide data (url, title, size ...) from DOM elements 
            // (children of gallerySelector)
            var parseThumbnailElements = function(el) {
                var thumbElements = el.childNodes,
                    numNodes = thumbElements.length,
                    items = [],
                    figureEl,
                    linkEl,
                    size,
                    item;
                for(var i = 0; i < numNodes; i++) {
                    
                    figureEl = thumbElements[i]; // <figure> element

                    // include only element nodes 
                    if(figureEl.nodeType !== 1) {
                        continue;
                    }

                    linkEl = figureEl.children[0]; // <a> element

                    size = linkEl.getAttribute('data-size').split('x');

                    // create slide object
                    item = {
                        src: linkEl.getAttribute('href'),
                        w: parseInt(size[0], 10),
                        h: parseInt(size[1], 10)
                    };

                    if(figureEl.children.length > 1) {
                        // <figcaption> content
                        item.title = figureEl.children[1].innerHTML; 
                    }
                    if(linkEl.children.length > 0) {
                        // <img> thumbnail element, retrieving thumbnail url
                        item.msrc = linkEl.children[0].getAttribute('src');
                    } 
                    item.el = figureEl; // save link to element for getThumbBoundsFn
                    items.push(item);
                }
                return items;
            };

            // find nearest parent element
            var closest = function closest(el, fn) {
                return el && ( fn(el) ? el : closest(el.parentNode, fn) );
            };

            // triggers when user clicks on thumbnail
            var onThumbnailsClick = function(e) {
                e = e || window.event;
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                var eTarget = e.target || e.srcElement;
                // find root element of slide
                var clickedListItem = closest(eTarget, function(el) {
                    return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
                });
                if(!clickedListItem) {
                    return;
                }
                // find index of clicked item by looping through all child nodes
                // alternatively, you may define index via data- attribute
                var clickedGallery = clickedListItem.parentNode,
                    childNodes = clickedListItem.parentNode.childNodes,
                    numChildNodes = childNodes.length,
                    nodeIndex = 0,
                    index;
                for (var i = 0; i < numChildNodes; i++) {
                    if(childNodes[i].nodeType !== 1) { 
                        continue; 
                    }

                    if(childNodes[i] === clickedListItem) {
                        index = nodeIndex;
                        break;
                    }
                    nodeIndex++;
                }
                if(index >= 0) {
                    // open PhotoSwipe if valid index found
                    openPhotoSwipe( index, clickedGallery );
                }
                return false;
            };

            // parse picture index and gallery index from URL (#&pid=1&gid=2)
            var photoswipeParseHash = function() {
                var hash = window.location.hash.substring(1),
                params = {};

                if(hash.length < 5) {
                    return params;
                }

                var vars = hash.split('&');
                for (var i = 0; i < vars.length; i++) {
                    if(!vars[i]) {
                        continue;
                    }
                    var pair = vars[i].split('=');  
                    if(pair.length < 2) {
                        continue;
                    }           
                    params[pair[0]] = pair[1];
                }

                if(params.gid) {
                    params.gid = parseInt(params.gid, 10);
                }

                return params;
            };

            var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
                var pswpElement = document.querySelectorAll('.pswp')[0],
                    gallery,
                    options,
                    items;

                items = parseThumbnailElements(galleryElement);

                // define options (if needed)
                options = {
                    // define gallery index (for URL)
                    galleryUID: galleryElement.getAttribute('data-pswp-uid'),

                    getThumbBoundsFn: function(index) {
                        // See Options -> getThumbBoundsFn section of documentation for more info
                        var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                            pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                            rect = thumbnail.getBoundingClientRect(); 
                        return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
                    }

                };

                // PhotoSwipe opened from URL
                if(fromURL) {
                    if(options.galleryPIDs) {
                        // parse real index when custom PIDs are used 
                        for(var j = 0; j < items.length; j++) {
                            if(items[j].pid == index) {
                                options.index = j;
                                break;
                            }
                        }
                    } else {
                        // in URL indexes start from 1
                        options.index = parseInt(index, 10) - 1;
                    }
                } else {
                    options.index = parseInt(index, 10);
                }

                // exit if index not found
                if( isNaN(options.index) ) {
                    return;
                }

                if(disableAnimation) {
                    options.showAnimationDuration = 0;
                }

                // Pass data to PhotoSwipe and initialize it
                gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
                gallery.init();
            };

            // loop through all gallery elements and bind events
            var galleryElements = document.querySelectorAll( gallerySelector );

            for(var i = 0, l = galleryElements.length; i < l; i++) {
                galleryElements[i].setAttribute('data-pswp-uid', i+1);
                galleryElements[i].onclick = onThumbnailsClick;
            }

            // Parse URL and open gallery if it contains #&pid=3&gid=1
            var hashData = photoswipeParseHash();
            if(hashData.pid && hashData.gid) {
                openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
            }
        };

        // execute above function
        initPhotoSwipeFromDOM('.single-listing-food-menu');
    }

    cldirectory_lightbox();
    cldirectory_lightbox2();


    // Window Ready
    jQuery(document).ready(function ($) {

        rdtheme_content_ready_scripts();
        rdtheme_wc_scripts();
        
        //Favourite Icon Update
        //=========================
        $(document).on('rtcl.favorite', function (e, data) {
            var $favCount = $(".rt-header-favourite-count").first();
            var $favCountAll = $(".rt-header-favourite-count");
            var favCountVal = parseInt($favCount.text(), 10);
            favCountVal = isNaN(favCountVal) ? 0 : favCountVal;
            if ("added" === data.action) {
                favCountVal++;
                $favCountAll.text(favCountVal);
            } else if ("removed" === data.action) {
                favCountVal--;
                $favCountAll.text(favCountVal);
            }
        });
        //End Favourite Icon Update

        //Compare icon update
        //====================
        $(document).on('rtcl.compare.added', function (e, data) {
            $('.rt-compare-count').text(data.current_listings);
        });

        $(document).on('rtcl.compare.removed', function (e, data) {
            $('.rt-compare-count').text(data.current_listings);
        });

        $(document).on('click', '.rtcl-compare-btn-clear', function () {
            $('.rt-compare-count').text('0');
        });

        //End Compare icon update

        $('.rtcl-item-visible-btn').on('click', function (e) {
            e.preventDefault();
            $(this).parents('.advance-search-form').find('.expanded-wrap').slideToggle();
        });

        $('.input-group .form-control').on('focus', function () {
            $(this).parent('.input-group').addClass('active');
        }).on('focusout', function () {
            $(this).parent('.input-group').removeClass('active');
        });


        /* Scroll to top */
        const backToTop = document.querySelector(".scrollup");
        window.onscroll = function () {
            scrollFunction();
        };
        function scrollFunction() {
            if (backToTop !== null) {
                if (
                    document.body.scrollTop > 80 ||
                    document.documentElement.scrollTop > 80
                ) {
                    backToTop.style.display = "block";
                    backToTop.classList.add('back-top');
                } else {
                    backToTop.style.display = "none";
                    backToTop.classList.remove('back-top');
                }
            }
        }
        if (backToTop !== null) {
            backToTop.addEventListener("click", (e) => {
            e.preventDefault();
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
            });
        }

        // Add class to listing search filter radios
        $('.search-radio-check ul li:first-child label').addClass('active');
        var $rtSearchRadioButtons = $('.search-radio-check input[type="radio"]');
        $rtSearchRadioButtons.click(function () {
            $rtSearchRadioButtons.each(function () {
                $(this).parent().toggleClass('active', this.checked);
            });
        });


        // Mobile Menu
        var mobileMenu = $('.offscreen-navigation nav ul');

        if (mobileMenu.length) {
            mobileMenu.children("li").addClass("menu-item-parent");

            mobileMenu.find(".menu-item-has-children > a, .page_item_has_children > a").append('<span class="pointer"></span>')
            mobileMenu.find(".menu-item-has-children > a > .pointer, .page_item_has_children > a > .pointer").on("click", function (e) {
                e.preventDefault();
                $(this).parent().toggleClass("opened");
                var n = $(this).parent().next(".sub-menu, .children"),
                    s = $(this).parent().closest(".menu-item-parent").find(".sub-menu, .children");
                //mobileMenu.find(".sub-menu, .children").not(s).slideUp(250).prev('a').removeClass('opened'); 
                n.slideToggle(250);
            });

            mobileMenu.find('.menu-item:not(.menu-item-has-children, .page_item_has_children) > a').on('click', function (e) {
                $('.rt-slide-nav').slideUp();
                $('body').removeClass('slidemenuon');
            });
        }


        $('.sidebarBtn.circle-btn').on('click', function (e) {
            e.preventDefault();
            $('.overly-sidebar-wrapper').addClass('show');
            $('.offcanvas-menu-btn').addClass('menu-status-open');
        });

        $('.mean-bar .sidebarBtn').on('click', function (e) {
            e.preventDefault();

            if ($('.rt-slide-nav').is(":visible")) {
                $('.rt-slide-nav').slideUp();
                $('body').removeClass('slidemenuon');
            } else {
                $('.rt-slide-nav').slideDown();
                $('body').addClass('slidemenuon');
            }

        });

    });

    // Window Load
    $(window).on('load', function () {
        // Scripts needs loading inside content area
        rdtheme_content_load_scripts();
        isSelect2();

        // Number Field range slider
        if ($.fn.ionRangeSlider) {
            $(".ion-rangeslider").each(function () {
                var $this = $(this);
                var rangeType = $this.data('type');
                $this.ionRangeSlider({
                    type: rangeType || "double",
                    drag_interval: true,
                    min_interval: null,
                    max_interval: null,
                    onChange: function (data) {
                        var $inp = data.input;
                        $inp.parent().find('.min-volumn').val(data.from);
                        $inp.parent().find('.max-volumn').val(data.to);
                    },
                });
            });
        }

        // Advanced Search Revel
        $(".advanced-btn").on("click", function () {
            $(this).toggleClass("collapsed");
            $("#advanced-search").toggleClass("show");

        });

        // Share Icon reveled
        $("#share-btn").on("click", function (e) {
            e.preventDefault();
            $(this).siblings('.share-icon').toggleClass('open');
        });

         // Delete Logo Image
        $(".remove-logo-image a").on("click", function (e) {
            e.preventDefault();
            let attachmentID = $(this).data('attachment_id');
            let postID = $(this).data('post_id');
            let container = $(this).parents('.logo-image');
            let inputWrapper = $('.logo-input-wrapper');

            let r = confirm('Are you want to delete this attachment?');

            if (r) {
                $.ajax({
                    type: "post",
                    url: ClDirectoryObj.ajaxUrl,

                    data: {
                        action: "delete_listing_logo_attachment",
                        attachment_id: attachmentID,
                        post_id: postID,
                    },
                    success: function (response) {
                        if (response === 'success') {
                            container.fadeOut(function () {
                                container.remove();
                                inputWrapper.toggleClass('d-none');
                            });
                        }
                    }
                })
            }
        });

    });

    function rdtheme_content_ready_scripts() {

        /*---------------------------------------
          Background Parallax
          --------------------------------------- */
        if ($(".rt-parallax-bg-yes").length) {
            $(".rt-parallax-bg-yes").each(function () {
                var speed = $(this).data('speed');
                $(this).parallaxie({
                    speed: speed ? speed : 0.5,
                    offset: 0,
                });
            });
        }

        $('.cldirectory-related-slider').each(function () {
            var $this = $(this); 
            var settings=$this.data('options');
            var autoplay= settings['autoplay'] == 1 ? true:false;
            new Swiper($this[0], {
                loop: true,
                slidesPerView: 3,
                speed:1000,
                autoplay:autoplay,
                spaceBetween:  24,
                navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
                breakpoints: {
                    0: {
                        slidesPerView: (settings['breakpoints']['0'] || {slidesPerView: 1})['slidesPerView'],
                    },
                    576: {
                        slidesPerView: (settings['breakpoints']['576'] || {slidesPerView: 2})['slidesPerView'],
                    },
                    768: {
                        slidesPerView: (settings['breakpoints']['768'] || {slidesPerView: 3})['slidesPerView'],
                    },
                    992: {
                        slidesPerView: (settings['breakpoints']['992'] || {slidesPerView: 3})['slidesPerView'],
                    },
                },
            });
        });

        //global swiper slider
        $('.rt-global-slider').each(function() {
            var $this = $(this);
            $this.fadeIn();
            var settings = $this.data('options');
            var autoplayconditon= settings['auto'];
            var $pagination = $this.find('.el-swiper-pagination')[0];
            var $next = $this.find('.custom-swiper-button-next')[0];
            var $prev = $this.find('.custom-swiper-button-prev')[0];
             new Swiper( $this[0], {
                speed:settings['speed'],
                direction:settings['direction'] ? settings['direction']:'horizontal',
                spaceBetween:  settings['spaceBetween'],
                slidesPerGroup: settings['slidesPerGroup'] ? settings['slidesPerGroup']:1,
                autoplay:autoplayconditon ? {delay:settings['autoplay']['delay']}:false,
                pagination: {
                    el: $pagination,
                    clickable: true,
                    type: 'bullets',
                },
                navigation: {
                    nextEl: $next,
                    prevEl: $prev,
                },
                breakpoints: {
                    0: {
                        slidesPerView: settings['breakpoints']['0']['slidesPerView'],
                    },
                    576: {
                        slidesPerView: settings['breakpoints']['576']['slidesPerView'],
                    },
                    768: {
                        slidesPerView: settings['breakpoints']['768']['slidesPerView'],
                    },
                    992: {
                        slidesPerView: settings['breakpoints']['992']['slidesPerView'],
                    },
                    1200: {
                        slidesPerView:  settings['breakpoints']['1200']['slidesPerView'],
                    },
                },
            });

        });

         // Testimonial Slider
         $('.vertical-slider').each(function() {
            var $this = $(this);

            var settings = $this.data('options');
            var autoplayconditon= settings['auto'];

            let vSlider = new Swiper(".vertical-slider", {
                // direction: "vertical",
                speed:settings['speed'],
                slidesPerView: 3,
                loop: true,
                spaceBetween:  settings['spaceBetween'],
                slidesPerGroup: settings['slidesPerGroup'] ? settings['slidesPerGroup']:1,
                autoplay:autoplayconditon ? {delay:settings['autoplay']['delay']}:false,
                grabCursor: true,
                pagination: {
                    el: ".vertical-slider-pagination.swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    0: {
                        slidesPerView: settings['breakpoints']['0']['slidesPerView'],
                    },
                    576: {
                        slidesPerView: settings['breakpoints']['576']['slidesPerView'],
                        direction: "vertical",
                    },
                    768: {
                        slidesPerView: settings['breakpoints']['768']['slidesPerView'],
                        direction: "vertical",
                    },
                    992: {
                        slidesPerView: settings['breakpoints']['992']['slidesPerView'],
                        direction: "vertical",
                    },
                    1200: {
                        slidesPerView:  settings['breakpoints']['1200']['slidesPerView'],
                        direction: "vertical",
                    },
                        
                },
            });

            vSlider.on('breakpoint', function (swiper, breakpointParams) {
                location.reload();
            });
        });


        /*-------------------------------
        // pricing table
        -------------------------------*/
        const pricingWrapper = $(".pricing-wrapper");
        if (pricingWrapper) {
            $(".pricing-wrapper").each(function() {
                const $switchContainer = $(this).find(".pricing-switch-container");
                $switchContainer.on("click", function() {
                    const $switch = $(this).find(".pricing-switch");
                    const $priceSwitchBox = $(this).parents(".price-switch-box");
                    const $tabContent = $(this).parents(".pricing-wrapper").find(".rt-tab-content");
                    $switch.toggleClass("pricing-switch-active");
                    $priceSwitchBox.toggleClass("price-switch-box--active");
                    $tabContent.toggleClass("rt-active");
                });
            });
        }

        /*===================================
        // Section background image 
        ====================================*/
        imageFunction();

        function imageFunction() {
            $("[data-bg-image]").each(function () {
            let img = $(this).data("bg-image");
            $(this).css({
                backgroundImage: "url(" + img + ")",
            });
            });
        }

        //elementor advance listing search
        // Add class to listing search filter radios
        $('.search-radio-check ul li:nth-child(2) label').addClass('active');
        var $rtSearchRadioButtons = $('.search-radio-check input[type="radio"]');
        $rtSearchRadioButtons.click(function () {
            $rtSearchRadioButtons.each(function () {
                $(this).parent().toggleClass('active', this.checked);
            });
        });
    }
   
    //Select2 js
    function isSelect2() {
        // Select2 Activation
        var $select2 = $('select.select2');
        if ($select2.length) {
            $select2.select2({
                theme: 'classic',
                dropdownAutoWidth: true,
                width: '100%',
            });
        }
    }

    function rdtheme_content_load_scripts() {

        $('.rtcl-sold-out, .section-title-wrapper .bg-title-wrap').fadeIn();
        $('.rtrs-review-wrap .rtrs-review-form .rtrs-form-group .rtrs-submit-btn').parent('.rtrs-form-group').addClass('rtrs-submit-button');
        // $('.single-product .product-amenities .amenities-list ')

        $('.button-times').on('click', function (e) {
            e.preventDefault();
            $(this).parents('.advanced-search-box').removeClass('show');
        });

        $(".advance-search-form.is-preloader").each(function () {
            $(this).removeClass('is-preloader');
        });


        //Add Class on search hover
        $(".header-btn .search-icon-wrapper .input-group .form-control").click(function () {
            $(this).parents('.search-icon').addClass('active');
        });

        //Remove Class on click out site of search
        $(document).on("click", function (e) {
            if ($(e.target).is(".header-btn .search-icon-wrapper .input-group .form-control") === false) {
                $(".header-btn .icon-hover-item").removeClass("active");
            }
        });

        // Isotope
        if (typeof $.fn.isotope == 'function') {
            // Run 1st time
            var $isotopeContainer = $('#inner-isotope');

            setTimeout(function () {
                $isotopeContainer.each(function () {
                    var $container = $(this).find('.featuredContainer'),
                        filter = $(this).find('.isotope-classes-tab a.current').data('filter');
                    runIsotope($container, filter);
                });

                // Run on click event

                $('.isotope-classes-tab a').on('click', function () {
                    $(this).closest('.isotope-classes-tab').find('.current').removeClass('current');
                    $(this).addClass('current');
                    var $container = $(this).closest('.isotope-wrap').find('.featuredContainer'),
                        filter = $(this).attr('data-filter');
                    runIsotope($container, filter);
                    return false;
                });

            }, 1000);
        }
  
    }

    // Elementor Frontend Load
    $(window).on('elementor/frontend/init', function () {
        if (elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', function () {
                rdtheme_content_ready_scripts();
                rdtheme_content_load_scripts();
                isSelect2();
            
            });
        }
    });
    $('.rt-el-listing-wrapper').each(function (){
        var $container = $(this).find('.featuredContainer');
        $container.isotope({
            transitionDuration: "1s",
            hiddenStyle: {
                opacity: 0,
                transform: "scale(0.001)"
            },
            visibleStyle: {
                transform: "scale(1)",
                opacity: 1
            }
        });
    })

    function runIsotope($container, filter) {
        $container.isotope({
            filter: filter,
            transitionDuration: "1s",
            originLeft: ClDirectoryObj.isRTL == 'rtl' ? false : true,
            hiddenStyle: {
                opacity: 0,
                transform: "scale(0.001)"
            },
            visibleStyle: {
                transform: "scale(1)",
                opacity: 1
            }
        });
    }

})(jQuery);



