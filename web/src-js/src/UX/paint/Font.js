import Utils from './../util/Utils';

class Font {
  constructor(name, size) {
    this._dom = null;
    this.name = name || 'serif';
    this.size = size || 12;
  }

  get name() {
    return this._name;
  }

  set name(value) {
    this._name = value;

    if (this._dom) {
      this._dom.css('font-family', value);
    }
  }

  get size() {
    return this._size;
  }

  set size(value) {
    this._size = value | 0;

    if (this._dom) {
      this._dom.css('font-size', value + 'px');
    }
  }

  get bold() {
    return this._bold | false;
  }

  set bold(value) {
    this._bold = value | false;

    if (this._dom) {
      this._dom.css('font-weight', this._bold ? 'bold' : 'normal');
    }
  }

  get italic() {
    return this._italic | false;
  }

  set italic(value) {
    this._italic = value | false;

    if (this._dom) {
      this._dom.css('font-style', this._italic ? 'italic' : 'normal');
    }
  }

  get underline() {
    return this._underline | false;
  }

  set underline(value) {
    this._underline = value | false;

    if (this._dom) {
      this._dom.css('text-decoration', this._underline ? 'underline' : 'none');
    }
  }

  static applyToDom(dom, font) {
    if (font instanceof Font) {
      dom.css('font-family', font.name);
      dom.css('font-size', font.size);

      if (font.bold) {
        dom.css('font-weight', 'bold');
      }

      if (font.italic) {
        dom.css('font-style', 'italic');
      }

      if (font.underline) {
        dom.css('text-decoration', 'underline');
      }
    } else if (typeof font === 'object') {
      if (font['family']) {
        dom.css('font-family', font.family);
      }

      if (font['size']) {
        dom.css('font-size', font.size);
      }

      if (font['bold']) {
        dom.css('font-weight', 'bold');
      }

      if (font['italic']) {
        dom.css('font-style', 'italic');
      }

      if (font['underline']) {
        dom.css('text-decoration', 'underline');
      }
    }
  }

  static getFromDom(dom) {
    if (dom instanceof jQuery) {
      const family = dom.css('font-family');
      const size = Utils.toPt(dom.css('font-size'));

      const bold = dom.css('font-weight') === 'bold';
      const italic = dom.css('font-style') === 'italic';

      const font = new Font(family, size);

      if (bold) font.bold = true;
      if (italic) font.italic = true;

      font._dom = dom;
      return font;
    }

    throw new TypeError('getFromDom(): 1 argument must be jquery object');
  }
}

export default Font;
