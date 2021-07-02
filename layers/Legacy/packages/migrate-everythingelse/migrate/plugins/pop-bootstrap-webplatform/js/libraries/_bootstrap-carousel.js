(function ($) {
    popBootstrapCarousel = {

        //-------------------------------------------------
        // PUBLIC FUNCTIONS
        //-------------------------------------------------

        carouselStatic : function (args) {

            var t = this;
            var pageSection = args.pageSection, block = args.block, targets = args.targets;

            t.prepareCarousel(pageSection, block, targets);

            targets.carousel(
                {
                    interval: false,
                    wrap: false
                }
            );
        },

        carouselAutomatic : function (args) {

            var t = this;
            var pageSection = args.pageSection, block = args.block, targets = args.targets;
        
            // Stop the Carousel when filtering, if not Javascript error
            block.on(
                'beforeReload',
                function (e) {
            
                    // After filtering, possibly there will be no results, so start the carousel only if not empty
                    targets.carousel('pause');
                }
            );

            t.prepareCarousel(pageSection, block, targets);
            
            // Initialize if there's one active item. If none, the carousel has no elements at all
            if (carousel.find('.item.active').length) {
                if (gdBootstrapCarousel.numberSlides(carousel) >= 2) {
                    // Start Automatic Carousel except for mobile phones, or we have an ugly problem where the homepage keeps moving (because height of slides is not uniform)
                    if ($(window).width() >= 768) {
                            carousel.carousel();
                    } else {
                        carousel.carousel(
                            {
                                interval: false,
                                wrap: false
                             }
                        );
                    }
                    carousel.find('.pop-carousel-controls').removeClass('hidden');
                } else {
                    carousel.find('.pop-carousel-controls').addClass('hidden');
                }
            }
        },

        //-------------------------------------------------
        // PROTECTED functions
        //-------------------------------------------------

        prepareCarousel : function (pageSection, block, targets) {

            var t = this;
        
            // When filtering, gotta re-add 'active' to the first slide
            block.on(
                'rendered',
                function () {

                    t.targetsAddActive(targets);
                }
            );

            t.targetsAddActive(targets);
        },
        targetsAddActive : function (targets) {

            var t = this;
            targets.each(
                function () {
    
                    // Add the 'active' class
                    var carousel = $(this);
                    t.addActive(carousel);
                }
            );
        },

        // carouselOverflowVisible : function(carousel) {

        //     var t = this;

        //     // Comment Leo 04/05/2014: With carousel in the Homepage we couldn't have a post background color extend until the border of the page,
        //     // because of the property 'overflow': 'hidden', which hid the +-15px from class row inside the carousel
        //     // Then, we take away that attr, and add it only when doing the slide transition
        //     // This way, we can add the hover effect to change the background color of the post, and it does it until the end of the page
        //     carousel.find('.carousel-inner').css({overflow: 'visible'});
        //     carousel.on('slide.bs.carousel', function () {
        //         carousel.find('.carousel-inner').css({overflow: 'hidden'});
        //     });
        //     carousel.on('slid.bs.carousel', function () {
        //         carousel.find('.carousel-inner').css({overflow: 'visible'});
        //     });
        // },

        addActive : function (carousel) {
    
            var t = this;
        
            // Add 'active' to the first item of carousel, and then initialize ('active' is not added in the .tmpl to be merged)
            if (carousel.find('.item.active').length == 0) {
                // Add 'active' to first item
                carousel.find('.item:first-child').addClass('active');
            }
        },

        showElement : function (carousel, elem) {

            var t = this;
            var slide = elem.closest('.item');
            var slideNumber = slide.parent().children().index(slide);
            t.slideTo(carousel, slideNumber);
        },
    
        slideTo : function (carousel, number) {

            var t = this;
            carousel.carousel(number);
        },

        next : function (carousel) {

            var t = this;
            carousel.carousel('next');
        },
    
        prev : function (carousel) {

            var t = this;
            carousel.carousel('prev');
        },
    
        isFirstSlide : function (carousel) {
    
            var t = this;
            return carousel.find('.item:first-child').hasClass('active');
        },
    
        isLastSlide : function (carousel) {
    
            var t = this;
            return carousel.find('.item:last-child').hasClass('active');
        },
    
        isNthSlide : function (carousel, position) {
    
            var t = this;
            return carousel.find('.item:nth-child('+position+')').hasClass('active');
        },
    
        isNthLastSlide : function (carousel, position) {
    
            var t = this;
            return carousel.find('.item:nth-last-child('+position+')').hasClass('active');
        },
    
        numberSlides : function (carousel) {
    
            var t = this;
            return carousel.find('.item').length;
        },

    };
})(jQuery);


(function ($) {
    popBootstrapCarouselControls = {
        
        //-------------------------------------------------
        // PRIVATE
        //-------------------------------------------------

        // items : {},

        //-------------------------------------------------
        // PUBLIC functions
        //-------------------------------------------------

        initBlockRuntimeMemory : function (args) {
    
            var t = this;
            var pageSection = args.pageSection, block = args.block, mempage = args.runtimeMempage;

            // Initialize with this library key
            mempage.carouselControls = {};

            // Reset values
            t.resetBlockRuntimeMemory(pageSection, block);
        },

        // preserveState : function(args) {
    
        //     var t = this;
        //     var pageSection = args.pageSection, state = args.state;
        //     var pssId = popManager.getSettingsId(pageSection);

        //     // console.log(state);
        //     state.bootstrapCarouselControls = {
        //         items: $.extend({}, t.items[pssId])
        //     };
        // },

        // retrieveState : function(args) {
    
        //     var t = this;
        //     var pageSection = args.pageSection, state = args.state.bootstrapCarouselControls;
        //     var pssId = popManager.getSettingsId(pageSection);

        //     t.items[pssId] = state.items;
        // },

        // clearState : function(args) {
    
        //     var t = this;
        //     var pageSection = args.pageSection;
        //     var pssId = popManager.getSettingsId(pageSection);

        //     t.items[pssId] = {};
        // },

        carouselControls : function (args) {
    
            var t = this;

            var pageSection = args.pageSection, block = args.block, targets = args.targets;

            var carousel = t.getCarousel(targets);

            var controlNext = targets.children('.pop-carousel-control.right');
            t.controlCarouselNext(pageSection, block, carousel, targets, controlNext);

            var controlPrev = targets.children('.pop-carousel-control.left');
            t.controlCarouselPrev(pageSection, block, carousel, targets, controlPrev);

            // Initialize block
            block.on(
                'fetchCompleted',
                function (e) {
        
                    t.handleReload(pageSection, block, targets);
                }
            );

            // Initialize variables
            // t.initVars(pageSection, block);
        
            // Initialize: set state of buttons (enabled / disabled)
            t.setEnabledDisabled(pageSection, block, targets);
        },

        //-------------------------------------------------
        // PROTECTED functions
        //-------------------------------------------------

        getRuntimeMemoryPage : function (pageSection, targetOrId) {

            var t = this;
            return popManager.getRuntimeMemoryPage(pageSection, targetOrId).carouselControls;
        },

        resetBlockRuntimeMemory : function (pageSection, targetOrId) {

            var t = this;
            var mempage = t.getRuntimeMemoryPage(pageSection, targetOrId);
            var empty = {

                state: '',
            };

            $.extend(mempage, empty);
        },

        slideNext : function (pageSection, block, carousel, controlNext) {
    
            var t = this;

            // var pageSection = args.pageSection, block = args.block, targets = args.targets;
            var blockParams = popManager.getBlockParams(pageSection, block);

            // If stopped loading and we are in the slide before the last one, disable control
            if (popBootstrapCarousel.isNthLastSlide(carousel, 2) && blockParams[M.URLPARAM_STOPFETCHING]) {
                t.disable(controlNext);
            }
        
            // Remove 'disabled' from Prev control
            t.enable(controlNext.siblings('.pop-carousel-control.left'));

            // Slide
            popBootstrapCarousel.next(carousel);
        },
    
        controlCarouselNext : function (pageSection, block, carousel, carouselControls, control) {
    
            var t = this;

            // var pageSection = args.pageSection, block = args.block, targets = args.targets;
            var blockParams = popManager.getBlockParams(pageSection, block);

            control.click(
                function (e) {
    
                    e.preventDefault();
                    var control = $(this);
        
                    // If control is disabled, do nothing
                    if (t.disabled(control)) {
                        return;
                    }
                
                    // var carousel = $(control.data('target'));

                    // If we are in the last slide, trigger the fetch on the template block
                    if (popBootstrapCarousel.isLastSlide(carousel)) {
                         // If we are already loading, or stopped loading altogether, then disable control and do nothing
                        if (blockParams.loading.length || blockParams[M.URLPARAM_STOPFETCHING]) {
                            return;
                        }

                        // Load more
                        popManager.fetchBlock(pageSection, block, {operation: M.URLPARAM_OPERATION_APPEND});
                
                        t.setControlState(pageSection, block, 'loading');
                    }
            
                    // Otherwise, just slide
                    else {
                         t.slideNext(pageSection, block, carousel, control);
                    }
                }
            );
        },

        controlCarouselPrev : function (pageSection, block, carousel, carouselControls, control) {
    
            var t = this;

            control.click(
                function (e) {
    
                    e.preventDefault();
                    var control = $(this);
        
                    // If control is disabled, do nothing
                    if (t.disabled(control)) {
                        return;
                    }
                    
                    // var carousel = $(control.data('target'));
            
                    // If we are in the first slide, disable Prev control
                    if (popBootstrapCarousel.isNthSlide(carousel, 2)) {
                         t.disable(control);
                    }

                    // If there are more slides later, re-enable Next Control
                    if (popBootstrapCarousel.numberSlides(carousel) > 1) {
                         t.enable(control.siblings('.pop-carousel-control.right'));
                    }

                    // Slide
                    popBootstrapCarousel.prev(carousel);
                }
            );
        },

        handleReload : function (pageSection, block, carouselControls) {
    
            var t = this;
            // var blockFeedback = popManager.getBlockFeedback(pageSection, block);
            var dataset = popManager.getBlockDataset(pageSection, block);

            // Set controls enabled / disabled
            t.setEnabledDisabled(pageSection, block, carouselControls);
        
            // Only if no message is being shown, and if the last state from the control was 'next'
            // if (!blockFeedback.msg && t.getControlState(pageSection, block) == 'loading') {

            // Only if there are results, and if the last state from the control was 'next'
            if (dataset && dataset.length && t.getControlState(pageSection, block) == 'loading') {
                var carousel = t.getCarousel(carouselControls);
                var controlNext = carouselControls.children('.pop-carousel-control.right');
                t.slideNext(pageSection, block, carousel, controlNext);
            }
        
            // Reset state
            t.setControlState(pageSection, block, '');
        },

        // initVars : function(pageSection, block) {

        //     var t = this;
        //     var pssId = popManager.getSettingsId(pageSection);
        //     var bsId = popManager.getSettingsId(block);

        //     if (!t.items[pssId]) {
        //         t.items[pssId] = {};
        //     }

        //     t.items[pssId][bsId] = { state : '' };
        // },
    
        getControlState : function (pageSection, block) {
    
            var t = this;
            var mempage = t.getRuntimeMemoryPage(pageSection, block);
        
            return mempage.state;
        },
    
        setControlState : function (pageSection, block, state) {
    
            var t = this;
            var mempage = t.getRuntimeMemoryPage(pageSection, block);
        
            mempage.state = state;
        },

        getCarousel : function (carouselControls) {
    
            var t = this;
            return $(carouselControls.data('target'));
        },
    
        setEnabledDisabled : function (pageSection, block, carouselControls) {
        
            var t = this;

            var carousel = t.getCarousel(carouselControls);
            var controlPrev = carouselControls.children('.pop-carousel-control.left');
            var controlNext = carouselControls.children('.pop-carousel-control.right');

            if (popBootstrapCarousel.numberSlides(carousel) == 0) {
                // No results, disable both
                t.disable(controlPrev);
                t.disable(controlNext);
            } else {
                // Disable the prev button
                if (popBootstrapCarousel.isFirstSlide(carousel)) {
                    t.disable(controlPrev);
                } else {
                    t.enable(controlPrev);
                }

                // Disable / Enable next button
                var blockParams = popManager.getBlockParams(pageSection, block);
                // if (popBootstrapCarousel.isLastSlide(carousel) && blockParams[M.DATALOAD_INTERNALPARAMS][M.URLPARAM_STOPFETCHING]) {
                if (popBootstrapCarousel.isLastSlide(carousel) && blockParams[M.URLPARAM_STOPFETCHING]) {
                    t.disable(controlNext);
                } else {
                    t.enable(controlNext);
                }
            }
        },
    
        disable : function (control) {
    
            var t = this;
            control.addClass('disabled');
            control.attr('disabled', true);
        },
    
        enable : function (control) {
    
            var t = this;
            control.removeClass('disabled');
            control.attr('disabled', false);
        },
    
        disabled : function (control) {
    
            var t = this;
            return control.hasClass('disabled');
        }
    };
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popBootstrapCarousel, ['carouselStatic', 'carouselAutomatic']);
popJSLibraryManager.register(popBootstrapCarouselControls, ['initBlockRuntimeMemory', 'controlCarouselPrev', 'controlCarouselNext', 'carouselControls']);