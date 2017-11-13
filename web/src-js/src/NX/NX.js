import App from './App';
import Logger from './Logger';
import Fragment from './Fragment';
import UILoader from './UILoader';


export default {
  App, Logger, UILoader, Fragment,

  init: function () {
    var app = new App();

    jQuery(document).find('[data-fragment]').each(function () {
      var fragment = $(this).attr('data-fragment');

      if (fragment) {
        var cls = window[fragment];

        if (!cls) {
          console.warn(`${cls} cannot find global class for fragment`);
          return;
        }

        var fragment = new cls();
        $(this).data('--wrapper', fragment);
        fragment.render(this);
      }
    });

    window.NX.app = app;
    return app;
  }
}
