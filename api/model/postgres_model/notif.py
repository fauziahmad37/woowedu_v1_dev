import datetime
import inspect

import config
from lib.db.postgres_function import PostgreSQLConnector
from lib.log_format import log_error_format, log_debug_format


class NotifModel:
    def __init__(self):
        self.pointer = PostgreSQLConnector(config.DB_HOST_POSTGRESQL,
                                           config.DB_USER_POSTGRESQL,
                                           config.DB_PASSWORD_POSTGRESQL,
                                           config.DB_DATABASE_POSTGRESQL)
        self.table = "notif"

    def json_notification(self, row):
        result = {
            "notif_id": row[0],
            "type": row[1],
            "title": row[2],
            "seen": row[3],
            "user_id": row[4],
            "created_at": row[5].strftime('%Y-%m-%d %H:%M'),
            "link": row[6],
            "task_id": row[7],
            "news_id": row[8],
            "sesi_id": row[9],
            "exam_id": row[10],
            "description": row[11]
        }
        return result

    def get_notifications(self, user_id=None, notif_id=None, start_date=None, end_date=None):
        result = []
        conditions = []

        query_select = "SELECT * FROM notif"

        # Build conditions
        if user_id:
            conditions.append(f"user_id = '{user_id}'")
        if notif_id:
            conditions.append(f"notif_id = '{notif_id}'")
        if start_date:
            conditions.append(f"created_at >= '{start_date} 00:00:00'")
        if end_date:
            conditions.append(f"created_at <= '{end_date} 23:59:59'")

        # Add WHERE if there are any conditions
        if conditions:
            query_select += " WHERE " + " AND ".join(conditions)

        query_select += " ORDER BY notif_id DESC"

        # Fetch
        if notif_id:
            fetch = self.pointer.fetch_one(query_select)
            result = self.json_notification(fetch)
        else:
            fetch = self.pointer.fetch_all(query_select)
            for row in fetch:
                result.append(self.json_notification(row))
        return result

    def insert_notification(self, type, title, seen, user_id, link, task_id, news_id, sesi_id, exam_id, description):
        result = {}

        query_insert = """
            INSERT INTO notif (
                type, title, seen, user_id, created_at, link,
                task_id, news_id, sesi_id, exam_id, description
            ) VALUES (
                %s, %s, %s, %s, NOW(), %s,
                %s, %s, %s, %s, %s
            )
        """
        fetch_insert = self.pointer.execute_insert(query_insert, param=(type, title, seen, user_id, link, task_id, news_id, sesi_id, exam_id, description))

        if not fetch_insert:
            log_error_format("model", "NOTIFICATION_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA INSERT ERROR {fetch_insert}")

        if fetch_insert:
            log_debug_format("model", "NOTIFICATION_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA INSERT SUCCESSFULLY {result}")
        return result

    def update_notification_seen(self, notif_id, seen):
        result = {}

        query_update = """
            update notif set seen = %s where notif_id = %s
        """

        fetch_update = self.pointer.execute_query(query_update, param=(seen, notif_id,))

        if not fetch_update:
            log_error_format("model", "NOTIFICATION_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA UPDATE ERROR {fetch_update}")

        if fetch_update:
            result = self.get_notifications(notif_id=notif_id)
            log_debug_format("model", "NOTIFICATION_MODEL", inspect.currentframe().f_code.co_name,
                             f"DATA UPDATE SUCCESSFULLY {result}")
        return result

if __name__ == "__main__":
    print(NotifModel().get_notifications(user_id=83))
    # SEED DATA