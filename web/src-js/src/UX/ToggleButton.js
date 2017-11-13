import Button from './Button';

class ToggleButton extends Button {
  createDom() {
    var dom = super.createDom();
    dom.addClass('ux-toggle-button');
    dom.attr('data-toggle', 'button');
    return dom;
  }

  get selected() {
    return this.dom.hasClass('active');
  }

  set selected(value) {
    if (value) {
      this.dom.addClass('active');
      this.dom.attr('aria-pressed', true);
    } else {
      this.dom.removeClass('active');
      this.dom.attr('aria-pressed', false);
    }
  }

  __bindEvents(dom) {
    dom.on('click', () => {
      this.selected = !this.selected;
    });
  }
}

export default ToggleButton;
