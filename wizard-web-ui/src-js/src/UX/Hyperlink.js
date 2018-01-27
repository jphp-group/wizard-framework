import Labeled from "./Labeled";

/**
 *
 */
class Hyperlink extends Labeled {
  get href() {
    return this.dom.attr('href');
  }

  set href(value) {
    this.dom.attr('href', value);
  }

  get target() {
    return this.dom.attr('target') || '_self';
  }

  set target(value) {
    this.dom.attr('target', value);
  }

  createDom() {
    return jQuery('<a class="ux-hyperlink"><span class="ux-labeled-text"></span></a>');
  }
}

export default Hyperlink;