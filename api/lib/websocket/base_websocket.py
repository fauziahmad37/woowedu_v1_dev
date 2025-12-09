import asyncio
import inspect
import threading
import websockets

import config
from lib.log_format import log_debug_format

class BaseWebsocket:
    def __init__(self, host=config.WS_HOST, port=config.WS_PORT):
        self.host = host
        self.port = port
        self.lock = threading.Lock()
        self.connected = False
        self.thread = threading.Thread(target=self._run_loop_in_thread, daemon=True)
        self.start_thread()

    async def websocket_handler(self, websocket, path):
        pass

    async def _websocket_main(self):
        async with websockets.serve(self.websocket_handler, self.host, self.port):
            log_debug_format("lib", "WEBSOCKET", inspect.currentframe().f_code.co_name,
                             f"WebSocket Server running at ws://{self.host}:{self.port}")
            self.connected = True
            await asyncio.Future()  # Keep running forever

    def _run_loop_in_thread(self):
        asyncio.run(self._websocket_main())

    def start_thread(self):
        self.thread.start()