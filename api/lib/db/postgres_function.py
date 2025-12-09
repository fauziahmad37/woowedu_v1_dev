import psycopg2
from lib.log_format import log_debug_format, log_error_format, log_warning_format


class PostgreSQLConnector:
    def __init__(self, host, user, password, database):
        self.host = host
        self.user = user
        self.password = password
        self.database = database
        self.connection = None
        self.cursor = None
        self.connect()

    def connect(self):
        try:
            self.connection = psycopg2.connect(
                host=self.host,
                user=self.user,
                password=self.password,
                database=self.database
            )
            self.cursor = self.connection.cursor()
            log_debug_format("LIB", "[DATABASE]", "CONNECT", "SUCCESS CONNECT TO PSQL-DATABASE")
        except Exception as err:
            log_error_format("LIB", "[DATABASE]", "CONNECT", f"FAILED CONNECT TO PSQL-DATABASE - {err}")

    def disconnect(self):
        if self.connection:
            self.cursor.close()
            self.connection.close()

    def execute_query(self, query, param=()):
        if not self.cursor:
            self.connect()
        try:
            self.cursor.execute(query, param)
            self.connection.commit()
            affected_rows = self.cursor.rowcount
            # log_debug_format("LIB", "[DATABASE]", "EXECUTE QUERY", f"{query}")
            return affected_rows if affected_rows and affected_rows != 0 else True
        except Exception as err:
            log_error_format("LIB", "[DATABASE]", "EXECUTE QUERY", f"{err}")
            self.connection.rollback()
            return False

    def execute_insert(self, query, param=()):
        return self.execute_query(query, param)

    def execute_insert_with_id(self, query, param=()):
        if not self.cursor:
            self.connect()
        try:
            self.cursor.execute(query + " RETURNING id;", param)  # PostgreSQL butuh RETURNING id
            inserted_id = self.cursor.fetchone()[0]
            self.connection.commit()
            log_debug_format("LIB", "[DATABASE]", "EXECUTE QUERY", f"{query}")
            return inserted_id
        except Exception as err:
            log_error_format("LIB", "[DATABASE]", "EXECUTE QUERY", f"{err}")
            self.connection.rollback()
            return False

    def fetch_one(self, query, param=()):
        if not self.cursor:
            self.connect()
        try:
            self.cursor.execute(query, param)
            result = self.cursor.fetchone()
            # log_debug_format("LIB", "[DATABASE]", "SELECT_ONE", f"{query}, {param}")
            return result
        except Exception as err:
            log_error_format("LIB", "[DATABASE]", "SELECT_ONE", f"{err}")
            return None

    def fetch_all(self, query, param=()):
        if not self.cursor:
            self.connect()
        try:
            self.cursor.execute(query, param)
            result = self.cursor.fetchall()
            # log_debug_format("LIB", "[DATABASE]", "SELECT_ALL", f"{query}")
            return result
        except Exception as err:
            log_error_format("LIB", "[DATABASE]", "SELECT_ALL", f"{err}")
            return []

    """ TRANSACTION MODE """
    def execute_query_transaction(self, query, param=()):
        if not self.cursor:
            self.connect()
        try:
            self.cursor.execute(query, param)
            self.connection.commit()
            return True
        except Exception as err:
            log_error_format("LIB", "[DATABASE]", "EXECUTE QUERY", f"{err}")
            self.connection.rollback()
            return False

    def save_execute_transaction(self, query, param=()):
        return self.execute_query_transaction(query, param)

    def insert_transaction(self, query, param=()):
        return self.execute_query_transaction(query, param)

    def insert_transaction_with_id(self, query, param=()):
        if not self.cursor:
            self.connect()
        try:
            self.cursor.execute(query + " RETURNING id;", param)
            inserted_id = self.cursor.fetchone()[0]
            return inserted_id
        except Exception as err:
            log_error_format("LIB", "[DATABASE]", "EXECUTE QUERY", f"{err}")
            self.connection.rollback()
            return False

    def fetch_one_transaction(self, query, param=()):
        return self.fetch_one(query, param)

    def fetch_all_transaction(self, query, param=()):
        return self.fetch_all(query, param)

    def transaction(self, func, *objt):
        if not self.cursor:
            self.connect()
        try:
            self.connection.begin()
            state = func(*objt)
            if not state:
                self.connection.rollback()
                log_warning_format("LIB", "[DATABASE]", "TRANSACTION ROLLBACK", f"TRANSACTION CANCELED")
                return None
            else:
                self.connection.commit()
                log_debug_format("LIB", "[DATABASE]", "TRANSACTION COMMIT", f"SUCCESS TO COMMIT TRANSACTION")
                return state
        except Exception as e:
            self.connection.rollback()
            log_error_format("LIB", "[DATABASE]", "ERROR TRANSACTION", f"FAILED TO EXECUTE TRANSACTION {e}")
            return None

if __name__ == "__main__":
    db = PostgreSQLConnector("127.0.0.1", "postgres", "admin", "woowspeech")
    data = db.fetch_all("SELECT * FROM user")

    print(data)