(function ($) {
  'use strict';

  $.fn.extend({
    addGiftCardToOrder: function () {
      const $element = $(this);
      const url = $element.data('action');
      const redirectUrl = $element.data('redirect');

      $element.on('submit', function (event) {
        event.preventDefault();

        $.ajax(url, {
          method: 'POST',
          data: $element.serialize(),
          success: function () {
            window.location.href = redirectUrl;
          },
          error: function (xhr) {
            $('.nextstore-sylius-gift-card-gift-card-block').replaceWith(xhr.responseText);

            $('#nextstore-sylius-gift-card-add-gift-card-to-order').addGiftCardToOrder();
          },
        });
      });

    },
  });
})(jQuery);
