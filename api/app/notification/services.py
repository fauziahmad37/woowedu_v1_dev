import threading

from flask import Blueprint, request, jsonify

from app import app
from lib.FcmMessage import FcmMessage
from lib.utility.tools import ResponseFormat
from model.postgres_model.notif import NotifModel
from model.postgres_model.sesi import SesiModel
from model.postgres_model.user import UserModel

modNotification = Blueprint('modNotification', __name__)

@app.route('/v1/notification', methods=['GET'])
def notification():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    user_id = request.args.get("user_id")
    start_date = request.args.get("start_date")
    end_date = request.args.get("end_date")

    # Kirim semua parameter ke fungsi
    data = NotifModel().get_notifications(
        user_id=user_id,
        start_date=start_date,
        end_date=end_date
    )

    total_seen = []
    total_unseen = []
    for fetch in data:
        seen = fetch["seen"]

        if seen:
            total_seen.append(seen)
        else:
            total_unseen.append(seen)
    data = {
        "notif_data": data,
        "total_data": len(data),
        "total_seen": len(total_seen),
        "total_unseen": len(total_unseen)
    }

    if data:
        result["data"] = data
        result["message"] = "Notification fetched"
        result["status"] = True
    else:
        result["data"] = {}
        result["message"] = "No notification found"
        result["status"] = False

    return result

@app.route('/v1/notification/seen', methods=['PATCH'])
def update_notification_seen():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    notif_id = request.args.get("notif_id")
    seen = request.args.get("seen")

    if notif_id and seen:
        data = NotifModel().update_notification_seen(notif_id, seen)
        if data:
            result["data"] = data
            result["message"] = "Notification seen updated"
            result["status"] = True
        else:
            result["data"] = {}
            result["message"] = "Notification seen not updated"
            result["status"] = False
    return result

def send_news_notif(news_id, sekolah_id, title, body):
    if news_id and sekolah_id and title and body:
        data = UserModel().get_users(sekolah_id=sekolah_id)
        for fetch in data:
            token_device = fetch["token_device"]
            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification(
                "NEWS", title, False, fetch["userid"],
                f"news/detail/{news_id}", None, news_id, None, None, body)

@app.route('/v1/notification/pengumuman', methods=['POST'])
def notification_pengumuman():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    # Handle Request
    try:
        json_data = request.get_json(silent=True)
    except Exception as e:
        result['message'] = f"Request failed => {e}"
        return jsonify(result)
    if not json_data:
        result['message'] = "Missing required fields"
        return jsonify(result)

    news_id = json_data.get("news_id")
    sekolah_id = json_data.get("sekolah_id")
    title = json_data.get("title")
    body = json_data.get("body")

    thread = threading.Thread(target=send_news_notif, args=(news_id, sekolah_id, title, body))
    thread.daemon = True
    thread.start()

    result["data"] = {}
    result["message"] = "Notification send to all receiver"
    result["status"] = True
    return result

def send_tugas_notif(task_id, kelas_arr, title, body):
    if task_id and kelas_arr and title and body:
        for kelas in kelas_arr:
            data = UserModel().get_notification_class_data(class_id=kelas)
            for fetch in data:
                user_id = fetch["userid"]
                token_device = fetch["token_device"]
                if token_device:
                    FcmMessage().send(token_device, title, body)
                NotifModel().insert_notification("TASK", title, False, user_id, f"task/detail/{task_id}", task_id,
                                                 None,
                                                 None, None, body)


@app.route('/v1/notification/tugas', methods=['POST'])
def notification_tugas():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    # Handle Request
    try:
        json_data = request.get_json(silent=True)
    except Exception as e:
        result['message'] = f"Request failed => {e}"
        return jsonify(result)
    if not json_data:
        result['message'] = "Missing required fields"
        return jsonify(result)

    task_id = json_data.get("task_id")
    kelas_arr = json_data.get("kelas_arr")
    title = json_data.get("title")
    body = json_data.get("body")

    thread = threading.Thread(target=send_tugas_notif, args=(task_id, kelas_arr, title, body))
    thread.daemon = True
    thread.start()

    result["data"] = {}
    result["message"] = "Notification send to all receiver"
    result["status"] = True
    return result

def send_sesi_notif(sesi_id, title, body):
    if sesi_id  and title and body:
        data = UserModel().get_notification_sesi_data(sesi_id=sesi_id)
        for fetch in data:
            token_device = fetch["token_device"]
            user_id = fetch["userid"]
            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification("SESI", title, False, user_id, f"sesi/lihat_sesi/{sesi_id}", None, None,
                                             sesi_id, None, body)


@app.route('/v1/notification/sesi', methods=['POST'])
def notification_sesi():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    # Handle Request
    try:
        json_data = request.get_json(silent=True)
    except Exception as e:
        result['message'] = f"Request failed => {e}"
        return jsonify(result)
    if not json_data:
        result['message'] = "Missing required fields"
        return jsonify(result)

    sesi_id = json_data.get("sesi_id")
    title = json_data.get("title")
    body = json_data.get("body")

    thread = threading.Thread(target=send_sesi_notif, args=(sesi_id, title, body))
    thread.daemon = True
    thread.start()

    result["data"] = {}
    result["message"] = "Notification send to all receiver"
    result["status"] = True
    return result

def send_new_exam_notif(exam_id, title, body):
    if exam_id  and title and body:
        data = UserModel().get_notification_new_task_data(exam_id=exam_id)
        for fetch in data:
            token_device = fetch["token_device"]
            user_id = fetch["userid"]
            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification("EXAM", title, False, user_id, f"asesmen", None, None,
                                             None, exam_id, body)


@app.route('/v1/notification/new_exam', methods=['POST'])
def notification_new_exam():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    # Handle Request
    try:
        json_data = request.get_json(silent=True)
    except Exception as e:
        result['message'] = f"Request failed => {e}"
        return jsonify(result)
    if not json_data:
        result['message'] = "Missing required fields"
        return jsonify(result)

    exam_id = json_data.get("exam_id")
    title = json_data.get("title")
    body = json_data.get("body")

    thread = threading.Thread(target=send_new_exam_notif, args=(exam_id, title, body))
    thread.daemon = True
    thread.start()

    result["data"] = {}
    result["message"] = "Notification send to all receiver"
    result["status"] = True
    return result

def send_exam_scoring_notif(es_id):
    if es_id:
        data = SesiModel().get_exam_student(exam_student_id=es_id)
        for fetch in data:
            es_id = fetch["es_id"]
            exam_title = fetch["exam_title"]
            user_id = fetch["user_id"]
            user_type = fetch["user_type"]
            token_device = fetch["token_device"]

            title = "Penilaian Asesmen"

            if user_type == "student":
                body = f"Nilai asesmen {exam_title} anda sudah keluar."
            else:
                body = f"Nilai asesmen {exam_title} anak anda sudah keluar."

            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification("EXAM", title, False, user_id, f"asesmen", None, None,
                                             None, es_id, body)

@app.route('/v1/notification/exam/scoring', methods=['POST'])
def notification_exam_scoring():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    # Handle Request
    try:
        json_data = request.get_json(silent=True)
    except Exception as e:
        result['message'] = f"Request failed => {e}"
        return jsonify(result)
    if not json_data:
        result['message'] = "Missing required fields"
        return jsonify(result)

    es_id = json_data.get("es_id")

    thread = threading.Thread(target=send_exam_scoring_notif, args=(es_id, ))
    thread.daemon = True
    thread.start()

    result["data"] = {}
    result["message"] = "Notification send to all receiver"
    result["status"] = True
    return result

def send_score_task_notif(ts_id, title, body, role='student'):
    if ts_id  and title and body:
        data = UserModel().get_notification_new_score(ts_id=ts_id)
        for fetch in data:
            task_id = fetch["task_id"]

            if role == 'parent':
                user_id = fetch["parent_userid"]
                token_device = fetch["student_token_device"]
            else:
                user_id = fetch["student_userid"]
                token_device = fetch["parent_userid"]

            if token_device:
                FcmMessage().send(token_device, title, body)
            NotifModel().insert_notification("TASK", title, False, user_id, f"task/detail/{task_id}", task_id, None,
                                             None, None, body)


@app.route('/v1/notification/score/<role>', methods=['POST'])
def notification_score(role):
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    # Handle Request
    try:
        json_data = request.get_json(silent=True)
    except Exception as e:
        result['message'] = f"Request failed => {e}"
        return jsonify(result)
    if not json_data:
        result['message'] = "Missing required fields"
        return jsonify(result)

    ts_id = json_data.get("ts_id")
    title = json_data.get("title")
    body = json_data.get("body")

    thread = threading.Thread(target=send_score_task_notif, args=(ts_id, title, body, role))
    thread.daemon = True
    thread.start()

    result["data"] = {}
    result["message"] = "Notification send to all receiver"
    result["status"] = True
    return result


@app.route('/v1/notification/login', methods=['POST'])
def notification_login():
    result = ResponseFormat(data="", message="", status="")
    result["data"] = {}
    result["message"] = "Invalid request"
    result["status"] = False

    # Handle Request
    try:
        json_data = request.get_json(silent=True)
    except Exception as e:
        result['message'] = f"Request failed => {e}"
        return jsonify(result)
    if not json_data:
        result['message'] = "Missing required fields"
        return jsonify(result)

    username = json_data.get("username")
    title = json_data.get("title")
    body = json_data.get("body")

    if username and title and body:
        data = UserModel().get_notification_login_data(username)
        if data:
            user_id = data["userid"]
            token_device = data["token_device"]

            NotifModel().insert_notification("LOGIN", title, False, user_id, f"#", None, None,
                                             None, None, body)
            FcmMessage().send(token_device, title, body)

    result["data"] = {}
    result["message"] = "Notification send to all receiver"
    result["status"] = True
    return result