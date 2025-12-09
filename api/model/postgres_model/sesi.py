import inspect

import config
from lib.db.postgres_function import PostgreSQLConnector
from lib.log_format import log_error_format, log_debug_format, log_warning_format


class SesiModel:
    def __init__(self):
        self.pointer = PostgreSQLConnector(config.DB_HOST_POSTGRESQL,
                                           config.DB_USER_POSTGRESQL,
                                           config.DB_PASSWORD_POSTGRESQL,
                                           config.DB_DATABASE_POSTGRESQL)
        self.table = "sesi"

    def get_upcoming_class(self, minutes):
        result = []

        query_select = f"""
            SELECT 
                s.sesi_id, 
                s.sesi_title, 
                s.sesi_date, 
                s.sesi_jam_start, 
                s.sesi_jam_end, 
                s.teacher_id, 
                st.nis,
                sj.subject_name,
                u.token_device,
                (s.sesi_date + s.sesi_jam_start::time - INTERVAL '{minutes} minutes') AS reminder_time,
                u.userid
            FROM 
                sesi s
            join 
                student st on st.class_id = s.class_id
            join
                subject sj on sj.subject_id = s.subject_id
            join
                users u on u.username = st.nis
            WHERE 
                CURRENT_TIMESTAMP BETWEEN 
                    (s.sesi_date + s.sesi_jam_start::time - INTERVAL '{minutes} minutes') AND 
                    (s.sesi_date + s.sesi_jam_start::time);
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "sesi_id": row[0],
                    "sesi_title": row[1],
                    "sesi_date": row[2],
                    "sesi_jam_start": row[3],
                    "sesi_jam_end":row[4],
                    "teacher_id": row[5],
                    "nis": row[6],
                    "subject_name": row[7],
                    "token_device": row[8],
                    "reminder_time": row[9],
                    "userid": row[10],
                })

            log_debug_format("model", "NOTIFICATION_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_total_teaching_today_class(self):
        result = []

        query_select = """
            SELECT 
                u.userid, 
                t.nik, 
                s.sesi_date, 
                COUNT(*) AS jumlah_kelas, 
                u.token_device
            FROM 
                teacher t 
            JOIN 
                sesi s ON t.teacher_id = s.teacher_id
            JOIN 
                users u ON u.username = t.nik
            WHERE 
                s.sesi_date = CURRENT_DATE 
            GROUP BY 
                u.userid, s.sesi_date, t.nik, u.token_device;
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "teacher_id": row[0],
                    "nik": row[1],
                    "sesi_date": row[2],
                    "jumlah_kelas": row[3],
                    "token_device": row[4]
                })

            log_debug_format("model", "NOTIFICATION_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_total_task_due_in_n_day_before(self, days):
        result = []

        query_select = f"""
            SELECT 
                u.userid,
                t.task_id,
                sj.subject_name,
                t.title AS task_title,
                t.due_date,
                st.nis,
                u.token_device
            FROM 
                task t
            JOIN 
                subject sj ON sj.subject_id = t.subject_id
            JOIN 
                student st ON st.class_id = t.class_id
            JOIN 
                users u ON u.username = st.nis
            WHERE 
                -- D-1 jam akurat
                t.due_date::timestamp - INTERVAL '1 day' <= CURRENT_TIMESTAMP 
                AND 
                t.due_date::timestamp - INTERVAL '1 day' >= CURRENT_TIMESTAMP - INTERVAL '5 minute';
        """
        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "userid": row[0],
                    "task_id": row[1],
                    "subject_name": row[2],
                    "task_title": row[3],
                    "due_date": row[4],
                    "nis": row[5],
                    "token_device": row[6]
                })

            log_debug_format("model", "NOTIFICATION_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_total_student_not_submit_the_task(self):
        result = []

        query_select = """
            SELECT 
                u.userid AS user_id,
                u.username,
                sj.subject_name,
                t.title,
                COUNT(s.student_id) AS jumlah_belum_kumpul,
                u.token_device,
                t.task_id
            FROM 
                task t
            JOIN 
                subject sj ON sj.subject_id = t.subject_id
            JOIN 
                student s ON s.class_id = t.class_id
            LEFT JOIN 
                task_student ts ON ts.task_id = t.task_id AND ts.student_id = s.student_id
            JOIN 
                teacher tchr ON tchr.teacher_id = t.teacher_id
            JOIN 
                users u ON u.username = tchr.nik
            WHERE 
                -- Bandingkan dengan NOW + INTERVAL '1 day' hanya bagian jam dan menit yang sama
                t.due_date::timestamp - INTERVAL '1 day' <= CURRENT_TIMESTAMP 
                AND 
                t.due_date::timestamp - INTERVAL '1 day' >= CURRENT_TIMESTAMP - INTERVAL '5 minute'
                AND ts.task_submit IS NULL
            GROUP BY 
                u.userid,
                u.username,
                u.token_device,
                sj.subject_name,
                t.title,
                t.task_id,
                t.due_date;
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "userid": row[0],
                    "nis": row[1],
                    "subject_name": row[2],
                    "title": row[3],
                    "total_not_submit": row[4],
                    "token_device": row[5],
                    "task_id": row[6]
                })

            log_debug_format("model", "NOTIFICATION_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_task_not_submited_to_parent(self):
        result = []

        query_select = """
            SELECT 
                u.userid AS parent_userid,
                t.task_id,
                sj.subject_name,
                t.title,
                u.token_device
            FROM 
                task t
            JOIN 
                subject sj ON sj.subject_id = t.subject_id
            JOIN 
                student st ON st.class_id = t.class_id
            LEFT JOIN 
                task_student ts ON ts.task_id = t.task_id AND ts.student_id = st.student_id
            JOIN 
                parent p ON st.parent_id = p.parent_id
            JOIN 
                users u ON u.username = p.username
            WHERE 
                -- Due date dikurangi 1 hari harus sama dengan jam sekarang (akurasi menit)
                t.due_date::timestamp - INTERVAL '1 day' <= CURRENT_TIMESTAMP 
                AND 
                t.due_date::timestamp - INTERVAL '1 day' >= CURRENT_TIMESTAMP - INTERVAL '5 minute'
                AND ts.ts_id IS NULL;
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "userid": row[0],
                    "task_id": row[1],
                    "subject_name": row[2],
                    "title": row[3],
                    "token_device": row[4]
                })

            log_debug_format("model", "NOTIFICATION_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_total_score_correction_not_complete(self):
        result = []

        query_select = """
            SELECT 
                u.userid,
                u.username AS guru_username,
                u.token_device AS teacher_token_device,
                COUNT(ts.ts_id) AS jumlah_belum_dikoreksi,
                t.task_id
            FROM task_student ts
            left JOIN task t ON t.task_id = ts.task_id
            left JOIN teacher th ON th.teacher_id = t.teacher_id
            left JOIN users u ON u.username = th.nik
            where ts.task_nilai is null AND t.due_date::date = CURRENT_DATE - INTERVAL '2 days' and DATE_PART('week', t.due_date) = DATE_PART('week', CURRENT_DATE)
            GROUP BY t.teacher_id, u.userid, u.username, u.token_device, t.task_id
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "userid": row[0],
                    "username": row[1],
                    "token_device": row[2],
                    "jumlah_belum_dikoreksi": row[3],
                    "task_id": row[4]
                })

            log_debug_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    @staticmethod
    def laporan_perubahan(class_name, total_minggu_lalu, total_2minggu_lalu):
        total_minggu_lalu = total_minggu_lalu or 0
        total_2minggu_lalu = total_2minggu_lalu or 0

        if total_minggu_lalu == 0 and total_2minggu_lalu == 0:
            return f'Aktivitas siswa {class_name} tidak ada aktivitas dalam 2 minggu terakhir.'
        elif total_2minggu_lalu == 0:
            return f'Aktivitas siswa {class_name} naik 100% minggu lalu.'
        else:
            if total_minggu_lalu > total_2minggu_lalu:
                kenaikan = round(((total_minggu_lalu - total_2minggu_lalu) / total_2minggu_lalu) * 100, 2)
                return f'Aktivitas siswa {class_name} naik {kenaikan}% minggu lalu.'
            elif total_minggu_lalu < total_2minggu_lalu:
                penurunan = round(((total_2minggu_lalu - total_minggu_lalu) / total_2minggu_lalu) * 100, 2)
                return f'Aktivitas siswa {class_name} turun {penurunan}% minggu lalu.'
            else:
                return f'Aktivitas siswa {class_name} stabil minggu lalu.'

    def get_class_activity(self):
        result = []

        query_select = """
            WITH aktivitas_minggu_lalu AS (
                SELECT 
                    st.sekolah_id,
                    st.class_id,
                    COUNT(acl.logtime) AS total_aktifitas_minggu_lalu
                FROM student st
                LEFT JOIN users u ON u.username = st.nis
                LEFT JOIN actionlog acl ON acl.user = u.username
                LEFT JOIN user_level ul ON u.user_level = ul.user_level_id
                WHERE ul.user_level_id = 4 
                  AND acl.logtime::date >= (CURRENT_DATE - INTERVAL '1 week') - INTERVAL '6 days'
                  AND acl.logtime::date <= (CURRENT_DATE - INTERVAL '1 week')
                GROUP BY st.class_id, st.sekolah_id
            ),
            aktivitas_2minggu_lalu AS (
                SELECT 
                    st.sekolah_id,
                    st.class_id,
                    COUNT(acl.logtime) AS total_aktifitas_2minggu_lalu
                FROM student st
                LEFT JOIN users u ON u.username = st.nis
                LEFT JOIN actionlog acl ON acl.user = u.username
                LEFT JOIN user_level ul ON u.user_level = ul.user_level_id
                WHERE ul.user_level_id = 4 
                  AND acl.logtime::date >= (CURRENT_DATE - INTERVAL '2 weeks') - INTERVAL '6 days'
                  AND acl.logtime::date <= (CURRENT_DATE - INTERVAL '2 weeks')
                GROUP BY st.class_id, st.sekolah_id
            ),
            kepsek_user as (
                SELECT DISTINCT ON (u.username) u.userid, u.username, u.token_device, tchr.sekolah_id
                FROM users u
                INNER JOIN actionlog acl ON u.username = acl."user"
                inner join teacher tchr on tchr.nik = u.username
                WHERE u.user_level = 6
                ORDER BY u.username, acl.logtime DESC
            )
            SELECT 
                ku.userid,
                kl.class_name,
                am.sekolah_id,
                ku.username, ku.token_device,
                COALESCE(am.total_aktifitas_minggu_lalu, 0) AS total_aktifitas_minggu_lalu,
                COALESCE(a2.total_aktifitas_2minggu_lalu, 0) AS total_aktifitas_2minggu_lalu
            FROM kelas kl
            JOIN aktivitas_minggu_lalu am ON am.class_id = kl.class_id
            JOIN aktivitas_2minggu_lalu a2 ON a2.class_id = kl.class_id
            JOIN kepsek_user ku on ku.sekolah_id = am.sekolah_id;
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                class_name = row[1]
                total_minggu_lalu = row[5]
                total_2minggu_lalu = row[6]
                result.append({
                    "userid": row[0],
                    "class_name": row[1],
                    "sekolah_id": row[2],
                    "token_device": row[4],
                    "total_activity_week_1": total_minggu_lalu,
                    "total_activity_week_2": total_2minggu_lalu,
                    "note": self.laporan_perubahan(class_name, total_minggu_lalu, total_2minggu_lalu)
                })

            log_debug_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_all_kepsek(self):
        result = []

        query_select = """
            SELECT DISTINCT ON (u.username) u.userid, u.username, u.token_device, tchr.sekolah_id
            FROM users u
            INNER JOIN actionlog acl ON u.username = acl."user"
            inner join teacher tchr on tchr.nik = u.username
            WHERE u.user_level = 6
            ORDER BY u.username, acl.logtime DESC;
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "userid": row[0],
                    "username": row[1],
                    "token_device": row[2]
                })

            log_debug_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_teacher_session_log(self):
        result = []

        query_select = """
                WITH sesi_tanpa_log AS (
                    SELECT 
                        t.sekolah_id,
                        t.teacher_id,
                        t.teacher_name,
                        s.sesi_id,
                        s.sesi_title,
                        s.sesi_date,
                        s.sesi_jam_end
                    FROM sesi s
                    INNER JOIN teacher t ON t.teacher_id = s.teacher_id
                    WHERE 
                        s.sesi_date = CURRENT_DATE
                        AND now() >= (s.sesi_date::timestamp + s.sesi_jam_end::time + INTERVAL '30 minutes')
                        AND NOT EXISTS (
                            SELECT 1 FROM actionlog acl 
                            WHERE 
                                acl."user" = t.nik
                                AND acl.logtime::date = CURRENT_DATE
                        )
                )
                SELECT 
                    u.userid,
                    u.username,
                    u.token_device,
                    st.sekolah_id,
                    COUNT(st.teacher_id) AS jumlah_guru_tidak_log,
                    st.sesi_id
                FROM users u
                INNER JOIN sesi_tanpa_log st ON st.sekolah_id = (
                    SELECT tchr.sekolah_id FROM teacher tchr WHERE tchr.nik = u.username LIMIT 1
                )
                WHERE u.user_level = 6
                GROUP BY u.userid, u.username, u.token_device, st.sekolah_id, st.sesi_id;
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "userid": row[0],
                    "username": row[1],
                    "token_device": row[2],
                    "sekolah_id": row[3],
                    "jumlah_guru_tidak_log": row[4],
                    "sesi_id": row[5]
                })

            log_debug_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_student_not_absence(self):
        result = []

        query_select = """
            SELECT 
                up.userid AS parent_userid,
                up.username AS parent_username,
                up.token_device AS parent_token_device,
                s.student_name ,
                a.absensi_id,
                a.log_time
            FROM 
                users u
            JOIN 
                student s ON s.nis = u.username
            JOIN 
                parent p ON p.parent_id = s.parent_id
            JOIN 
                users up ON up.username = p.username
            LEFT JOIN 
                absensi a 
                ON a.userid = u.userid 
                AND a.log_time::date = CURRENT_DATE  -- cek di ON JOIN, biar tetep null-safe
            
            WHERE 
                u.user_level = 4
                AND a.log_time IS NULL;
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "userid": row[0],
                    "username": row[1],
                    "token_device": row[2],
                    "student_name": row[3],
                    "absensi_id": row[4],
                    "log_time": row[5]
                })

            log_debug_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_exam_student(self, exam_student_id):
        result = []

        query_select = f"""
            SELECT 
                es.es_id,
                e.title,
                u_student.userid,
                'student' AS user_type,
                u_student.users_token AS users_token
            FROM exam_student es
            JOIN student s ON es.student_id = s.student_id
            JOIN parent p ON p.parent_id = s.parent_id
            JOIN users u_student ON u_student.username = s.nis
            join exam e on e.exam_id = es.exam_id
            WHERE es.es_id = {exam_student_id}
            
            UNION ALL
            
            SELECT 
                es.es_id,
                e.title,
                u_parent.userid,
                'parent' AS user_type,
                u_parent.users_token AS users_token
            FROM exam_student es
            JOIN student s ON es.student_id = s.student_id
            JOIN parent p ON p.parent_id = s.parent_id
            JOIN users u_parent ON u_parent.username = p.username
            join exam e on e.exam_id = es.exam_id
            WHERE es.es_id = {exam_student_id};
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "es_id": row[0],
                    "exam_title": row[1],
                    "user_id": row[2],
                    "user_type": row[3],
                    "token_device": row[4]
                })

            log_debug_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result

    def get_teacher_not_attempt_exam_score(self):
        result = []

        query_select = """
            SELECT 
                es.exam_id, 
                e.title, 
                u.userid, 
                u.token_device, 
                es.exam_submit
            FROM exam_student es 
            JOIN exam e ON es.exam_id = e.exam_id
            JOIN teacher t ON t.teacher_id = e.teacher_id
            JOIN users u ON u.username = t.nik
            WHERE 
                (es.exam_total_nilai IS null or es.exam_total_nilai = 0)
                AND es.exam_submit >= CURRENT_DATE - INTERVAL '1 day';
        """

        fetch = self.pointer.fetch_all(query_select)
        if not fetch:
            log_warning_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                               f"DATA SELECT NULL {fetch}")

        if fetch:
            for row in fetch:
                result.append({
                    "exam_id": row[0],
                    "exam_title": row[1],
                    "user_id": row[2],
                    "token_device": row[3],
                    "exam_submit": row[4]
                })

            log_debug_format("model", "SESI_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA SELECT SUCCESSFULLY {result}")
        return result


if __name__ == "__main__":
    # print(SesiModel().get_upcoming_class(15))
    # print(SesiModel().get_total_teaching_today_class())
    # print(SesiModel().get_total_task_due_in_1_day_before(1))
    # print(SesiModel().get_total_student_not_submit_the_task())
    # print(SesiModel().get_task_not_submited_to_parent())
    # print(SesiModel().get_total_score_correction_not_complete())
    print(SesiModel().get_exam_student(17))

    # SEED DATA