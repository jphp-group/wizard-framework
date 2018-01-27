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
    const dom = jQuery('<a class="ux-labeled ux-hyperlink" href><span class="ux-labeled-text"></span></a>');
    dom.on('click.Hyperlink', (e) => {
      if (this.href === '#') {
        e.preventDefault();
        return false;
      }

      return true;
    });

    return dom;
  }
}

export default Hyperlink;