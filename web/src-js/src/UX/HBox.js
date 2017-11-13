import Container from './Container';

class HBox extends Container {

  constructor(nodes) {
    super(...arguments);

    this.spacing = 0;
    this.align = ['top', 'left'];
  }

  get fitHeight() {
    return this.dom.hasClass('ux-m-fit-height');
  }

  set fitHeight(value) {
    if (value) {
      this.dom.addClass('ux-m-fit-height');
    } else {
      this.dom.removeClass('ux-m-fit-height');
    }
  }

  get spacing() {
    return this._spacing;
  }

  set spacing(value) {
    this._spacing = value;
    var slots = this.dom.find('> div');

    slots.css('margin-right', value + 'px');
    slots.last().css('margin-right', 0);
  }

  createDom() {
    var dom = super.createDom();
    dom.addClass('ux-h-box');

    return dom;
  }

  createSlotDom(object) {
    var dom = super.createSlotDom(object);
    return dom;
  }

  add(nodes) {
    super.add(...arguments);
    this.spacing = this.spacing;
  }

  insert(index, nodes) {
    super.insert(...arguments);
    this.spacing = this.spacing;
  }
}

export default HBox;
