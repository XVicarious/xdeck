import urllib.request
import xml
import MySQLdb
import traceback
import warnings
import xmltodict

def download_tokens():
    url = 'https://raw.githubusercontent.com/Cockatrice/Magic-Token/master/tokens.xml'
    urllib.request.urlretrieve(url, 'tokens.xml')

def load_tokens():
    raw_xml = open('tokens.xml', encoding='utf-8')
    data = xmltodict.parse(raw_xml.read().encode())
    data = data['cockatrice_carddatabase']['cards']['card']
    for tkn in data:
        if 'pt' in tkn:
            tkn['power'],tkn['toughness'] = tkn['pt'].split('/')
    raw_xml.close()
    return data

def quit(conn):
    conn.commit()
    conn.close()
    exit()

def sql_connect():
    my_file = open('sql-info.cfg','r')
    hostname = my_file.readline().strip()
    username = my_file.readline().strip()
    password = my_file.readline().strip()
    database = my_file.readline().strip()
    my_file.close()
    conn = MySQLdb.connect(host=hostname,user=username,passwd=password,db=database,charset='utf8')
    cursor = conn.cursor()
    try:
        cursor.execute('use ' + database + ';')
    except pymysql.err.InternalError:
        cursor.execute('CREATE DATABASE ' + database + ';')
        cursor.execute('use ' + database + ';')
    return conn, cursor

def parseList(l):
    s = ''
    for item in l:
        if s != '':
            s += ','
        s += str(item)
    return s

def parseTokens(data, cursor):
    parsed_cards = []
    for tkn in data:
        try:
            quote = '"'
            if 'text' in tkn:
                if tkn['text'] is None:
                    tkn['text'] = ""
                else:
                    tkn['text'] = tkn['text'].replace('"', '\\"')
            else:
                tkn['text'] = ""
            sql_command = 'INSERT INTO card_cardtoken (name, colors, type, text, power, toughness) VALUES ("%s", %s, "%s", %s, "%s", "%s");'%(
                tkn['name'],
                ('"' + parseList(tkn['color']) + '"') if 'color' in tkn else "NULL",
                tkn['type'],
                quote + tkn['text'] + quote if 'text' in tkn else "NULL",
                tkn['power'] if 'power' in tkn else "NULL",
                tkn['toughness'] if 'toughness' in tkn else "NULL",
            )
            cursor.execute(sql_command)
            if 'reverse-related' in tkn:
                li = cursor.lastrowid
                for related in tkn['reverse-related']:
                    if not isinstance(tkn['reverse-related'], list):
                        related = tkn['reverse-related']
                    if related == 'Beck // Call':
                        related = 'Call'
                    elif related == 'Alive // Well':
                        related = 'Alive'
                    elif related == 'Research // Development':
                        related = 'Development'
                    elif related == 'Assault // Battery':
                        related = 'Battery'
                    elif related == 'Mouth // Feed':
                        related = 'Mouth'
                    elif related == 'Supply // Demand':
                        related = 'Supply'
                    elif related == 'Start // Finish':
                        related = 'Start'
                    elif related == 'Never // Return':
                        related = 'Return'
                    elif related == 'Insect' or related == 'Snake': #This is a token itself
                        related = 'Peek' # Temp set this to peek, I'll fix it myself
                    sql_command = 'INSERT INTO card_cardtotoken (card_id, token_id) VALUES ((SELECT id FROM card_card WHERE cardName LIKE "%s"), %s);'%(
                        related,
                        li,
                    )
                    cursor.execute(sql_command)
                    if not isinstance(tkn['reverse-related'], list):
                        break
        except Exception as e:
            print(tkn)
            print(sql_command)
            print('Error -->', e)
            traceback.print_exc()
            exit()

download_tokens()
data = load_tokens()
conn,cursor = sql_connect()
parseTokens(data, cursor)
quit(conn)
