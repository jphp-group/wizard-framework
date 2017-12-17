import Labeled from './Labeled';

const kinds = [
  'success', 'primary', 'secondary', 'info', 'warning', 'danger', 'link', 'dark', 'light'
];

class Button extends Labeled {
  get outline() {
    return !!this.dom.data('--outline');
  }

  set outline(value) {
    const kind = this.kind;
    this.dom.data('--outline', !!value);
    this.kind = kind;
  }

  get kind() {
    const dom = this.dom;

    for (let kind of kinds) {
      if (dom.hasClass(`btn-${kind}`) || dom.hasClass(`btn-outline-${kind}`)) {
        return kind;
      }
    }

    return 'default';
  }

  set kind(value) {
    this.dom.removeClass(`btn-${this.kind}`);
    this.dom.removeClass(`btn-outline-${this.kind}`);

    if (this.outline) {
      this.dom.addClass(`btn-outline-${value}`);
    } else {
      this.dom.addClass(`btn-${value}`);
    }
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
