import Node from './Node';
import uiMediator from '../NX/UIMediator';

export default class SelectControl extends Node {
  constructor(items) {
    super();

    if (items) {
      this.items = items;
    }

    this.dom.on('change.SelectControl', (e) => {
      uiMediator.sendUserInput(this, {selected: this.selected, selectedText: this.selectedText});
    });
  }

  get items() {
    const result = {};

    this.dom.find('option').each(function () {
      result[this.attr('value')] = $(this).text();
    });

    return result;
  }

  set items(value) {
    this.dom.find('option').remove();

    for (const key in value) {
      if (value.hasOwnProperty(key)) {
        this.dom.append(jQuery(`<option value='${key}'>${value[key]}</option>`));
      }
    }
  }

  get selected() {
    return this.dom.val();
  }

  set selected(value) {
    this.dom.val(value);
  }

  get selectedText() {
    return this.dom.find('option:selected').text();
  }

  set selectedText(value) {
    this.selected = null;

    this.dom.find('option').each(function () {
      if (jQuery(this).text() === value) {
        jQuery(this).prop('selected', true);
        return false;
      }
    });
  }

  createDom() {
    const dom = jQuery('<select class="form-control ux-select-control">');
    return dom;
  }
}
