import TextInputControl from './TextInputControl';

class PasswordField extends TextInputControl {
  createDom() {
    var dom = jQuery('<input type="password" class="form-control ux-text-input-control ux-password-field" />');
    return dom;
  }
}

export default PasswordField;
