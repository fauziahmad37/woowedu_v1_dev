import inspect
import json
import requests
from google.oauth2 import service_account
import google.auth.transport.requests

from lib.log_format import log_debug_format


class FcmMessage:
    def __init__(self):
        self.main_url = "https://fcm.googleapis.com"
        self.endpoint = "/v1/projects/woowedu-de1ff/messages:send"
        self.channel_id="WoowEdunotification"
        self.sound="wowedu.mp3"
        self.json_key_path = r"woowedu-de1ff-firebase-adminsdk-re2a2-3496e9578a.json"
        self.click_action = "FLUTTER_NOTIFICATION_CLICK"

    def get_fcm_bearer_token(self):
        scopes = ["https://www.googleapis.com/auth/firebase.messaging"]
        credentials = service_account.Credentials.from_service_account_file(
            self.json_key_path,
            scopes=scopes
        )
        request = google.auth.transport.requests.Request()
        credentials.refresh(request)
        return credentials.token

    def send(self, token, title, body):
        data = json.dumps({
            "message": {
                "token": token,
                "notification": {
                    "body": body,
                    "title": title
                },
                "data": {
                    "current_user_fcm_token": token
                },
                "android": {
                    "notification": {
                        "channel_id": self.channel_id,
                        "sound": self.sound,
                        "click_action": self.click_action
                    }
                }
            }
        })

        headers = {
            "Authorization": f"Bearer {FcmMessage().get_fcm_bearer_token()}",
            "Content-Type": "application/json"
        }
        result = requests.post(url=f"{self.main_url}{self.endpoint}", data=data, headers=headers)
        log_debug_format("model", "FIREBASE_MESSAGE", inspect.currentframe().f_code.co_name,
                         f"DATA MESSAGE SENDED WITH STATUS CODE {result.status_code} result: {result.json()}")
        return result.json()