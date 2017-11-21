import Labeled from './Labeled';


class Label extends Labeled {

  createDom() {
      const dom = jQuery('<span class="ux-labeled ux-label"><span class="ux-labeled-text"></span></span>');
      return dom;
  }
}

export default Label;
