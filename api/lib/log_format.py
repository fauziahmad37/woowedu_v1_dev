import sys
from loguru import logger

formatting = "<green>{time:DD/MM/YY HH:mm:ss}</green> || <level>{level: <6}</level> || <level>{message}</level>"
logger.remove()
logger.add(sys.stderr, format=formatting)


def log_debug_format(directory, tag="[ENDPOINT]", def_name="root", parameter=None):
    logger.debug("{} {} {} {}".format(tag, "{}/{}".format(directory, def_name), "-->", parameter))


def log_error_format(directory, tag="[ENDPOINT]", def_name="root", parameter=None):
    logger.error("{} {} {} {}".format(tag, "{}/{}".format(directory, def_name), "-->", parameter))


def log_info_format(directory, tag="[ENDPOINT]", def_name="root", parameter=None):
    logger.info("{} {} {} {}".format(tag, "{}/{}".format(directory, def_name), "-->", parameter))


def log_warning_format(directory, tag="[ENDPOINT]", def_name="root", parameter=None):
    logger.warning("{} {} {} {}".format(tag, "{}/{}".format(directory, def_name), "-->", parameter))


def log_text(parameter):
    logger.info(parameter)
