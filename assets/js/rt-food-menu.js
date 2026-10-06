(function ($) {
    "use strict";


    // By MAHBUBUR
    function updateRecipeIndexing() {
        $('.rn-recipe-wrap').find('.rn-recipe-item').each(function (i, item) {
            $('input, textarea', item).each(function () {
                // Rename first array value from name to group index
                var _recipe_input = $(this);
                _recipe_input.attr('name', _recipe_input.attr('name').replace(/cldirectory_food_list\[[^\]]*\]/, 'cldirectory_food_list[' + i + ']'));
                _recipe_input.attr('name', _recipe_input.attr('name').replace(/cldirectory_food_images\[[^\]]*\]/, 'cldirectory_food_images[' + i + ']'));
            });
            $('.rn-ingredient-item', item).each(function (ii, ingredient) {
                $('input, textarea', ingredient).each(function () {
                    // Rename first array value from name to group index
                    var _ingredient = $(this);
                    _ingredient.attr('name', _ingredient.attr('name').replace(/\[food_list\]\[[^\]]*\]/, '[food_list][' + ii + ']'));
                });
            });
        });
    }

    function getIngredientHtml() {
        return '<div class="rn-ingredient-item">' +
            '<div class="rn-ingredient-fields">' +
            '<input name="cldirectory_food_list[][food_list][][title]" type="text" placeholder="Title" class="form-control">' +
            '<input name="cldirectory_food_list[][food_list][][foodprice]" type="text" placeholder="Price" class="form-control">' +
            '<textarea name="cldirectory_food_list[][food_list][][description]" placeholder="Description" class="form-control"></textarea>' +
            '<div class="food-image-wrap"><div class="floor-input-wrapper"><input name="cldirectory_food_images[][food_list][]" class="cldirectory-food-image" type="file"/></div></div>' +
            '</div>' +
            '<span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>' +
            '</div>';
    }

    $(document).on('click', '.rn-recipe-wrapper .add-recipe', function (e) {
        e.preventDefault();

        var _self = $(this),
            recipe = '<div class="rn-recipe-item">' +
                '<span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>' +
                '<div class="rn-recipe-title"><input type="text" name="cldirectory_food_list[][gtitle]" class="form-control" placeholder="Group Title"></div>' +
                '<div class="rn-ingredient-wrap">' + getIngredientHtml() + '</div>' +
                '<div class="rn-ingredient-actions"><a href="javascript:void()" class="btn-upload add-ingredient btn-sm btn-primary">Add Food</a></div>' +
                '</div>';
        _self.closest('.rn-recipe-wrapper').find('.rn-recipe-wrap').append(recipe);
        updateRecipeIndexing();

    });

    
    $(document).on('click', '.rn-recipe-item > .rn-remove', function (e) {
        e.preventDefault();
        var _self = $(this);
        if (_self.closest('.rn-recipe-wrapper').find('.rn-recipe-item').length >= 0) {
            _self.closest('.rn-recipe-item').slideUp('slow', function () {
                $(this).remove();
                updateRecipeIndexing();
            });
        } else {
            alert('You are not permited to remove all recipe');
        }
    });


    $(document).on('click', '.rn-recipe-item .add-ingredient', function (e) {
        e.preventDefault();
        var _self = $(this),
            ingredient = getIngredientHtml();
        _self.closest('.rn-recipe-item').find('.rn-ingredient-wrap').append(ingredient);
        updateRecipeIndexing();
    });


    $(document).on('click', '.rn-ingredient-item .rn-remove', function (e) {
        e.preventDefault();
        var _self = $(this);
        if (_self.closest('.rn-ingredient-wrap').find('.rn-ingredient-item').length >= 0) {
            _self.closest('.rn-ingredient-item').slideUp('slow', function () {
                $(this).remove();
                updateRecipeIndexing();
            });
        } else {
            alert('You are not permited to remove all ingradient');
        }
    });
    // END MAHBUBUR


    // Delete Floor Image
    $(".remove-food-image a").on("click", function (e) {
        e.preventDefault();
        let attachmentID = $(this).data('attachment_id');
        // let indexNo = $(this).data('index');
        let postID = $(this).data('post_id');
        let container = $(this).parents('.food-image');
        let inputWrapper = $('.food-input-wrapper');

        let r = confirm('Are you want to delete this attachment?');

        if (r) {
            $.ajax({
                type: "post",
                url: rtcl.ajaxurl,
                data: {
                    action: "delete_food_attachment",
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

    /* ====== Food Menu Accordion ====== */
    function listingFAQ() {
        // Initially hide all FAQ contents
        // Toggle on button click
        $('.rtcl-faqs-content').each(function () {
            var $faqContainer = $(this);
            $faqContainer.find('.faq-item.active .faq-content').show();
            $faqContainer.find('.faq-heading').on('click', function (e) {
                e.preventDefault();

                var $faqItem = $(this).closest('.faq-item');
                var $content = $faqItem.find('.faq-content');
                var $button = $(this).find('button');

                // Close other items in this container only
                $faqContainer.find('.faq-item').not($faqItem).find('.faq-content').slideUp();
                $faqContainer.find('.faq-item').not($faqItem).removeClass('active');
                $faqItem.toggleClass('active');
                //$faqContainer.find('.faq-item').not($faqItem).find('i').removeClass('rt-icon-minus').addClass('rt-icon-plus');

                // Toggle current item
                $content.slideToggle();
                // $button.toggleClass('rt-icon-plus rt-icon-minus');
            });
        });
    }
    listingFAQ();


})(jQuery);