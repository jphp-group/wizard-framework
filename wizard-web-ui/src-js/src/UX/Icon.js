import Node from './Node';
import Utils from "./util/Utils";

class Icon extends Node {
  get kind() {
    this.dom.text();
  }

  set kind(value) {
    this.dom.text(value);
  }

  get color() {
    return this.dom.css('color') || 'black';
  }

  set color(value) {
    this.dom.css('color', value);
  }

  get imageSize() {
    return Utils.toPt(this.dom.css('font-size'));
  }

  set imageSize(value) {
    this.dom.css('font-size', value);
  }

  createDom() {
    return jQuery('<i class="material-icons ux-icon"></i>');
  }
}

export default Icon;