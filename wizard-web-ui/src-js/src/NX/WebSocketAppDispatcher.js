
import AppDispatcher from "./AppDispatcher";

/**
 * WebSocket App Dispatcher
 */
class WebSocketAppDispatcher extends AppDispatcher
{
  constructor(wsUrl) {
    super();

    const loc = window.location;

    this.wsUrl = wsUrl;
    let newUri = '';

    if (loc.protocol === "https:") {
      newUri = "wss:";
    } else {
      newUri = "ws:";
    }

    newUri += "//" + loc.host;
    newUri += wsUrl;

    this.ws = new WebSocket(newUri);
  }

  onOpen(callback) {
    this.ws.onopen = () => callback();
  }

  onMessage(callback) {
    this.ws.onmessage = (e) => callback(e.data);
  }

  onError(callback) {
    this.ws.onerror = () => callback();
  }

  onClose(callback) {
    this.ws.onclose = () => callback();
  }

  send(data) {
    return this.ws.send(data);
  }
}

export default WebSocketAppDispatcher;