/**
 * On page load, grab the google qr code link and give it the switching functionality.
 */
(function ($) {
  Drupal.behaviors.jquery_qr_code = {
    attach: function(context, settings) {
      $('#block-googleqrcode a').click(function(){
        $('#block-googleqrcode .content').html('');
        createQRcode();
        return false;
      });
    }
  }
})(jQuery);

/**
 * Onclick function for displaying QR code from google
 */
function createQRcode(){
  googleQRarguments = "?chs=" + drupalSettings.google_qr_code_width + "x" +
      drupalSettings.google_qr_code_height + "&cht=qr&chl=" + drupalSettings.google_qr_code_url +
  '&chld=H|0';
  output = '<div class="inner"><img src="https://chart.googleapis.com/chart'
 + googleQRarguments + '"></img></div>';
  jQuery('#block-googleqrcode .content').html(output);
}
