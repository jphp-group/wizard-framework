import Labeled from './Labeled';
import uiMediator from '../NX/UIMediator';

class Checkbox extends Labeled {
  constructor(text, graphic) {
    super(text, graphic);

    this.dom.on('click.Checkbox', () => {
      uiMediator.sendUserInput(this, {selected: this.selected});
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
