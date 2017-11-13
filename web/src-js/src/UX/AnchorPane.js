import Container from './Container';

class AnchorPane extends Container {
  createDom() {
    var dom = super.createDom();
    dom.addClass('ux-anchor-pane');
    return dom;
  }

  createSlotDom(object) {
    object.dom.css('position', 'absolute');
    return object.dom;
  }

  childToBack(object) {
    var dom = object.dom;
    dom.detach();

    this.dom.prepend(dom);
  }

  childToFront(object) {
    var dom = object.dom;
    dom.detach();
    this.dom.append(dom);
  }
}

export default AnchorPane;
