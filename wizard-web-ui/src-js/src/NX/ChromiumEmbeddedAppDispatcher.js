import AppDispatcher from "./AppDispatcher";

class ChromiumEmbeddedAppDispatcher extends AppDispatcher
{
  constructor(wsUrl) {
    super();

    this.wsUrl = wsUrl;

    this.onMessage(() => {});
    this.onError(() => {});
    this.onClose(() => {});
  }

  onOpen(callback) {
    window.cefOpenHandler = () => {
      callback();
    };

    setTimeout(() => window.cefOpenHandler(), 1);
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
      request: 'ws:' + this.wsUrl + ':' + data,
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