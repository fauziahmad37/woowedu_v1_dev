from flask import Blueprint, render_template, jsonify, request

from app import app
from lib.utility.tools import ResponseFormat
from model.postgres_model.sesi import SesiModel
from model.postgres_model.user import UserModel
from version import combine_line

modHome = Blueprint('modHome', __name__)

@app.route('/', methods=['GET'])
def home():
    return render_template('index.html', version=combine_line)

@app.route('/check', methods=['GET'])
def check():
    data = SesiModel().get_upcoming_class(15)
    return jsonify(data), 200

@app.route('/absen', methods=['POST'])
def insert_absen():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    userid = request.form.get('userid')
    logtime = request.form.get('logtime')

    data = UserModel().add_absent(userid, logtime)
    if data:
        result["data"] = f"Absent with userid: {userid} successfuly inserted at {logtime}"
        result["message"] = "Data successfuly inserted"
        result["status"] = True
    return jsonify(result), 200
