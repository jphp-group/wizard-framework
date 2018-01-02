import Node from './Node';
import AppMediator from '../NX/AppMediator';
import Font from "./paint/Font";

class TextInputControl extends Node {
  constructor() {
    super();

    this.dom.on('keydown.TextInputControl', (e) => {
        AppMediator.sendUserInput(this, () => {
          return { text: this.text }
        });
    });
  }

  get placeholder() {
    return this.dom.attr('placeholder');
  }

  set placeholder(value) {
    this.dom.attr('placeholder', value);
  }

  get editable() {
    return !this.dom.prop('readonly');
  }

  set editable(value) {
    this.dom.prop('readonly', !value);
  }

  get textAlign() {
    return thid.dom.css('text-algin');
  }

  set textAlign(value) {
    this.dom.css('text-algin', value);
  }

  get font() {
    return Font.getFromDom(this.dom);
  }

  set font(value) {
    Font.applyToDom(this.dom, value);
  }

  get text() {
    return this.dom.val();
  }

  set text(value) {
    this.dom.val(value);
  }
}

export default TextInputControl;
