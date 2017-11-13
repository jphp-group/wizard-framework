import Node from './Node';

class ImageView extends Node {

  constructor(image) {
    super();

    this.proportional = true;
    this.displayType = 'origin';

    if (image !== undefined) {
      this.source = image;
    }
  }

  get source() {
    var source = this.dom.css('background-image');

    if (source) {
      source = /^url\((['"]?)(.*)\1\)$/.exec(source);
      return source ? source[2] : null;
    }

    return null;
  }

  set source(value) {
    this.dom.css({'background-image': `url('${value}')`});

    if (this.displayType == 'origin') {
      this.dom.find('img').attr('src', value);
    }
  }

  get centered() {
    return this.dom.css('background-position') === '50% 50%';
  }

  set centered(value) {
    this.dom.css('background-position', value ? '50% 50%' : '0 0');
  }

  get displayType() {
    switch (this.dom.css('background-size')) {
      case '100% 100%': return 'filled';
      case 'cover': return 'cropped';
      case 'resized': return 'resized';

      case 'auto':
      case 'auto auto': return 'origin';

      default:
        return '';
    }
  }

  set displayType(type) {
    this.dom.find('img').remove();

    switch (type.toString().toLowerCase()) {
      case 'filled':
        this.dom.css('background-size', `100% 100%`);
        break;
      case 'cropped':
        this.dom.css('background-size', 'cover');
        break;
      case 'resized':
        this.dom.css('background-size', 'contain');
        break;
      case 'origin':
        var source = this.source;
        this.dom.css('background-size', 'auto auto');
        this.dom.append(jQuery('<img style="visibility: hidden" />'));
        this.source = source;
        break;
    }
  }

  createDom() {
    var dom = jQuery('<div></div>');
    dom.addClass('ux-image-view');

    dom.css({
      display: 'inline-block',
      backgroundRepeat: 'no-repeat',
      backgroundSize: '100% 100%',
      backgroundPosition: '0 0'
    });
    return dom;
  }
}

export default ImageView;
