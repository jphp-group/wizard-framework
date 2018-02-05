import AppDispatcher from "./AppDispatcher";

class ChromiumEmbeddedAppDispatcher extends AppDispatcher
{
  constructor() {
    super();

    this.onOpen(() => {});
    this.onMessage(() => {});
    this.onError(() => {});
    this.onClose(() => {});
  }

  onOpen(callback) {
    window.cefOpenHandler = () => {
      callback();
    };
  }

  onMessage(callback) {
    window.cefMessageHandler = (data) => callback(data);
  }

  onError(callback) {
    window.cefErrorHandler = () => callback();
  }

  onClose(callback) {
    window.cefCloseHandler = () => callback();
  }

  send(data) {
    window.cefQuery({
      requiest: 'send',
      persistent: false,
      onSuccess: (res) => {
      },
      onFailure: (error_code, error_message) => {
        if (window.cefErrorHandler && error_code !== -1) {
          window.cefErrorHandler.call();
        }
      }
    });
  }
}

export default ChromiumEmbeddedAppDispatcher;