import Labeled from './Labeled';

class Checkbox extends Labeled {
  createDom() {
      var dom = jQuery('<label><input type="checkbox"> <span class="ux-labeled-text"></span></label>');
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
