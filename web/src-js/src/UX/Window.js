import Node from './Node';
import Container from "./Container";

class Window extends Container {
  constructor() {
    super();

    this.contentDom = this.dom.find('.modal-body');
    this.dom.modal();
    this.dom.on('hide.bs.modal.Window', () => {
      if (this.uiMediator) {
        const data = {
          visible: false,
          close: true,
        };

        this.uiMediator.sendUserInput(this, data);
      }
    });
  }

  get centered() {
    return this.dom.hasClass('ux-centered')
  }

  set centered(value) {
    if (value) {
      this.dom.addClass('ux-centered');
    } else {
      this.dom.removeClass('ux-centered')
    }
  }

  get title() {
    return this.dom.find('.ux-window-title').text();
  }

  set title(value) {
    return this.dom.find('.ux-window-title').text(value);
  }

  get width() {
    return this.dom.find('.modal-dialog').width()
  }

  set width(value) {
    this.__triggerPropertyChange('width', value);
    this.dom.find('.modal-dialog').width(value);
  }

  get height() {
    return this.dom.find('.modal-dialog').height()
  }

  set height(value) {
    this.__triggerPropertyChange('height', value);
    this.dom.find('.modal-dialog').height(value);
  }

  hide() {
    this.dom.modal('hide');
  }

  show() {
    this.dom.modal('show');
  }

  free() {
    this.hide();

    setTimeout(() => {
      super.free();
    }, 3000);

    return this;
  }

  createDom() {
    const dom = jQuery('<div class="modal fade in ux-window" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">' +
      '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
        '<h5 class="modal-title ux-window-title"></h4></div>' +
      '<div class="modal-body"></div>' +
      '</div></div></div>');

    return dom;
  }
}

export default Window;
