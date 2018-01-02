import Container from './Container';

class VBox extends Container {

  constructor(nodes) {
    super(...arguments);

    this.spacing = 0;
    this.align = ['top', 'left'];
  }

  get fitWidth() {
    return this.dom.hasClass('ux-m-fit-width');
  }

  set fitWidth(value) {
    if (value) {
      this.dom.addClass('ux-m-fit-width');
    } else {
      this.dom.removeClass('ux-m-fit-width');
    }
  }

  get spacing() {
    return this._spacing;
  }

  set spacing(value) {
    this._spacing = value;
    const slots = this.dom.find('> div');

    slots.css('margin-bottom', value + 'px');
    slots.last().css('margin-bottom', 0);
  }

  createDom() {
    const dom = super.createDom();
    dom.addClass('ux-v-box');
    return dom;
  }

  createSlotDom(object) {
    const dom = super.createSlotDom(object);

    if (this.spacing !== 0) {
      const slots = this.dom.find('> div');
      slots.last().css('margin-bottom', this.spacing);
      dom.css('margin-bottom', 0);
    }

    return dom;
  }
}

export default VBox;
