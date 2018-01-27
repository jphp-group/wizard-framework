class Color {
  constructor(value) {
    this._value = value;
  }

  get webValue() {
    return this._value ? this._value : '';
  }

  static of(value) {
    if (!value) {
      return null;
    }

    return new Color(value);
  }
}

export default Color;
