(function (window, document, $) {
	'use strict';

	//variables
	var _window = $(window),
		_document = $(document),
		_body = $('body');

	//scrollspy
	var _scrollSpy = function () {
		var hash = function (h) {
			if (history.pushState) {
				history.pushState(null, null, h);
			} else {
				location.hash = h;
			}
		};

		_document.on('click', 'a', function (event) {

			var _refVal = $(this).attr("href");
            $(".listing-nav-menu > li > a").removeClass('active');
            $(this).addClass('active');
			if ($(this).attr('href') !== "#" && $(this).attr('href').indexOf("#") > -1 && $(_refVal).length) {
				event.preventDefault();
				$("html, body").animate({
					scrollTop: $(_refVal).offset().top - 200
				}, {
					duration: 500,
					complete: hash(_refVal)
				});

			}
		});
	}

	

	_scrollSpy();


})(window, document, jQuery);