import config
from lib.db.mongo_function import MongoDB
from lib.log_format import log_warning_format, log_debug_format


class MessageModel:
    def __init__(self):
        self.pointer = MongoDB(config.MONGO_HOST, config.MONGO_PORT, config.MONGO_DB)
        self.collection_name = "notification_data"
        self.collection_object = self.pointer.get_collection(self.collection_name)

    @staticmethod
    def json_data_message(json_data):
        try:
            data = {
              "app_id": json_data["app_id"],
              "userid": json_data["userid"],
              "module": json_data["module"],
              "execute_time": json_data["execute_time"],
              "payload": json_data["payload"],
              "receiver": json_data["receiver"]
            }
        except Exception as e:
            log_warning_format("mongo_model", "[MESSAGE MODEL]",
                               "JSON DATA MESSAGE", f"FAILED CONSTRUCT DATA {e}")
            data = {}
        return data

    def insert_one_message(self, json_data: dict):
        if not json_data:
            log_warning_format("mongo_model", "[MESSAGE MODEL]",
                               "insert_one_message", f"FAIL TO INSERT TO {self.collection_name}")
            return False
        collection = self.pointer.insert_one(self.collection_name, self.json_data_message(json_data))
        if not collection.inserted_id:
            log_warning_format("mongo_model", "[MESSAGE MODEL]",
                               "insert_one_message", f"FAIL TO INSERT TO {self.collection_name}")
            return False
        log_debug_format("mongo_model", "[MESSAGE MODEL]",
                         "insert_one_message", f"SUCCESSFULLY INSERT TO {self.collection_name}")
        return  self.json_data_message(json_data)

    def find_last_data(self):
        data = self.pointer.find_one(self.collection_name)

        print(data)
        return ""