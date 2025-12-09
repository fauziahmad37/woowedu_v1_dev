from waitress import serve
import config
import ssl


def production_mode(flask_module):
    print(f"\033[92m\033[1m * API is running on {config.ALL_IP}:{config.SERVER_PORT}\033[0m")
    serve(flask_module, host=config.ALL_IP, port=config.SERVER_PORT)


def production_mode_ssl(flask_module):
    print(f"\033[92m\033[1m * API is running on {config.ALL_IP}:{config.SERVER_PORT} with SSL\033[0m")

    # Path to your SSL certificate and key
    certfile = config.CERTFILE
    keyfile = config.KEYFILE

    # Create SSL context
    ssl_context = ssl.SSLContext(ssl.PROTOCOL_TLSv1_2)
    ssl_context.load_cert_chain(certfile, keyfile)

    # Serve with SSL context
    serve(flask_module, host=config.ALL_IP, port=config.SERVER_PORT,
          url_scheme='https')
