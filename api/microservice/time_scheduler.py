import schedule
import time
import config
import threading


class TimeScheduler:
    def __init__(self, waktu=config.TIME_DELAY):
        self.waktu = waktu
        self.play = False
        self.thread_scheduler = None
        self.first = True

    def worker(self):
        print("testes", self.waktu)

    def work_schedule(self):
        if config.TIME_TYPE == "days" or config.TIME_TYPE == "day":
            schedule.every(self.waktu).days.do(self.worker)
        elif config.TIME_TYPE == "hours" or config.TIME_TYPE == "hour":
            schedule.every(self.waktu).hours.do(self.worker)
        elif config.TIME_TYPE == "minutes" or config.TIME_TYPE == "minute":
            schedule.every(self.waktu).minutes.do(self.worker)
        elif config.TIME_TYPE == "seconds" or config.TIME_TYPE == "second":
            schedule.every(self.waktu).seconds.do(self.worker)
        else:
            schedule.every(self.waktu).minutes.do(self.worker)
        while self.play:
            if self.first:
                print("testes", self.waktu)
                self.first = False
            schedule.run_pending()
            time.sleep(1)

    def runner(self):
        if not self.play:
            self.play = True
            self.thread_scheduler = threading.Thread(target=self.work_schedule, daemon=True)
            self.thread_scheduler.start()

    def stop(self):
        if self.play:
            self.play = False
            if self.thread_scheduler is not None:
                self.thread_scheduler.join()
            self.first = True


