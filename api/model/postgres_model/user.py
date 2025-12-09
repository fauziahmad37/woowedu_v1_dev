import inspect

import config
from lib.db.postgres_function import PostgreSQLConnector
from lib.log_format import log_error_format, log_debug_format


class UserModel:
    def __init__(self):
        self.pointer = PostgreSQLConnector(config.DB_HOST_POSTGRESQL,
                                           config.DB_USER_POSTGRESQL,
                                           config.DB_PASSWORD_POSTGRESQL,
                                           config.DB_DATABASE_POSTGRESQL)
        self.table = "users"

    @staticmethod
    def json_user(row):
        result = {
            "userid": row[0],
            "username": row[1],
            "password": row[2],
            "user_level": row[3],
            "last_login": row[4],
            "active": row[5],
            "photo": row[6],
            "sekolah_id": row[7],
            "themes": row[8],
            "users_token": row[9],
            "date_limit": row[10],
            "publisher_id": row[11],
            "token_device": row[12],
            "token_server": row[13]
        }
        return result

    @staticmethod
    def json_student(row):
        result = {
            "student_id": row[0],
            "nis": row[1],
            "student_name": row[2],
            "class_id": row[3],
            "jurusan": row[4],
            "address": row[5],
            "phone": row[6],
            "email": row[7],
            "create_by": row[8],
            "edit_at": row[9],
            "edit_by": row[10],
            "sekolah_id": row[11],
            "gender": row[12],
            "ta_aktif": row[13],
            "parent_id": row[14],
            "user_id": row[15]
        }
        return result

    def add_absent(self, userid, log_time):
        result = {}
        query_insert = """
            INSERT INTO absensi (userid, log_time)
            VALUES (
                %(userid)s,
                %(log_time)s
            )
        """
        param = {
            "userid": userid,
            "log_time": log_time
        }
        try:
            self.pointer.execute_insert(query_insert, param=param)

            result['status'] = 'success'
            result['message'] = 'Absensi berhasil ditambahkan'
        except Exception as e:
            result['status'] = 'error'
            result['message'] = f"Exception: {e}"
        return result

    def get_users(self, username=None, sekolah_id=None):
        result = []

        query_select = """
            select * from users
        """
        if username:
            query_select += f" where username = '{username}'"
            fetch = self.pointer.fetch_one(query_select)
            result = self.json_user(fetch)
        else:
            if sekolah_id:
                query_select += f" where sekolah_id = '{sekolah_id}'"
                fetch = self.pointer.fetch_all(query_select)
                for row in fetch:
                    result.append(self.json_user(row))
            else:
                fetch = self.pointer.fetch_all(query_select)
                for row in fetch:
                    result.append(self.json_user(row))

        return result

    def update_token_device(self, username, token_device):
        result = {}

        query_update = """
            update users set token_device = %s where username = %s
        """

        fetch_update = self.pointer.execute_query(query_update, param=(token_device, username,))

        if not fetch_update:
            log_error_format("model", "USER_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA UPDATE ERROR {fetch_update}")

        if fetch_update:
            result = self.get_users(username=username)
            log_debug_format("model", "USER_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA UPDATE SUCCESSFULLY {result}")
        return result

    def get_notification_class_data(self, class_id=None):
        result = []

        query_select = f"""
           select u.userid, s.class_id, u.token_device from student s 
           LEFT join users u on s.nis = u.username where s.class_id  = '{class_id}'
        """
        fetch = self.pointer.fetch_all(query_select)
        for row in fetch:
            result.append({
                "userid": row[0],
                "class_id": row[1],
                "token_device": row[2]
            })
        return result


    def get_notification_sesi_data(self, sesi_id=None):
        result = []

        query_select = f"""
           select s.sesi_id, u.userid, u.token_device from sesi s 
            left join student st on s.class_id  = st.class_id
            left join users u on st.nis = u.username
            where s.sesi_id = '{sesi_id}'
        """

        fetch = self.pointer.fetch_all(query_select)
        for row in fetch:
            result.append({
                "sesi_id": row[0],
                "userid": row[1],
                "token_device": row[2]
            })
        return result

    def get_notification_login_data(self, username):
        result = {}
        query_select = f"""
            select u.userid, u.username, p.parent_id , u.token_device from student s
            left join parent p on p.parent_id = s.parent_id
            left join users u on u.username = p.username 
            where nis='{username}';
        """

        fetch = self.pointer.fetch_one(query_select)
        if fetch:
            result = {
                "userid": fetch[0],
                "nis": fetch[1],
                "parent_id": fetch[2],
                "token_device": fetch[3]
            }
        return result

    def get_notification_new_task_data(self, exam_id):
        result = []
        query_select = f"""
          select e.exam_id, u.userid, u.token_device from exam e 
            inner join student s on e.class_id = s.class_id
            inner join users u on u.username = s.nis where e.exam_id = {exam_id}
        """

        fetch = self.pointer.fetch_all(query_select)
        if fetch:
            for row in fetch:
                result.append({
                    "exam_id": row[0],
                    "userid": row[1],
                    "token_device": row[2]
                })
        return result

    def get_notification_new_score(self, ts_id):
        result = []
        query_select = f"""
            SELECT 
                ts.task_id,
                u_student.userid AS student_user_id,
                u_parent.userid AS parent_user_id,
                u_student.token_device as student_token_device,
                u_parent.token_device as parent_token_device
            FROM task_student ts
            LEFT JOIN student s ON s.student_id = ts.student_id
            LEFT JOIN parent p ON p.parent_id = s.parent_id
            LEFT JOIN users u_student ON u_student.username = s.nis
            LEFT JOIN users u_parent ON u_parent.username = p.username
            WHERE ts.ts_id = {ts_id}
        """
        fetch = self.pointer.fetch_all(query_select)
        if fetch:
            for row in fetch:
                result.append({
                    "task_id": row[0],
                    "student_userid": row[1],
                    "parent_userid": row[2],
                    "student_token_device": row[3],
                    "parent_token_device": row[4]
                })
        return result


if __name__ == "__main__":
    print(UserModel().get_users(sekolah_id='1'))
    # SEED DATA