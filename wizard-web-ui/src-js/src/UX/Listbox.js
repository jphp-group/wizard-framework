import SelectControl from './SelectControl';

class Listbox extends SelectControl {

  createDom() {
    var dom = super.createDom();
    dom.prop('multiple', true);
    dom.addClass('ux-listbox');
    return dom;
  }
}

export default Listbox;
