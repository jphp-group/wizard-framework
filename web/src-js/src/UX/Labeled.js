import Node from './Node';
import Font from './paint/Font';
import ImageView from './ImageView';
import Utils from './util/Utils';

class Labeled extends Node {
    constructor(text, graphic) {
        super();
        this.textType = 'text';
        this.contentDisplay = 'left';
        this.graphicTextGap = 4;
        this.graphic = graphic;
        this.text = text;
        this.align = ['center', 'center'];
    }

    get font() {
      return Font.getFromDom(this.dom);
    }

    set font(value) {
      Font.applyToDom(this.dom, value);
      this.__triggerPropertyChange('value', value);
    }

    get align() {
      return [this.verAlign, this.horAlign];
    }

    set align(value) {
      if (value instanceof Array && value.length >= 2) {
        this.horAlign = value[1];
        this.verAlign = value[0];
      }
    }

    get horAlign() {
      if (this.dom.hasClass('ux-m-halign-right')) {
        return 'right';
      } else if (this.hasClass('ux-m-halign-center')) {
        return 'center';
      }

      return 'left';
    }

    set horAlign(value) {
      this.dom.removeClass('ux-m-halign-left');
      this.dom.removeClass('ux-m-halign-right');
      this.dom.removeClass('ux-m-halign-center');

      this.dom.addClass('ux-m-halign-' + value);

      this.__triggerPropertyChange('horAlign', value);
    }

    get verAlign() {
      if (this.dom.hasClass('ux-m-valign-bottom')) {
        return 'bottom';
      } else if (this.hasClass('ux-m-valign-center')) {
        return 'center';
      }

      return 'top';
    }

    set verAlign(value) {
      this.dom.removeClass('ux-m-valign-top');
      this.dom.removeClass('ux-m-valign-bottom');
      this.dom.removeClass('ux-m-valign-center');

      this.dom.addClass('ux-m-valign-' + value);

      this.__triggerPropertyChange('verAlign', value);
    }

    get text() {
      switch (this.textType) {
        case 'text':
          return this.dom.find('> span.ux-labeled-text').text();
        case 'html':
          return this.dom.find('> span.ux-labeled-text').html();
      }

      return '';
    }

    set text(value) {
      switch (this.textType) {
        case 'text':
          this.dom.find('> span.ux-labeled-text').text(value);
          break;

        case 'html':
          this.dom.find('> span.ux-labeled-text').html(value);
          break;
      }

      this.__triggerPropertyChange('text', value);
    }

    get textColor() {
      return this.dom.css('color');
    }

    set textColor(value) {
      this.dom.css('color', value ? value : '');

      this.__triggerPropertyChange('textColor', value);
    }

    get textType() {
      return this._textType;
    }

    set textType(value) {
      const text = this.text;
      const graphic = this.graphic;

      if (value) {
        this._textType = value.toString().toLowerCase();
      } else {
        this._textType = 'text';
      }

      this.text = text;
      this.graphic = graphic;
      this.__triggerPropertyChange('textType', value);
    }

    get contentDisplay() {
      if (this.dom.first().hasClass('ux-graphic')) {
        if (this.dom.hasClass('ux-labeled-vertical')) {
          return 'top';
        } else {
          return 'left';
        }
      } else if (this.dom.last().hasClass('ux-graphic')) {
        if (this.dom.hasClass('ux-labeled-vertical')) {
          return 'bottom';
        } else {
          return 'right';
        }
      } else {
        return this._contentDisplay;
      }
    }

    set contentDisplay(value) {
      const graphic = this.graphic;
      const graphicGap = this.graphicTextGap;
      this._contentDisplay = value;

      switch (value) {
        case 'top':
        case 'bottom':
          this.dom.addClass('ux-labeled-vertical');
          break;

        case 'right':
          this.dom.removeClass('ux-labeled-vertical');
          break;

        case 'left':
        default:
          this.dom.removeClass('ux-labeled-vertical');
          this._contentDisplay = 'left';
          break;
      }

      this.__triggerPropertyChange('contentDisplay', value);
      this.graphic = graphic;
      this.graphicTextGap = graphicGap;
    }

    get graphicTextGap() {
      const grDom = this.dom.find('.ux-graphic');

      if (grDom.length) {
        let prop = 'margin-right';

        switch (this.contentDisplay) {
          case 'bottom': prop = 'margin-top'; break;
          case 'right': prop = 'margin-left'; break;
          case 'top': prop = 'margin-bottom'; break;
        }

        return Utils.toPt(grDom.css(prop));
      } else {
        return this._graphicGap;
      }
    }

    set graphicTextGap(value) {
      this._graphicGap = value;

      const grDom = this.dom.find('.ux-graphic');

      if (grDom.length) {
        grDom.css('margin', 0);

        let prop = 'margin-right';

        switch (this.contentDisplay) {
          case 'bottom': prop = 'margin-top'; break;
          case 'right': prop = 'margin-left'; break;
          case 'top': prop = 'margin-bottom'; break;
        }

        grDom.css(prop, value + 'px');
      }

      this.__triggerPropertyChange('contentDisplay', value);
    }

    get graphic() {
      return Node.getFromDom(this.dom.find('.ux-graphic > *').first());
    }

    set graphic(node) {
      const graphicGap = this.graphicTextGap;
      this.dom.find('.ux-graphic').remove();

      if (node) {
        if (typeof node === 'string' || node instanceof String) {
          node = new ImageView(node);
        }

        const dom = jQuery('<span class="ux-graphic" />').append(node.dom);

        switch (this.contentDisplay) {
          case 'top':
          case 'left':
            this.dom.prepend(dom);
            break;
          case 'bottom':
          case 'right':
            this.dom.append(dom);
            break;
        }

        this.graphicTextGap = graphicGap;
      }

      this.__triggerPropertyChange('graphic', node);
    }
}

export default Labeled;
