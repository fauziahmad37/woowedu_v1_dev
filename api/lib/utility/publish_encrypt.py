from lib.utility.encrypt_tool import EncryptSettings
from lib.mqtt.mqtt_singleton import MainMqtt
from lib.utility.singleton_tool import singleton
import config
import json


@singleton
class PublishEncrypt:
    def __init__(self):
        self.ready = False
        self.engine = EncryptSettings(config.SALT_KEY)
        self.mqtt = MainMqtt()
        self.topic = f"{config.LOGS_TOPIC}"

    def publish_data(self, dict_message: dict):
        if not self.ready:
            self.connect()
        json_data = json.dumps(dict_message)
        data = self.engine.encrypt(json_data)
        try:
            self.mqtt.publish(self.topic, data)
        except Exception: # noqa
            pass

    def connect(self):
        self.mqtt = MainMqtt()
        self.ready = True


publisher = PublishEncrypt()

if __name__ == "__main__":
    publisher = PublishEncrypt()
    data_json = {"tets": "test"}
    publisher.publish_data(data_json)
