import os
from os.path import abspath, dirname
from pathlib import Path
from flask import Flask, request, jsonify
from flask_cors import CORS
from flask_jwt_extended import JWTManager
import loguru
import logging
from app.intercept_handler import InterceptHandler
import config
from lib.token.token_tools import Tokentools
from lib.log_format import *


__author__ = "GOBLAY"

class Setup:
    def __init__(self):
        self.log_level = "DEBUG"
        self.log_format = '{time} {level} {message}'
        self.log_path = r'/var/log/woowedu-fcm-api/debug.log'

    @staticmethod
    def initialize_api():
        from app.home.services import modHome as moduleHome
        from app.message.services import modMessage as moduleMessage
        from app.user.services import modUser as moduleUser
        from app.notification.services import modNotification as moduleNotification

        app.register_blueprint(moduleHome)
        app.register_blueprint(moduleMessage)
        app.register_blueprint(moduleUser)
        app.register_blueprint(moduleNotification)

    def initialize_logger(self):
        logger.enable("start logging woowedu-fcm")
        logger.start(self.log_path, level=self.log_level, format=self.log_format, rotation='25 MB')

        app.logger.addHandler(InterceptHandler())

    def log(self):
        loggers = logging.getLogger('')
        if self.log_path:
            if not os.path.exists(self.log_path):
                Path(dirname(abspath(self.log_path))).mkdir(parents=True, exist_ok=True)
                with open(self.log_path, 'w+') as f:
                    f.close()
            fh = logging.FileHandler(self.log_path)
            loggers.addHandler(fh)
            sys.excepthook = lambda x, y, z: logging.exception("Uncaught exception", exc_info=(x, y, z))
            loguru.logger.add(self.log_path, backtrace=True, diagnose=True, enqueue=True, retention="30 days")
        sh = logging.StreamHandler(sys.stderr)
        loggers.addHandler(sh)


cli = sys.modules['flask.cli']
cli.show_server_banner = lambda *x: None
app = Flask(__name__, template_folder=os.path.dirname(__file__) + "/../templates",
            static_folder=os.path.dirname(__file__) + "/../static")
# app.config['CORS_HEADERS'] = 'Content-Type'
# app.config.from_object('config')
app.config['SECRET_KEY'], app.config['JWT_ACCESS_TOKEN_EXPIRES'] = config.SECRET_KEY, config.JWT_ACCESS_TOKEN_EXPIRES
app.config['JWT_SECRET_KEY'] = config.JWT_SECRET_KEY
# cors = CORS(app, resources={r"/*": {"origins": "*"}})
cors = CORS(app)

@app.before_request
def log_request():
    logger.info(f"BEFORE REQUEST: {request.method} {request.path} | IP: {request.remote_addr} | Data: {request.get_json() if request.is_json else request.args}")

@app.after_request
def log_response(response):
    logger.info(f"AFTER RESPONSE: {request.method} {request.path} | Status: {response.status_code}")
    return response

try:
    jwt = JWTManager(app)
    ins = Setup()
    ins.log()
    ins.initialize_api()
    ins.initialize_logger()
    blacklist = Tokentools()

    @jwt.token_in_blocklist_loader
    def check_if_token_in_blacklist(decrypted_token, jwt_headers): # noqa
        current_token = request.headers.get('Authorization')
        current_token = current_token.split()[1]
        return current_token in blacklist.blacklist


    @jwt.invalid_token_loader
    def handle_invalid_token(callback): # noqa
        result = {"data": False, "status": 401, "message": "Unauthorized"}
        return jsonify(result), 401


    @jwt.expired_token_loader
    def handle_expired_token(jwt_header, jwt_payload): # noqa
        result = {"data": False, "status": 401, "message": "Unauthorized"}
        return jsonify(result), 401


    @jwt.unauthorized_loader
    def handle_missing_token(callback): # noqa
        result = {"data": False, "status": 401, "message": "Unauthorized"}
        return jsonify(result), 401

    @jwt.revoked_token_loader
    def handle_revoked_token(jwt_header, jwt_payload): # noqa
        result = {"data": False, "status": 401, "message": "Unauthorized"}
        return jsonify(result), 401


    @app.errorhandler(422)
    def handle_unprocessable_entity(error): # noqa
        result = {"data": False, "status": 401, "message": "Unauthorized"}
        return jsonify(result), 401

except Exception as e:
    log_error_format('app', 'exception', 'app', e)
