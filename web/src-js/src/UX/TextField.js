import TextInputControl from './TextInputControl';

class TextField extends TextInputControl {
  createDom() {
    var dom = jQuery('<input type="text" class="form-control ux-text-input-control ux-text-field" />');
    return dom;
  }
}

export default TextField;
