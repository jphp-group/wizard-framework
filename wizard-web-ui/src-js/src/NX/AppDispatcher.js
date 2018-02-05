
class AppDispatcher {

  onOpen(callback) {
    throw "onOpen() is not implemented";
  }

  onMessage(callback) {
    throw "onMessage() is not implemented";
  }

  onError(callback) {
    throw "onError() is not implemented";
  }

  onClose(callback) {
    throw "onClose() is not implemented";
  }

  /**
   * @param {string} data
   */
  send(data) {
    throw "send() is not implemented";
  }
}

export default AppDispatcher;