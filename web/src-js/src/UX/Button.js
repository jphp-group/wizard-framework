import Labeled from './Labeled';

class Button extends Labeled {
    get kind() {
      const dom = this.dom;

      if (dom.hasClass('btn-primary')) {
        return 'primary';
      } else if (dom.hasClass('btn-success')) {
        return 'success';
      } else if (dom.hasClass('btn-info')) {
        return 'info';
      } else if (dom.hasClass('btn-warning')) {
        return 'warning';
      } else if (dom.hasClass('btn-danger')) {
        return 'danger';
      } else if (dom.hasClass('btn-link')) {
        return 'link';
      }

      return 'default';
    }

    set kind(value) {
      this.dom.removeClass(`btn-${this.kind}`);
      this.dom.addClass(`btn-${value}`);
    }

    createDom() {
        const dom = jQuery('<button><span class="ux-labeled-text"></span></button>');
        dom.addClass('ux-labeled');
        dom.addClass('ux-button');

        dom.addClass('btn');
        dom.addClass('btn-default');

        return dom;
    }
}

export default Button;
