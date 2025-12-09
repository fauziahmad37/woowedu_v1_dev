from flask import Blueprint, request, jsonify

from app import app
from lib.FcmMessage import FcmMessage
from lib.utility.tools import ResponseFormat
from model.mongo_model.message_model import MessageModel

modMessage = Blueprint('modMessage', __name__)

@app.route('/v1/message/generate-token', methods=['GET'])
def generate_token():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    jwt_token = FcmMessage().get_fcm_bearer_token()
    if len(jwt_token) > 100:
        result["data"] = jwt_token
        result["message"] = "Generated!"
        result["status"] = True
    else:
        result["data"] = jwt_token
        result["message"] = "Failed to generate token!"
        result["status"] = False
    return result

@app.route('/v1/message/send', methods=['GET'])
def send_message():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    user_token = request.args.get("user_token")
    title = request.args.get("title")
    body = request.args.get("body")

    if user_token and title and body:
        message = FcmMessage().send(user_token, title, body)
        if message:
            result["data"] = message
            result["message"] = "Publish notification success"
            result["status"] = True
        else:
            result["data"] = {}
            result["message"] = "Publish notification failed"
            result["status"] = False
    else:
        result["data"] = {}
        result["message"] = "Failed to generate publish notification!"
        result["status"] = False
    return result

@app.route('/v1/message/manage/notification', methods=['POST'])
def manage_notification():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    try:
        json_data = request.get_json(silent=True)
    except Exception as e:
        result['message'] = f"Request failed => {e}"
        return jsonify(result)
    if not json_data:
        result['message'] = "Missing required fields"
        return jsonify(result)

    result = MessageModel().insert_one_message(json_data)
    if result:
        result["data"] = result
        result["message"] = "Data inserted to mongodb"
        result["status"] = True
    else:
        result["data"] = result
        result["message"] = "Data not inserted to mongodb"
        result["status"] = False
    return result

