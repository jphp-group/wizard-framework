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
      const dom = jQuery('<label><input type="checkbox"> <span class="ux-labeled-text"></span></label>');
      dom.addClass('ux-labeled');
      dom.addClass('ux-checkbox');
      return dom;
  }

  get checked() {
    return this.dom.find('> input[type=checkbox]').prop('checked');
  }

  set checked(value) {
    this.dom.find('> input[type=checkbox]').prop('checked', value);
  }

  get selected() {
    return this.checked;
  }

  set selected(value) {
    this.checked = value;
  }
}

export default Checkbox;
