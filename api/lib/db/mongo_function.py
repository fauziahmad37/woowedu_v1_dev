from pymongo import MongoClient

import config
from lib.log_format import *
import textwrap
import json


class MongoDB:
    def __init__(self, host=config.MONGO_HOST, port=config.MONGO_PORT, db_name=config.MONGO_DB):
        self.host = host
        self.port = port
        self.db_name = db_name
        self.db = None
        self.client = None
        self.connect()

    def connect(self):
        self.client = MongoClient(self.host, self.port)
        if self.db_name:
            self.db = self.client[self.db_name]

    def get_collection(self, collection_name):
        if self.client is None:
            self.connect()
        return self.db[collection_name] if hasattr(self, 'db') else None

    def insert_one(self, collection_name, document):
        if self.client is None:
            self.connect()
        try:
            log_document = textwrap.shorten(json.dumps(document), width=100) if document else ""
        except Exception:  # noqa
            log_document = textwrap.shorten(str(document), width=100) if document else ""
        try:
            mongo_collection = self.get_collection(collection_name)
            if mongo_collection is not None:
                log_debug_format("mongo_function", f"[{collection_name}]", "insert_one", f"{log_document}")
                return mongo_collection.insert_one(document)
        except Exception as e:
            log_error_format("mongo_function", f"[{collection_name}]", "insert_one", f"{e} : {log_document}")
            return None
        log_warning_format("mongo_function", f"[{collection_name}]",
                           "insert_one", f"No Data Inserted -> {log_document}")
        return None

    def insert_many(self, collection_name, documents):
        if self.client is None:
            self.connect()
        try:
            log_document = textwrap.shorten(json.dumps(documents), width=100) if documents else ""
        except Exception:  # noqa
            log_document = textwrap.shorten(str(documents), width=100) if documents else ""
        try:
            mongo_collection = self.get_collection(collection_name)
            if mongo_collection is not None:
                log_debug_format("mongo_function", f"[{collection_name}]", "insert_many", f"{log_document}")
                return mongo_collection.insert_many(documents)
        except Exception as e:
            log_error_format("mongo_function", f"[{collection_name}]", "insert_many", f"{e} : {log_document}")
            return None
        log_warning_format("mongo_function", f"[{collection_name}]",
                           "insert_many", f"No Data Inserted -> {log_document}")
        return None

    def find_many(self, collection_name, query=None, projection=None):
        if self.client is None:
            self.connect()
        try:
            log_document = textwrap.shorten(json.dumps(query), width=100) if query else ""
        except Exception:  # noqa
            log_document = textwrap.shorten(str(query), width=100) if query else ""
        try:
            mongo_collection = self.get_collection(collection_name)
            if mongo_collection is not None:
                log_debug_format("mongo_function", f"[{collection_name}]", "find_many", f"{log_document}")
                if not projection:
                    return mongo_collection.find(query, projection=projection) if query \
                        else mongo_collection.find(projection=projection)
                else:
                    return mongo_collection.find(query, projection=projection) if query \
                        else mongo_collection.find(projection=projection)
        except Exception as e:
            log_error_format("mongo_function", f"[{collection_name}]", "find_many", f"{e} : {log_document}")
            return None
        log_warning_format("mongo_function", f"[{collection_name}]",
                           "find_many", f"No Data Were Found -> {log_document}")
        return None

    def find_one(self, collection_name, query=None):
        if self.client is None:
            self.connect()
        try:
            log_document = textwrap.shorten(json.dumps(query), width=100) if query else ""
        except Exception:  # noqa
            log_document = textwrap.shorten(str(query), width=100) if query else ""
        try:
            mongo_collection = self.get_collection(collection_name)
            if mongo_collection is not None:
                log_debug_format("mongo_function", f"[{collection_name}]", "find_one", f"{log_document}")
                return mongo_collection.find_one(query) if query else mongo_collection.find_one()
        except Exception as e:
            log_error_format("mongo_function", f"[{collection_name}]", "find_one", f"{e} : {log_document}")
            return None
        log_warning_format("mongo_function", f"[{collection_name}]", "find_one",
                           f"No Data Were Found -> {log_document}")
        return None

    def update_one(self, collection_name, filter_data, update):
        if self.client is None:
            self.connect()
        query = f"{filter_data} -> {update}"
        try:
            log_document = textwrap.shorten(json.dumps(query), width=100) if query else ""
        except Exception:  # noqa
            log_document = textwrap.shorten(str(query), width=100) if query else ""
        try:
            mongo_collection = self.get_collection(collection_name)
            if mongo_collection is not None:
                log_debug_format("mongo_function", f"[{collection_name}]", "update_one", f"{log_document}")
                return mongo_collection.update_one(filter_data, update)
        except Exception as e:
            log_error_format("mongo_function", f"[{collection_name}]", "update_one", f"{e} : {log_document}")
            return None
        log_warning_format("mongo_function", f"[{collection_name}]",
                           "update_one", f"No Data Can Be Updated -> {log_document}")
        return None

    def update_many(self, collection_name, filter_data, update):
        if self.client is None:
            self.connect()
        query = f"{filter_data} -> {update}"
        try:
            log_document = textwrap.shorten(json.dumps(query), width=100) if query else ""
        except Exception:  # noqa
            log_document = textwrap.shorten(str(query), width=100) if query else ""
        try:
            mongo_collection = self.get_collection(collection_name)
            if mongo_collection is not None:
                log_debug_format("mongo_function", f"[{collection_name}]", "update_many", f"{log_document}")
                return mongo_collection.update_many(filter_data, update)
        except Exception as e:
            log_error_format("mongo_function", f"[{collection_name}]", "update_many", f"{e} : {log_document}")
            return None
        log_warning_format("mongo_function", f"[{collection_name}]", "update_many",
                           f"No Data Can Be Updated - > {log_document}")
        return None

    def delete_one(self, collection_name, filter_data):
        if self.client is None:
            self.connect()
        try:
            log_document = textwrap.shorten(json.dumps(filter_data), width=100) if filter_data else ""
        except Exception:  # noqa
            log_document = textwrap.shorten(str(filter_data), width=100) if filter_data else ""
        try:
            mongo_collection = self.get_collection(collection_name)
            if mongo_collection is not None:
                log_debug_format("mongo_function", f"[{collection_name}]", "delete_one", f"{log_document}")
                return mongo_collection.delete_one(filter_data)
        except Exception as e:
            log_error_format("mongo_function", f"[{collection_name}]", "delete_one", f"{e} : {log_document}")
            return None
        log_warning_format("mongo_function", f"[{collection_name}]",
                           "delete_one", f"No Data Can Be Deleted -> {log_document}")
        return None

    def delete_many(self, collection_name, filter_data):
        if self.client is None:
            self.connect()
        try:
            log_document = textwrap.shorten(json.dumps(filter_data), width=100) if filter_data else ""
        except Exception:  # noqa
            log_document = textwrap.shorten(str(filter_data), width=100) if filter_data else ""
        try:
            mongo_collection = self.get_collection(collection_name)
            if mongo_collection is not None:
                log_debug_format("mongo_function", f"[{collection_name}]", "delete_many", f"{log_document}")
                return mongo_collection.delete_many(filter_data)
        except Exception as e:
            log_error_format("mongo_function", f"[{collection_name}]", "delete_many", f"{e} : {log_document}")
            return None
        log_warning_format("mongo_function", f"[{collection_name}]", "delete_many",
                           f"No Data Can Be Deleted -> {log_document}")
        return None

    def document_exists(self, collection_name, doc):
        mongo_collection = self.get_collection(collection_name)
        return mongo_collection.count_documents(doc, limit=1) != 0


if __name__ == "__main__":
    db = MongoDB(db_name='db_message_broker_gateway')
    # collection = db.get_collection('nama_collection')
    # collection.insert_one({'key': 'value'})
    result = db.find_one("user_activity", {'key': 'value'})
    print(result)
