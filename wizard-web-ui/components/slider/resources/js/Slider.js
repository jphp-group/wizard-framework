class bundle_slider_Slider extends UX.Node {

  constructor() {
    super();

    this._isReady = false;
    this._step = 1;
    this._value = 0;
    this._min = 0;
    this._max = 100;
    this._tooltipVisibility = 'show';

    this.dom.on('change.bundle_slider_Slider', (e) => {
      this._value = this.dom.slider('getValue');

      NX.AppMediator.sendUserInput(this, () => {
        return {value: this.value}
      });
    });
  }

  get value() {
    return this._value;
  }

  set value(value) {
    this._value = value;

    if (this.dom.data('--slider-created')) {
      this.dom.slider('setValue', value);
    }
  }

  get step() {
    return this._step;
  }

  set step(value) {
    this._step = value;
    this.__setAttribute('step', value);
  }

  get min() {
    return this._min;
  }

  set min(value) {
    this._min = value;
    this.__setAttribute('min', value);
  }

  get max() {
    return this._max;
  }

  set max(value) {
    this._max = value;
    this.__setAttribute('max', value);
  }

  get tooltipVisibility() {
    return this._tooltipVisibility || 'show';
  }

  set tooltipVisibility(value) {
    this._tooltipVisibility = value;
    this.__setAttribute('tooltip', value);
  }

  __setAttribute(name, value) {
    if (this.dom.data('--slider-created')) {
      this.dom.slider('setAttribute', name, value);
    }
  }

  createDom() {
    const dom = jQuery(
      '<input type="text" class="ux-slider" style="visibility: hidden;" />'
    );

    dom.ready(() => {
      dom.slider({
        step: this.step,
        min: this.min,
        max: this.max,
        value: this.value,
        tooltip: this.tooltipVisibility
      });

      dom.data('--slider-created', true);
    });
    return dom;
  }
}

window['bundle.Slider'] = bundle_slider_Slider;