import Button from './Button';
import uiMediator from '../NX/UIMediator';

class ToggleButton extends Button {
  constructor(text, graphic) {
    super(text, graphic);

    this.dom.on('click.ToggleButton', () => {
      uiMediator.sendUserInput(this, () => {
        return {'selected': this.selected}
      });
    });
  }

  createDom() {
    const dom = super.createDom();
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
}

export default ToggleButton;
