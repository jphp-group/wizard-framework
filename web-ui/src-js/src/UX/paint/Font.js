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

    if (this._underline) {
      this._linethrough = false;
    }

    if (this._dom) {
      this._dom.css('text-decoration', this._underline ? 'underline' : 'none');
    }
  }

  get linethrough() {
    return this._linethrough | false;
  }

  set linethrough(value) {
    this._linethrough = value | false;

    if (this._linethrough) {
      this._underline = false;
    }

    if (this._dom) {
      this.dom.css('text-decoration', this._linethrough ? 'line-through' : 'none')
    }
  }

  static applyToDom(dom, font) {
    if (font instanceof Font) {
      dom.css('font-family', font.name);

      if (font.size) {
        dom.css('font-size', font.size);
      } else {
        dom.css('font-size', '');
      }

      if (font.bold) {
        dom.css('font-weight', 'bold');
      } else {
        dom.css('font-weight', '');
      }

      if (font.italic) {
        dom.css('font-style', 'italic');
      } else {
        dom.css('font-style', '');
      }

      if (font.underline) {
        dom.css('text-decoration', 'underline');
      } else {
        dom.css('text-decoration', '');
      }

      if (font.linethrough) {
        dom.css('text-decoration', 'line-through')
      } else {
        dom.css('text-decoration', '');
      }
    } else if (typeof font === 'object') {
      if (font.hasOwnProperty('name')) {
        if (font.name) {
          dom.css('font-family', font.name);
        } else {
          dom.css('font-family', '');
        }
      }

      if (font.hasOwnProperty('size')) {
        if (font.size) {
          dom.css('font-size', font.size);
        } else {
          dom.css('font-size', '');
        }
      }

      if (font.hasOwnProperty('bold')) {
        if (font.bold) {
          dom.css('font-weight', 'bold');
        } else {
          dom.css('font-weight', '');
        }
      }

      if (font.hasOwnProperty('italic')) {
        if (font.italic) {
          dom.css('font-style', 'italic');
        } else {
          dom.css('font-style', '');
        }
      }

      if (font.hasOwnProperty('underline')) {
        if (font.underline) {
          dom.css('text-decoration', 'underline');
        } else {
          dom.css('text-decoration', '');
        }
      }

      if (font.hasOwnProperty('linethrough')) {
        if (font.linethrough) {
          dom.css('text-decoration', 'line-through');
        } else {
          dom.css('text-decoration', '');
        }
      }
    }
  }

  static getFromDom(dom) {
    if (dom instanceof jQuery) {
      const family = dom.css('font-family');
      const size = Utils.toPt(dom.css('font-size'));

      const bold = dom.css('font-weight') === 'bold';
      const italic = dom.css('font-style') === 'italic';
      const linethrough = dom.css('text-decoration') === 'line-through';
      const underline = dom.css('text-decoration') === 'underline';

      const font = new Font(family, size);

      if (bold) font.bold = true;
      if (italic) font.italic = true;
      if (underline) font.underline = true;
      if (linethrough) font.linethrough = true;

      font._dom = dom;
      return font;
    }

    throw new TypeError('getFromDom(): 1 argument must be jquery object');
  }
}

export default Font;
