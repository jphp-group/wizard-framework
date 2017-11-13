import TextInputControl from './TextInputControl';

class TextArea extends TextInputControl {

  get wrap() {
    return this.dom.attr('wrap');
  }

  set wrap(value) {
    this.dom.attr('wrap', value);
  }

  createDom() {
    var dom = jQuery('<textarea class="form-control ux-text-input-control ux-text-area" />');
    return dom;
  }
}

export default TextArea;
