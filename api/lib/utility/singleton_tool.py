def singleton(cls):
    """Singleton pattern to avoid loading class multiple times
    """
    instances = {}

    def getinstance():
        if cls not in instances:
            instances[cls] = cls()
        return instances[cls]

    return getinstance
