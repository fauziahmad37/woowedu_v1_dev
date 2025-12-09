from art import *
# SET YOUR PROJECT PROPERTIES HERE
project_name = 'WOOWEDU-FCM-API'
software_type = 'MICROSERVICE'
project_version = {'major': 1, 'minor': 2, 'month': 6, 'revision': 1}
copyright_year = 2025
client = 'Internal'
maintainer = 'GOBAY'
company_author = 'HIT Corporation'

# VERSION PROPERTIES VISIBILITY CONFIG (YOU CAN USE DATETIME TO AUTOMATICALLY SHOW/HIDE THIS CONFIG BASED ON TIME)
show_maintainer = True
show_company_author = True
show_codename = True
show_terminal_banner = True

# TERMINAL BANNER
terminal_banner = text2art(project_name) # noqa

# NO NEED TO CHANGE CODE BELOW
codename = {1: "Journey", 2: "Flame", 3: "Maverick", 4: "Aurora", 5: "Mirage", 6: "Jester",
            7: "Jupiter", 8: "Amber", 9: "Serenade", 10: "Odyssey", 11: "Noir", 12: "Dreamer"}
pv = project_version
friendly_ver = f"{pv['major']}.{pv['minor']}.{pv['month']} {'REV.'+str(pv['revision']) if pv['revision'] else ''}"
LINE1 = f"{project_name} {software_type}".upper()
LINE2 = f"VERSION {friendly_ver}{' CODENAME '+codename[project_version['month']] if show_codename else ''}" \
        f"{' (MAINTAINER:'+maintainer+')' if show_maintainer else ''}".upper()
LINE3 = f"COPYRIGHT © {copyright_year} {client}" \
        f"{', POWERED BY ' + company_author + '.' if show_company_author else '.'}".upper()
combine_line = ""

def initialize():
    global combine_line
    try:
        def on_exit():
            import time
            print(f"\n\033[1m\033[91m  <<STOPPING {LINE1}>>  \033[0m", end='')
            time.sleep(1)
        import os
        import atexit
        atexit.register(on_exit)
        combine_line = f'{terminal_banner}\n * {LINE1}\n * {LINE2}\n * {LINE3}'
        if show_terminal_banner:
            print("\033[1m\033[92m"+terminal_banner)
        print("\033[1m\033[92m" + combine_line.replace(terminal_banner, "") + "\033[0m")
        os.system('title ' + LINE1)
    except Exception as e:
        print(e)
