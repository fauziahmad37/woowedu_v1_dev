from lib.utility.singleton_tool import singleton

@singleton
class Tokentools:
    def __init__(self):
        self.blacklist = set()
