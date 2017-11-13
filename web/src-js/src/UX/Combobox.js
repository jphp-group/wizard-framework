import SelectControl from './SelectControl';

class Combobox extends SelectControl {
  createDom() {
    var dom = super.createDom();
    dom.addClass('ux-combobox');
    return dom;
  }
}

export default Combobox;
