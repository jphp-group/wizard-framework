
import Checkbox from "./Checkbox";
import AppMediator from "../NX/AppMediator";

const KINDS = [
  'success', 'primary', 'secondary', 'info', 'warning', 'danger', 'link', 'dark', 'light'
];

class Switch extends Checkbox {

  constructor() {
    super();
  }

  set kind(value) {
    if (!value) {
      value = 'default';
    }

    const dom = this.dom.find('label');

    dom.removeClass(`badge-${this.kind}`);

    if (value) {
      dom.addClass(`badge-${value}`);
    }
  }

  get kind() {
    const dom = this.dom.find('label');

    for (let kind of KINDS) {
      if (dom.hasClass(`badge-${kind}`)) {
        return kind;
      }
    }

    return 'default';
  }

  createDom() {
    const dom = jQuery('<span><input type="checkbox" /><label class="badge-default"></label><span class="ux-labeled-text"></span></span>');
    dom.addClass('ux-labeled');
    dom.addClass('ux-switch');


    dom.on('click.Switch', (e) => {
      const checkbox = dom.find('input[type=checkbox]');

      if (!checkbox.prop('disabled')) {
        checkbox.prop('checked', !checkbox.prop('checked'));

        AppMediator.sendUserInput(this, {selected: this.selected}, () => {
          this.trigger('action', e);
        });
      }
    });

    return dom;
  }
}

export default Switch;