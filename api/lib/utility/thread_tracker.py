import threading
import time


class ThreadTracker:
    _active_threads = []
    _original_thread_init = threading.Thread.__init__

    @staticmethod
    def apply_patch():
        def new_thread_init(self, *args, **kwargs):
            ThreadTracker._original_thread_init(self, *args, **kwargs)
            thread_name = kwargs['target'].__name__
            ThreadTracker._active_threads.append((self, thread_name))
        threading.Thread.__init__ = new_thread_init

    @staticmethod
    def thread_started(thread):
        if thread in ThreadTracker._active_threads:
            ThreadTracker._active_threads.remove(thread)

    @staticmethod
    def get_active_threads():
        return [thread for thread in ThreadTracker._active_threads if thread.is_alive()]

    @staticmethod
    def remove_patch():
        threading.Thread.__init__ = ThreadTracker._original_thread_init

    @staticmethod
    def show_info():
        list_info = []
        for i, x in ThreadTracker._active_threads:
            list_info.append((i.is_alive(), x))
        return list_info

# Override `start` method to track when a thread starts
original_start = threading.Thread.start


def new_start(self):
    ThreadTracker.thread_started(self)
    original_start(self)


threading.Thread.start = new_start


if __name__ == "__main__":
    ThreadTracker.apply_patch()

    def sample_function():
        while True:
            print("hello")
            time.sleep(1)

    t1 = threading.Thread(target=sample_function)
    t2 = threading.Thread(target=sample_function)
    t1.start()
    t2.start()
    print(ThreadTracker.show_info())
    t1.join()
    t2.join()
    ThreadTracker.remove_patch()
