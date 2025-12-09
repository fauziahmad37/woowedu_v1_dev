from flask import Blueprint, request

from app import app
from lib.utility.tools import ResponseFormat
from model.postgres_model.user import UserModel

modUser = Blueprint('modUser', __name__)

@app.route('/v1/users', methods=['GET'])
def users():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    username = request.args.get("username")

    if username:
        data = UserModel().get_users(username)
    else:
        data = UserModel().get_users()

    if data:
        result["data"] = data
        result["message"] = "Users fetched"
        result["status"] = True
    else:
        result["data"] = {}
        result["message"] = "Users fetched"
        result["status"] = False
    return result

@app.route('/v1/user/update_device_token', methods=['PATCH'])
def update_device_token():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    username = request.args.get("username")
    token_device = request.args.get("token_device")

    if username and token_device:
        data = UserModel().update_token_device(username, token_device)
        if data:
            result["data"] = data
            result["message"] = "Token update success"
            result["status"] = True
        else:
            result["data"] = {}
            result["message"] = "Token update failed"
            result["status"] = False
    return result

