class App {
  constructor() {
    this._callbacks = {};
  }

  /**
   * @param {string} message
   */
  log(message) {
    console.log(message);
  }

  launch() {
    this.socket = new WebSocket("/dnext/ws/");

    this.socket.onmessage = function (event) {
      const data = JSON.parse(event.data);

      if (data.id && data.message) {
        const callback = this._callbacks[data.id];

        if (callback) {
          callback(data);
        }
      }
    }
  }

  send(message, data, callback) {
    data.message = message;
    data.id = Math.random().toString(36).substring(7);

    if (callback) {
      this._callbacks[data.id] = callback;
    }

    this.socket.send(JSON.stringify(data));
  }
}

export default App;
