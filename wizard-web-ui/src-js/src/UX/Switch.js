
import Checkbox from "./Checkbox";
import AppMediator from "../NX/AppMediator";
import Utils from "./util/Utils";

const KINDS = [
  'success', 'primary', 'secondary', 'info', 'warning', 'danger', 'link', 'dark', 'light'
];

class Switch extends Checkbox {

  constructor() {
    super();
  }

  get iconSize() {
    return this.dom.find('label').css('font-size');
  }

  set iconSize(value) {
    const dom = this.dom.find('label');
    dom.css('font-size', value);
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

  get iconGap() {
    const labeled = this.dom.find('.ux-labeled-text');
    switch (this.iconDisplay) {
      case 'left': return Utils.toPt(labeled.css('margin-left'));
      case 'right': return Utils.toPt(labeled.css('margin-right'));
    }

    return 0;
  }

  set iconGap(value) {
    const labeled = this.dom.find('.ux-labeled-text');
    labeled.css('margin-right', '');
    labeled.css('margin-left', '');

    switch (this.iconDisplay) {
      case 'left':
        labeled.css('margin-left', value); break;

      case 'right':
        labeled.css('margin-right', value); break;
    }
  }

  get iconDisplay() {
    if (this.dom.find('> *:first').hasClass('ux-labeled-text')) {
      return 'right';
    } else {
      return 'left';
    }
  }

  set iconDisplay(value) {
    if (this.iconDisplay === value) return;

    const gap = this.iconGap;

    const checkbox = this.dom.find('input[type=checkbox]');
    const label = this.dom.find('label');
    label.remove();
    checkbox.remove();

    let labeled = this.dom.find('.ux-labeled-text');

    switch (value) {
      case "right":
        label.insertAfter(labeled);
        checkbox.insertAfter(labeled);
        break;
      case "left":
        checkbox.insertBefore(labeled);
        label.insertBefore(labeled);
        break;
    }

    this.iconGap = gap;
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