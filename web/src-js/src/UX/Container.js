import Node from "./Node";

class Container extends Node {
  constructor(nodes) {
      super();
      this.add(...arguments);
  }

  get align() {
    return [this.verAlign, this.horAlign];
  }

  set align(value) {
    if (value instanceof Array && value.length >= 2) {
      this.horAlign = value[1];
      this.verAlign = value[0];
    }
  }

  get horAlign() {
    if (this.dom.hasClass('ux-m-halign-right')) {
      return 'right';
    } else if (this.hasClass('ux-m-halign-center')) {
      return 'center';
    }

    return 'left';
  }

  set horAlign(value) {
    this.dom.removeClass('ux-m-halign-left');
    this.dom.removeClass('ux-m-halign-right');
    this.dom.removeClass('ux-m-halign-center');

    this.dom.addClass('ux-m-halign-' + value);
  }

  get verAlign() {
    if (this.dom.hasClass('ux-m-valign-bottom')) {
      return 'bottom';
    } else if (this.hasClass('ux-m-valign-center')) {
      return 'center';
    }

    return 'top';
  }

  set verAlign(value) {
    this.dom.removeClass('ux-m-valign-top');
    this.dom.removeClass('ux-m-valign-bottom');
    this.dom.removeClass('ux-m-valign-center');

    this.dom.addClass('ux-m-valign-' + value);
  }

  createSlotDom(object) {
    if (!(object instanceof Node)) {
      throw new TypeError('createSlotDom(): 1 argument must be instance of Node')
    }

    const dom = jQuery('<div/>').append(object.dom);
    dom.addClass('ux-slot');

    dom.data('--wrapper', object);
    object.dom.data('--wrapper-dom', dom);
    return dom;
  }

  createDom() {
    const dom = jQuery('<div></div>');
    dom.addClass('ux-container');

    return dom;
  }

  child(id) {
    const dom = this.dom.find(`#${id}`);

    if (dom && dom.length) {
      return Node.getFromDom(dom);
    }

    return null;
  }

  count() {
    return this.dom.children().length;
  }

  children() {
    const children = [];

    this.dom.children().each(function () {
      children.push(Node.getFromDom(jQuery(this)));
    });

    return children;
  }

  removeByIndex(index) {
    const child = this.children()[index];
    
    if (child) {
      child.free();
    }
  }

  add(nodes) {
    for (let i = 0; i < arguments.length; i++) {
      this.dom.append(this.createSlotDom(arguments[i]));
    }

    return this;
  }

  insert(index, nodes) {
    index = index | 0;

    const children = this.dom.children();

    if (!children.length || index >= children.length) {
      return this.add(...Array.prototype.slice.call(arguments, 1));
    }

    nodes = Array.prototype.slice.call(arguments, 1);

    let i = 0;
    const self = this;

    this.dom.children().each(function () {
      if (index === i) {
        for (let k = 0; k < nodes.length; k++) {
          const slot = self.createSlotDom(nodes[k]);
          slot.insertBefore(this);
        }

        return false;
      }

      i++;
    });

    return this;
  }

  clear() {
    this.dom.empty();
  }

  show() {
    this.dom.css('display', '');
    return this;
  }
}

export default Container;
