import version
import json
import os
import sys
import datetime

if getattr(sys, 'frozen', False):
    exe_path = os.path.dirname(sys.executable)
    os.chdir(exe_path)
else:
    script_path = os.path.dirname(os.path.abspath(__file__))
    os.chdir(script_path)

version.initialize()

conf = {}

try:
    with open('settings.json', 'r') as f:
        conf = json.loads(f.read())
except FileNotFoundError:
    message = "Failed to load 'settings.json' file. Please remake the file using Settings program or reinstall API."
    print('API Fatal Error', message)
    exit(1)

"""SERVER CONFIGURATION"""
SERVER_HOST = conf['SERVER']['SERVER_HOST']
ALL_IP = conf['SERVER']['ALL_IP']
SERVER_PORT = conf['SERVER']['SERVER_PORT']
PREFIX_DOMAIN = conf['SERVER']['PREFIX']
CERTFILE = conf['SERVER']['CERTFILE']
KEYFILE = conf['SERVER']['KEYFILE']
SALT_KEY = conf['SERVER']['SALT_KEY']
PUBLIC_URL = conf['SERVER']['PUBLIC_URL']
PROJECT = conf['SERVER']['PROJECT']


"DATABASE CONFIGURATION"
DB_HOST = conf['DATABASE']['MYSQL']['DB_HOST']
DB_USER = conf['DATABASE']['MYSQL']['DB_USER']
DB_PASSWORD = conf['DATABASE']['MYSQL']['DB_PASSWORD']
DB_DATABASE = conf['DATABASE']['MYSQL']['DB_DATABASE']
DB_HOST_POSTGRESQL = conf['DATABASE']['POSTGRESQL']['DB_HOST']
DB_USER_POSTGRESQL = conf['DATABASE']['POSTGRESQL']['DB_USER']
DB_PASSWORD_POSTGRESQL = conf['DATABASE']['POSTGRESQL']['DB_PASSWORD']
DB_DATABASE_POSTGRESQL = conf['DATABASE']['POSTGRESQL']['DB_DATABASE']
MONGO_HOST = conf['DATABASE']['MONGODB']['MONGO_HOST']
MONGO_PORT = conf['DATABASE']['MONGODB']['MONGO_PORT']
MONGO_DB = conf['DATABASE']['MONGODB']['MONGO_DB']

"""API RUNTIME"""
DEBUG = True if str(conf['SERVER']['DEBUG']).lower() == "true" else False
PRODUCTION = True if str(conf['SERVER']['PRODUCTION']).lower() == "true" else False
USING_JWT = True if str(conf['SERVER']['USING_JWT']).lower() == "true" else False
DEBUG_LEVEL = "DEBUG"  # INFO,DEBUG
FORCE_AUTOLOGIN = False
JWT_ACCESS_TOKEN_EXPIRES = datetime.timedelta(days=30)
SECRET_KEY = 'f2b1c0d6e5f7a9c8e2b1c0d6e5f7a9c8e2b1c0d6e5f7a9c8e'  # bson b.crypt
JWT_SECRET_KEY = 'G1Kg-WbB2Uo4JFh7HkbtWw9BCU3UXy3n2gN4jdMwFH0'
JSONIFY_PRETTYPRINT_REGULAR = False
FTP_DOMAIN = conf['SERVER']['FTP_DOMAIN']

PREFIX_API = '/api_content'
API_FUNCTION = ''
SLICE_API = 'api'
BASE_ROOT_PATH = PREFIX_API + '/' + API_FUNCTION

"""MQTT SERVICES"""
MQTT_NAME = conf['SERVICE']['MQTT']['MQTT_NAME']
MQTT_PORT = conf['SERVICE']['MQTT']['MQTT_PORT']
MQTT_HOST = conf['SERVICE']['MQTT']['MQTT_HOST']
LOGS_TOPIC = conf['SERVICE']['MQTT']['MQTT_TOPIC']['LOGS_TOPIC']

"""WEBSOCKET SERVICES"""
WS_NAME = conf['SERVICE']['WEBSOCKET']['WS_NAME']
WS_PORT = conf['SERVICE']['WEBSOCKET']['WS_PORT']
WS_HOST = conf['SERVICE']['WEBSOCKET']['WS_HOST']
WS_MAX_RETRIES = conf['SERVICE']['WEBSOCKET']['WS_MAX_RETRIES']
WS_RETRIES_INTERVAL = conf['SERVICE']['WEBSOCKET']['WS_RETRIES_INTERVAL']

WS_TOPIC = conf['SERVICE']['WEBSOCKET']['WS_TOPIC']['LOGS_TOPIC']


"""SERIAL SERVICE"""
SERIAL_PORT = conf['SERIAL']['SERIAL_PORT']
SERIAL_BAUDRATE = conf['SERIAL']['SERIAL_BAUDRATE']

"""SCHEDULER"""
TIME_DELAY = conf['SCHEDULER']['TIME_DELAY']
TIME_TYPE = str(conf['SCHEDULER']['TIME_TYPE']).lower()
