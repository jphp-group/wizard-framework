import Labeled from './Labeled';
import AppMediator from '../NX/AppMediator';

class Checkbox extends Labeled {
  constructor(text, graphic) {
    super(text, graphic);

    this.dom.on('click.Checkbox', (e) => {
      if (e.target.tagName === 'INPUT') {
        AppMediator.sendUserInput(this, {selected: this.selected}, () => {
          this.trigger('action', e);
        });
      }
    });
  }

  createDom() {
      const dom = jQuery('<span><label><input type="checkbox"><span class="cr"><i class="cr-icon material-icons" style="font-weight: bold;">check</i></span><span class="ux-labeled-text"></span></label></span>');
      dom.addClass('ux-labeled');
      dom.addClass('ux-checkbox');
      return dom;
  }

  get checked() {
    return this.dom.find('input[type=checkbox]').prop('checked');
  }

  set checked(value) {
    this.dom.find('input[type=checkbox]').prop('checked', value);
  }

  get selected() {
    return this.checked;
  }

  set selected(value) {
    this.checked = value;
  }

  get enabled() {
    return !this.dom.find('input[type=checkbox]').prop("disabled");
  }

  set enabled(value) {
    this.dom.find('input[type=checkbox]').prop('disabled', !value);
  }
}

export default Checkbox;
