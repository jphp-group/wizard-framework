import Container from './Container';
import Node from './Node';
import uiMediator from '../NX/UIMediator';

class ListView extends Container {
  constructor() {
    super();

    this.spacing = 0;
    this.align = ['center', 'left'];

    this.dom.on('action.ListView', () => {
      const data = {
        selected: this.selected,
        selectedIndex: this.selectedIndex
      };

      uiMediator.sendUserInput(this, data);
    })
  }

  get selectedIndex() {
    let index = -1;
    let result = -1;

    this.dom.find('> .ux-slot').each(function () {
      index++;

      if (jQuery(this).hasClass('active')) {
        result = index;
        return true;
      }
    });

    return result;
  }

  set selectedIndex(value) {
    const children = this.children();

    if (value >= 0 && value < children.length) {
      this.selected = children[value];
    } else {
      this.selected = null;
    }
  }

  get selected() {
    const dom = this.dom.find('> .ux-slot.active').first();

    if (dom) {
      return Node.getFromDom(dom);
    }

    return null;
  }

  set selected(object) {
    this.dom.find('> .ux-slot.active').removeClass('active');

    if (object instanceof Node) {
      object.dom.closest('.ux-slot').addClass('active');
    }
  }

  createDom() {
    const dom = super.createDom();
    dom.addClass('list-group');
    dom.addClass('ux-list-view');
    return dom;
  }

  createSlotDom(object) {
    if (!(object instanceof Node)) {
      throw new TypeError('createSlotDom(): 1 argument must be instance of Node')
    }

    const dom = jQuery('<a href="#" class="list-group-item ux-slot" />').append(object.dom);

    dom.on('click.ListView', (e) => {
      dom.closest('.ux-list-view').find('> .ux-slot').removeClass('active');
      dom.addClass('active');

      this.dom.trigger('action');
      e.preventDefault();
      return false;
    });

    dom.data('--wrapper', object);
    object.dom.data('--wrapper-dom', dom);
    return dom;
  }
}

export default ListView;
