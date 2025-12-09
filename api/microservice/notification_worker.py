import schedule
import time
import threading

from config import TIME_DELAY, TIME_TYPE
from lib.FcmMessage import FcmMessage
from lib.log_format import log_info_format
from lib.utility.tools import HashingUtility
from model.postgres_model.notif import NotifModel
from model.postgres_model.sesi import SesiModel


class NotificationScheduler:
    def __init__(self):
        self.running = False
        self.thread = None
        self.set_check_15_mins_before_class = {}
        self.set_check_30_mins_before_class = {}
        self.set_deadline_1_day_before = {}
        self.set_total_student_not_submit_task_before_deadline = {}
        self.set_student_task_not_submited_to_parent = {}
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "NOTIFICATION SCHEDULER", f"STARTING NOTIFICATION WORKER")

    def check_15_mins_before_class(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "check_15_mins_before_class",
                        f"Scheduler checked")
        data = self.get_upcoming_classes(minutes=15)
        for row in data:
            sesi_id = row["sesi_id"]
            subject_name = row["subject_name"]
            sesi_jam_start = row["sesi_jam_start"]

            if (not self.set_check_15_mins_before_class.get(sesi_id) or
                    not self.set_check_15_mins_before_class[sesi_id]["notified"]):

                userid = row["userid"]
                token_device = row["token_device"]
                title = "Kelas Dimulai"
                body = f"Kelas {subject_name} Dimulai pukul {sesi_jam_start}. Jangan sampai terlambat ya!"
                if token_device:
                    FcmMessage().send(token_device, title , body)
                NotifModel().insert_notification("SESI", title, False, userid, f"sesi/lihat_sesi/{sesi_id}", None, None,
                                                 sesi_id, None, body)

                # Tandai sudah dikirim
                self.set_check_15_mins_before_class[sesi_id] = {
                    "data": row,
                    "notified": True
                }

    def notify_at_morning(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "notify_at_morning",
                        f"Scheduler checked")
        data = self.get_teaching_today()
        for row in data:
            userid = row["teacher_id"]
            token_device = row["token_device"]
            jumlah_kelas = row["jumlah_kelas"]
            title = "Jadwal Mengajar Hari Ini"
            body = f"Hari ini Anda mengajar {jumlah_kelas} kelas. Jangan lupa cek materinya, ya!"
            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification("SESI", title, False, userid, f"sesi", None,
                                             None,None, None, body)

    def notify_30_mins_after_class(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "notify_30_mins_after_class",
                        f"Scheduler checked")

        data = SesiModel().get_teacher_session_log()

        for row in data:
            user_id = row["userid"]
            sesi_id = row["sesi_id"]
            if (not self.set_check_30_mins_before_class.get(user_id+sesi_id) or
                    not self.set_check_30_mins_before_class[user_id+sesi_id]["notified"]):
                token_device = row["token_device"]
                jumlah_guru_tidak_log = row["jumlah_guru_tidak_log"]

                title = "Guru Belum Log Aktivitas"
                body = f"{jumlah_guru_tidak_log} guru belum mengisi log sesi hari ini."

                if token_device:
                    FcmMessage().send(token_device, title , body)
                NotifModel().insert_notification("SESI", title, False, user_id, f"#", None, None,
                                                 None, None, body)

                # Tandai sudah dikirim
                self.set_check_30_mins_before_class[user_id+sesi_id] = {
                    "data": row,
                    "notified": True
                }


    def deadline_1_day_before(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "deadline_1_day_before",
                        f"Scheduler checked")
        self.get_tasks_due_in(1)
        self.get_total_student_not_submit_task_before_deadline()
        self.get_student_task_not_submited_to_parent()


    def monday_8am(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "monday_8am",
                        f"Scheduler checked")
        data = SesiModel().get_class_activity()

        for row in data:
            user_id = row["userid"]
            token_device = row["token_device"]
            note = row["note"]

            title = f"Aktivitas Kelas Menurun"
            body = note
            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification("NEWS", title, False, user_id, f"#", None,
                                             None, None, None, body)

    def notify_after_2_days_task_deadline(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "notify_after_2_days_task_deadline",
                        f"Scheduler checked")
        data = SesiModel().get_total_score_correction_not_complete()

        for row in data:
            user_id = row["userid"]
            token_device = row["token_device"]
            jumlah_belum_dikoreksi = row["jumlah_belum_dikoreksi"]
            task_id = row["task_id"]

            if (not self.set_student_task_not_submited_to_parent.get(task_id) or
                    not self.set_student_task_not_submited_to_parent[task_id]["notified"]):

                title = f"Koreksi Belum Lengkap"
                body = f"Masih ada {jumlah_belum_dikoreksi} tugas belum dikoreksi. Segera diselesaikan ya."
                if token_device:
                    FcmMessage().send(token_device, title, body)
                NotifModel().insert_notification("TASK", title, False, user_id, f"task/detail/{task_id}", task_id,
                                                 None, None, None, body)

                # Tandai sudah dikirim
                self.set_student_task_not_submited_to_parent[task_id] = {
                    "data": row,
                    "notified": True
                }

        return data

    def meeting_1_hour_before(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "meeting_1_hour_before",
                        f"Scheduler checked")

    def daily_at_16(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "daily_at_16",
                        f"Scheduler checked")

        self.get_absence_history_today()



    def reset_set_data(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "reset_set_data",
                        f"ALL SETTER DATA RESETED")
        self.set_check_15_mins_before_class = {}
        self.set_deadline_1_day_before = {}
        self.set_student_task_not_submited_to_parent = {}
        self.set_check_30_mins_before_class = {}

    def worker(self):
        self.check_15_mins_before_class()
        self.notify_30_mins_after_class()
        self.deadline_1_day_before()
        # self.meeting_1_hour_before()
        self.notify_after_2_days_task_deadline()

    def every_weekday(self):
        self.get_student_not_absence_today()
        self.get_teacher_not_submit_exam_score()

    def run(self):
        # Schedule absolute time jobs
        schedule.every().day.at("23:59").do(self.reset_set_data)
        schedule.every().day.at("07:00").do(self.notify_at_morning)
        schedule.every().day.at("16:00").do(self.daily_at_16)

        schedule.every().monday.at("12:00").do(self.every_weekday)
        schedule.every().tuesday.at("12:00").do(self.every_weekday)
        schedule.every().wednesday.at("12:00").do(self.every_weekday)
        schedule.every().thursday.at("12:00").do(self.every_weekday)
        schedule.every().friday.at("12:00").do(self.every_weekday)

        schedule.every().monday.at("08:00").do(self.monday_8am)

        # Schedule interval checks (setiap 1 menit)
        if TIME_TYPE == "days" or TIME_TYPE == "day":
            schedule.every(TIME_DELAY).days.do(self.worker)
        elif TIME_TYPE == "hours" or TIME_TYPE == "hour":
            schedule.every(TIME_DELAY).hours.do(self.worker)
        elif TIME_TYPE == "minutes" or TIME_TYPE == "minute":
            schedule.every(TIME_DELAY).minutes.do(self.worker)
        elif TIME_TYPE == "seconds" or TIME_TYPE == "second":
            schedule.every(TIME_DELAY).seconds.do(self.worker)
        else:
            schedule.every(TIME_DELAY).minutes.do(self.worker)

        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "NOTIFICATION SCHEDULER",
                        f"NOTIFICATION WORKER EVERY {TIME_DELAY} {TIME_TYPE}")
        self.running = True
        while self.running:
            schedule.run_pending()
            time.sleep(1)

    def start(self):
        if not self.running:
            self.thread = threading.Thread(target=self.run, daemon=True)
            self.thread.start()

    def stop(self):
        self.running = False
        if self.thread:
            self.thread.join()

    # Mock methods
    def get_upcoming_classes(self, minutes):
        data = SesiModel().get_upcoming_class(minutes)
        return data

    def get_teaching_today(self):
        data = SesiModel().get_total_teaching_today_class()
        return data

    def get_classes_ended_30_mins_ago(self):
        return []

    def get_tasks_due_in(self, days):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "get_tasks_due_in",
                        f"Scheduler checked")
        data = SesiModel().get_total_task_due_in_n_day_before(days)

        for row in data:
            user_id = row["userid"]
            task_id = row["task_id"]
            token_device = row["token_device"]
            task_title = row["task_title"]
            subject_name = row["subject_name"]
            unique_id = HashingUtility().MD5Encode(f"{user_id}{task_id}{task_title}{subject_name}")

            if (not self.set_deadline_1_day_before.get(unique_id) or
                    not self.set_deadline_1_day_before[unique_id]["notified"]):

                title = f"Deadline Tugas {subject_name} Mendekati"
                body = f"Tugas {task_title} tinggal 1 hari lagi. Segera diselesaikan, ya!"
                if token_device:
                    FcmMessage().send(token_device, title, body)
                NotifModel().insert_notification("TASK", title, False, user_id, f"task/detail/{task_id}", task_id,
                                                 None, None, None, body)

                # Tandai sudah dikirim
                self.set_deadline_1_day_before[unique_id] = {
                    "data": row,
                    "notified": True
                }
        return data

    def get_total_student_not_submit_task_before_deadline(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "get_total_student_not_submit_task_before_deadline",
                        f"Scheduler checked")

        data = SesiModel().get_total_student_not_submit_the_task()

        for row in data:
            user_id = row["userid"]
            task_id = row["task_id"]
            token_device = row["token_device"]
            task_title = row["title"]
            subject_name = row["subject_name"]
            total_not_submit = row["total_not_submit"]

            if (not self.set_total_student_not_submit_task_before_deadline.get(task_id) or
                    not self.set_total_student_not_submit_task_before_deadline[task_id]["notified"]):

                title = f"Siswa Belum Kumpulkan"
                body = f"{total_not_submit} siswa belum kumpulkan tugas {subject_name} - {task_title}."
                if token_device:
                    FcmMessage().send(token_device, title, body)
                NotifModel().insert_notification("TASK", title, False, user_id, f"task/detail/{task_id}", task_id,
                                                 None, None, None, body)

                # Tandai sudah dikirim
                self.set_total_student_not_submit_task_before_deadline[task_id] = {
                    "data": row,
                    "notified": True
                }

        return data

    def get_student_task_not_submited_to_parent(self):
        log_info_format("NOTIFICATION WORKER", "MICROSERVICE", "get_task_not_student_submited_to_parent",
                        f"Scheduler checked")

        data = SesiModel().get_task_not_submited_to_parent()

        for row in data:
            user_id = row["userid"]
            task_id = row["task_id"]
            subject_name = row["subject_name"]
            task_title = row["title"]
            token_device = row["token_device"]
            unique_id = HashingUtility().MD5Encode(f"{user_id}{task_id}{task_title}{subject_name}")

            if (not self.set_student_task_not_submited_to_parent.get(unique_id) or
                    not self.set_student_task_not_submited_to_parent[unique_id]["notified"]):

                title = f"Tugas {subject_name} Anak Belum Selesai"
                body = f"Tugas {task_title} anak Anda belum diserahkan. Mohon bantu awasi, ya!"
                if token_device:
                    FcmMessage().send(token_device, title, body)
                NotifModel().insert_notification("TASK", title, False, user_id, f"task/detail/{task_id}", task_id,
                                                 None, None, None, body)

                # Tandai sudah dikirim
                self.set_student_task_not_submited_to_parent[unique_id] = {
                    "data": row,
                    "notified": True
                }

        return data

    def get_absence_history_today(self):
        data = SesiModel().get_all_kepsek()

        for row in data:
            user_id = row["userid"]
            token_device = row["token_device"]

            title = f"Rekap Absensi Hari Ini"
            body = "Rekap kehadiran hari ini sudah tersedia di dashboard."
            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification("NEWS", title, False, user_id, f"#", None,
                                             None, None, None, body)

    def get_student_not_absence_today(self):
        data = SesiModel().get_student_not_absence()

        for row in data:
            user_id = row["userid"]
            token_device = row["token_device"]
            student_name = row["student_name"]

            title = f"Anak Tidak Hadir"
            body = f"{student_name} tidak hadir hari ini. Apakah ada konfirmasi izin?"
            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification("NEWS", title, False, user_id, f"#", None,
                                             None, None, None, body)

    def get_teacher_not_submit_exam_score(self):
        data = SesiModel().get_teacher_not_attempt_exam_score()

        for row in data:
            exam_id = row["exam_id"]
            user_id = row["user_id"]
            token_device = row["token_device"]
            exam_title = row["exam_title"]

            title = f"Anda belum submit score exam"
            body = f"Anda belum submit exam '{exam_title}' segera submit sekarang."
            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification("EXAM", title, False, user_id, f"asesmen", None,
                                             None, None, exam_id, body)


