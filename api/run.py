#!/bin/bash

import ssl
from lib.log_format import log_error_format
import config
from app import app
from config import PRODUCTION
from app.production_server import production_mode, production_mode_ssl
from microservice.notification_worker import NotificationScheduler

if __name__ == '__main__':
    try:
        scheduler = NotificationScheduler()
        scheduler.start()
    except Exception as e:
        log_error_format("RUNNER", "MAIN APP", "APPLICATION", f"{e}")

    if PRODUCTION:  # Run FLASK in production mode
        if config.PREFIX_DOMAIN.lower() == "https":
            try:
                production_mode_ssl(app)
            except Exception as e:
                log_error_format("BASE PROJECT", "RUN",
                                 "API RUN",
                                 f"SSL ERROR - > {e}")
        elif config.PREFIX_DOMAIN.lower() == "http":
            production_mode(app)
        else:
            log_error_format("BASE PROJECT", "RUN",
                             "API RUN",
                             f"WRONG PREFIX DOMAIN SETTING")
    else:  # Run FLASK in developing mode
        if config.PREFIX_DOMAIN.lower() == "https":
            context = ssl.SSLContext(ssl.PROTOCOL_TLSv1_2)
            context.load_cert_chain(certfile=config.CERTFILE, keyfile=config.KEYFILE)
            app.run(host=config.ALL_IP, port=config.SERVER_PORT, debug=config.DEBUG, ssl_context=context)
        elif config.PREFIX_DOMAIN.lower() == "http":
            app.run(host=config.ALL_IP, port=config.SERVER_PORT, debug=config.DEBUG)
        else:
            log_error_format("BASE PROJECT", "RUN",
                             "API RUN",
                             f"WRONG PREFIX DOMAIN SETTING")
