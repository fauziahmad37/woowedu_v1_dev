import threading
from lib.utility.singleton_tool import singleton

"""VARIABLE FOR INTERACT OVER MICROSERVICE"""
@singleton
class GlobalVar:
    def __init__(self):
        self.lock = threading.Lock()
        self.pipe_db = False