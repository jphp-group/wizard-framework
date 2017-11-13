import Node from './Node';
import Fragment from './../NX/Fragment';

class FragmentPane extends Node {
  constructor() {
    super();
  }

  get content() {
    this._content;
  }

  set content(fragment) {
    if (fragment instanceof Fragment) {
      this._content = fragment;
    } else if (typeof fragment === 'string' || fragment instanceof String) {
      this._content = new window[fragment]();
    }

    if (this._content) {
      this._content.render(this.dom);
    }
  }

  createDom() {
    return jQuery('<div class="ux-fragment-pane">');
  }
}

export default FragmentPane;
