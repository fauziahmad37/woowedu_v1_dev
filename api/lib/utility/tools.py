import binascii
import re
import shutil
from datetime import datetime, timedelta, timezone

from lib.log_format import log_error_format, log_debug_format, log_info_format
import config
import hashlib
import os

class Validation:

    @staticmethod
    def is_valid_email(email):
        email_pattern = r'^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$'
        if re.match(email_pattern, email):
            return True
        else:
            return False

    @staticmethod
    def is_valid_password(password):
        return len(password) >= 8

    def convert_phone_number(self, phone_number):
        if phone_number.startswith('0'):
            number = phone_number[1:]
            if not number:
                return None
            elif self.has_letters(number):
                return None
            return '+62' + number
        elif phone_number.startswith('+62'):
            number = phone_number[3:]
            if not number:
                return None
            elif self.has_letters(number):
                return None
            return phone_number
        else:
            return None  # Return as is if not matching any rule

    @staticmethod
    def has_letters(phone_number):
        non_digit_pattern = r'\D'
        match = re.search(non_digit_pattern, phone_number)
        return match is not None

    @staticmethod
    def check_special_char(input_string):
        # Regex pattern untuk karakter khusus kecuali @, _, -, ., dan ,
        pattern = r'[^\w\s@_,\.\-]'

        # Mencari karakter khusus
        match = re.search(pattern, input_string)

        # Jika ada karakter khusus selain @, _, -, ., dan ,, kembalikan True
        return match is not None

    @staticmethod
    def replace_special_characters(input_string):
        # Memperbolehkan huruf, angka, @, dan . (titik)
        return re.sub(r'[^a-zA-Z0-9@.]', '', input_string)


class Storage:

    @staticmethod
    def disk_inf_mb():
        total, used, free = shutil.disk_usage('/')
        total_mb = total / (1024 * 1024)
        used_mb = used / (1024 * 1024)
        free_mb = free / (1024 * 1024)
        return total_mb, used_mb, free_mb

    @staticmethod
    def disk_inf_kb():
        total, used, free = shutil.disk_usage('/')
        total_kb = total / 1024
        used_kb = used / 1024
        free_kb = free / 1024
        return total_kb, used_kb, free_kb

    @staticmethod
    def disk_inf_gb():
        total, used, free = shutil.disk_usage('/')
        total_gb = total / 1024
        used_gb = used / 1024
        free_gb = free / 1024
        return total_gb, used_gb, free_gb


class Filetype:

    @staticmethod
    def is_image_file(filename):
        image_extensions = ['.jpg', '.jpeg', '.png']
        file_ext = os.path.splitext(filename)[1].lower()
        if file_ext in image_extensions:
            return True
        else:
            return False

    @staticmethod
    def is_video_file(filename):
        video_extensions = ['.mp4', '.avi', '.mov', '.mkv', '.wmv', '.flv', '.webm', '.mpeg']
        file_ext = os.path.splitext(filename)[1].lower()
        if file_ext in video_extensions:
            return True
        else:
            return False

    @staticmethod
    def is_audio_file(filename):
        audio_extensions = ['.mp3', '.wav', '.aac', '.flac', '.ogg', '.m4a', '.wma', '.aiff', '.alac', '.3gp']
        file_ext = os.path.splitext(filename)[1].lower()
        return file_ext in audio_extensions


class DateUtility:

    @staticmethod
    def time_day(waktu: datetime):
        return waktu.strftime("%Y-%m-%d")

    def time_extractor(self, time_data: str):
        try:
            waktu = datetime.strptime(time_data, "%Y-%m-%d %H:%M:%S")
            return self.time_day(waktu)
        except Exception:  # noqa
            try:
                waktu = datetime.strptime(time_data, "%Y-%m-%d %H:%M")
                return self.time_day(waktu)
            except Exception:  # noqa
                try:
                    waktu = datetime.strptime(time_data, "%Y-%m-%d %H")
                    return self.time_day(waktu)
                except Exception:  # noqa
                    try:
                        waktu = datetime.strptime(time_data, "%Y-%m-%d")
                        return self.time_day(waktu)
                    except Exception:  # noqa
                        try:
                            waktu = datetime.strptime(time_data, "%Y-%m")
                            return self.time_day(waktu)
                        except Exception:  # noqa
                            try:
                                waktu = datetime.strptime(time_data, "%Y")
                                return self.time_day(waktu)
                            except Exception:  # noqa
                                return None

    def time_converter(self, time1: str, time2: str):
        time_data = self.time_extractor(time2)
        if time_data == "year":
            result = datetime.strptime(time1, "%Y-%m-%d %H:%M:%S").strftime("%Y")
        elif time_data == "month":
            result = datetime.strptime(time1, "%Y-%m-%d %H:%M:%S").strftime("%Y-%m")
        elif time_data == "day":
            result = datetime.strptime(time1, "%Y-%m-%d %H:%M:%S").strftime("%Y-%m-%d")
        elif time_data == "hour":
            result = datetime.strptime(time1, "%Y-%m-%d %H:%M:%S").strftime("%Y-%m-%d %H")
        elif time_data == "minute":
            result = datetime.strptime(time1, "%Y-%m-%d %H:%M:%S").strftime("%Y-%m-%d %H:%M")
        elif time_data == "second":
            result = datetime.strptime(time1, "%Y-%m-%d %H:%M:%S").strftime("%Y-%m-%d %H:%M:%S")
        else:
            result = None
        return result

    @staticmethod
    def past_date_converter(day):
        current_date = datetime.now().strftime("%Y-%m-%d")
        current_date = datetime.strptime(current_date, "%Y-%m-%d")

        past_date = current_date - timedelta(days=day)
        past_date = past_date.strftime("%Y-%m-%d")
        return past_date

    @staticmethod
    def time_checker(time_data: str):
        try:
            datetime.strptime(time_data, "%Y-%m-%d")
            return "hari", "%Y-%m-%d"
        except Exception:  # noqa
            try:
                datetime.strptime(time_data, "%Y-%m")
                return "bulan", "%Y-%m"
            except Exception:  # noqa
                try:
                    datetime.strptime(time_data, "%Y")
                    return "tahun", "%Y"
                except Exception:  # noqa
                    return None, None


class DirectoryUtility:

    @staticmethod
    def create_directory(root_folder, *subdirs):
        dir_path = os.path.join(str(root_folder), *subdirs)

        os.makedirs(dir_path, exist_ok=True)
        return dir_path  # Mengembalikan path directory yang dibuat

    @staticmethod
    def check_directory(target_folder):
        if not os.path.exists(target_folder):
            os.makedirs(target_folder)
            log_debug_format("LIB", "[DIRECTORY]", "MAKE DIRECTORY", f"SUCCESS CREATE DIRECTORY {target_folder}")
        else:
            pass
            log_debug_format("LIB", "[DIRECTORY]", "MAKE DIRECTORY", f"DIRECROTY {target_folder} IS ALREADY AVAILABLE")

    @staticmethod
    def remove_folder(folder_path):
        try:
            shutil.rmtree(folder_path)
        except OSError as e:
            log_error_format("LIB", "[REMOVE FOLDER]", f"{folder_path}", f"{e.strerror}")

    @staticmethod
    def check_file(file_path):
        if os.path.exists(file_path):
            return True
        else:
            return False

    @staticmethod
    def explore_link_folder(root_folder):
        result_list = []

        def explore_folder(folder):
            for item in os.listdir(folder):
                item_path = os.path.join(folder, item)
                if os.path.isdir(item_path):
                    explore_folder(item_path)
                else:
                    result_list.append(item_path.replace("\\", "/").replace(config.FTP_DOMAIN, ""))

        explore_folder(root_folder) # Ini perlu recursive karena dia bisa loop berkali2
        return result_list

    @staticmethod
    def list_empty_folder(root_folder):
        result_list = []

        def explore_folder(folder):
            if len(os.listdir(folder)) == 0:
                delete_folder = folder.replace("\\", "/")
                if delete_folder == root_folder:
                    pass
                else:
                    result_list.append(delete_folder)
            for item in os.listdir(folder):
                item_path = os.path.join(folder, item)
                if os.path.isdir(item_path):
                    explore_folder(item_path)

        explore_folder(root_folder) # Ini perlu recursive karena dia bisa loop berkali2
        return result_list

    def delete_empty_folder(self, root_folder, target_name):
        try:
            list_folder = self.list_empty_folder(root_folder)
            set_delete = set()
            for i in list_folder:
                data = i.split("/")
                counter = 0
                for j in range(len(data)):
                    if counter == 1:
                        path_data = "/".join(data[:j + 1])
                        set_delete.add(path_data)
                        break
                    if data[j] == target_name:
                        counter += 1
            if set_delete:
                log_info_format("LIB", "[DIRECTORY DELETER]", "DELETE EMPTY DIRECTORY", f"{set_delete}")
            for deleter in set_delete:
                shutil.rmtree(deleter)
            return list(set_delete)
        except Exception as e:
            log_error_format("LIB", "[DIRECTORY]", "MAKE DIRECTORY", f"FAILED DELETE EMPTY FOLDER {e}")
            return []

    def delete_empty_dir(self, root_folder):
        try:
            list_folder = self.list_empty_folder(root_folder)
            for deleter in list_folder:
                shutil.rmtree(deleter)
            if list_folder:
                log_info_format("LIB", "[DIRECTORY DELETER]", "DELETE EMPTY DIRECTORY", f"{list_folder}")
            return list(list_folder)
        except Exception as e:
            log_error_format("LIB", "[DIRECTORY]", "MAKE DIRECTORY", f"FAILED DELETE EMPTY FOLDER {e}")
            return []


class HashingUtility:
    @staticmethod
    def MD5Encode(text):
        result = hashlib.md5(text.encode("utf-8")).hexdigest()
        return result


def generate_iso_datetime(year, month, day, hour, minute, second, tz_offset_hours=0):
   tz = timezone(timedelta(hours=tz_offset_hours))
   dt = datetime(year, month, day, hour, minute, second, tzinfo=tz)
   return dt.isoformat()


def pbkdf2_hmac_sha256(password: str, salt_hex: str, iterations: int = 1000, key_length: int = 32) -> str:
   """
   Menghasilkan digest PBKDF2WithHmacSHA256 dari password dan salt dalam hex string.
   """
   salt = bytes.fromhex(salt_hex)
   password_bytes = password.encode('utf-8')

   dk = hashlib.pbkdf2_hmac('sha256', password_bytes, salt, iterations, dklen=key_length)

   return binascii.hexlify(dk).decode()


def generate_salt(length: int = 16) -> str:
   """Generate random salt dengan panjang tertentu (default: 16 bytes) dan dikembalikan dalam bentuk hex."""
   salt = os.urandom(length)
   return binascii.hexlify(salt).decode()

def generate_iso_datetime_now(tz_offset_hours=7):
   tz = timezone(timedelta(hours=tz_offset_hours))
   now = datetime.now(tz)
   return now.isoformat()

def pbkdf2_hmac_sha256_no_salt(password: str, iterations: int = 1000, key_length: int = 32) -> str:
   password_bytes = password.encode('utf-8')
   dk = hashlib.pbkdf2_hmac('sha256', password_bytes, b'', iterations, dklen=key_length)
   return binascii.hexlify(dk).decode()

def ResponseFormat(data, message, status):
    return {
        "data": data,
        "message": message,
        "status": status
    }