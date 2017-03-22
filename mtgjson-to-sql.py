
import urllib.request
import json
import pymysql
import traceback
import warnings

def download_mtgjson():
  url = 'http://mtgjson.com/json/AllSets-x.json'
  urllib.request.urlretrieve(url, 'AllSets-x.json')

def load_mtgjson():
  raw_json = open('AllSets-x.json',encoding='utf-8')
  data = json.load(raw_json)
  raw_json.close()
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
  my_file.close()

  conn = pymysql.connect(host=hostname,user=username,passwd=password,use_unicode=True,charset='utf8')
  cursor = conn.cursor()

  try:
    cursor.execute('use mtg_card_db;')
  except pymysql.err.InternalError:
    cursor.execute('CREATE DATABASE mtg_card_db;');
    cursor.execute('use mtg_card_db;')

  return conn, cursor

def drop_sql_tables(cursor):
  try:
    cursor.execute('DROP TABLE card_set_info;');
  except:
    pass
  try:
    cursor.execute('DROP TABLE sets;');
  except:
    pass
  try:
    cursor.execute('DROP TABLE cards;');
  except:
    pass
  #print('Tables dropped')

def padDate(date):
  while(len(date)<10):
    date += '-' if len(date) in [4,7] else '0' if len(date) in [5,8] else '1'
  #print(date)
  return '"' + date + '"'

def setup_sql_db(cursor):
  cursor.execute('SHOW TABLES IN mtg_card_db;');

  current_tables = cursor.fetchall()
  #print(current_tables)
  if ('sets',) not in current_tables:
    cursor.execute("CREATE TABLE sets ("+
    "id INT PRIMARY KEY AUTO_INCREMENT,"+
    " setName varchar(255),"+
    "code varchar(8),"+
    "setType varchar(16),"+
    "block varchar(255),"+
    "onlineOnly BIT)")
    #"PRIMARY KEY (id),"+
    #"UNIQUE INDEX id_UNIQUE (id ASC));")
  if ('cards',) not in current_tables:
    cursor.execute("CREATE TABLE cards ("+
    "id INT PRIMARY KEY AUTO_INCREMENT,"+
    " layout varchar(255),"+
    " cardName varchar(255),"+
    " otherName varchar(255),"+
    " manaCost varchar(255),"+
    " cmc INT UNSIGNED,"+
    " colors SET('White','Blue','Black','Red','Green'),"+
    " type varchar(255),"+
    " text TEXT,"+
    " power varchar(255),"+
    " toughness varchar(255),"+
    " loyalty TINYINT UNSIGNED,"+
    " hand TINYINT,"+
    " life TINYINT,"+
    " reserved BIT,"+
    " vintage SET('Banned','Restricted','Legal'),"+
    " legacy SET('Banned','Restricted','Legal'),"+
    " modern SET('Banned','Restricted','Legal'),"+
    " standard SET('Banned','Restricted','Legal'),"+
    " commander SET('Banned','Restricted','Legal'))")
    #"PRIMARY KEY (id),"+
    #"UNIQUE INDEX id_UNIQUE (id ASC));")
  if ('card_set_info',) not in current_tables:
    cursor.execute("CREATE TABLE card_set_info ("+
    "_cardId INT,"+
    " _setId INT,"+
    " rarity varchar(32),"+
    " flavor TEXT,"+
    " artist varchar(255),"+
    " number varchar(32),"+
    " multiverseid INT UNSIGNED,"+
    " variations varchar(255),"+
    " watermark varchar(255),"+
    " border varchar(16),"+
    " timeshifted BIT,"+
    " releaseDate DATE,"+
    " starter BIT,"+
    " originalText TEXT,"+
    " originalType varchar(255),"+
    " source varchar(255),"+
    " imageName varchar(255),"+
    " CONSTRAINT cardID PRIMARY KEY(_cardId, _setId, imageName),"+
    " FOREIGN KEY (_cardId) REFERENCES cards(id),"+
    " FOREIGN KEY (_setId) REFERENCES sets(id) );")

def parseSets(data, cursor):
  for key in list(data.keys()):
    this_set = data[key]
    sql_command = 'INSERT INTO sets (setName, code, setType, block, onlineOnly) VALUES ("%s", "%s", "%s", %s, %s);'%(
        this_set['name'].replace('"','\\"'),
        this_set['code'],
        this_set['type'],
        '"' + this_set['block'] + '"' if 'block' in this_set.keys() else 'NULL',
        this_set['onlineOnly'] if 'onlineOnly' in this_set.keys() else 'NULL')
    #print(sql_command)
    cursor.execute(sql_command)

def legalIn(card,_format):
  if 'legalities' not in card.keys():
    return "NULL"
  for item in card['legalities']:
    if item['format'] == _format:
      return '"' + item['legality'] + '"'
  return "NULL"

def parseList(l):
  s = ''
  for item in l:
    if s != '':
      s += ','
    s += str(item)
  return s

def parseCards(data, cursor):
  parsed_cards = []
  for _set in list(data.values()):
    for card in _set['cards']:
      try:
        if card['name'] not in parsed_cards:
          sql_command = 'INSERT INTO cards (layout, cardName, otherName, manaCost, cmc, colors, type, text, power, toughness, loyalty, hand, life, reserved, vintage, legacy, modern, standard, commander) VALUES ("%s", "%s", %s, %s, %s, %s, "%s", %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);'%(
            card['layout'],
            card['name'].replace('"','\\"'),
            '"' + parseList(card['names']) + '"' if 'names' in card.keys() else "NULL",
            '"' + card['manaCost'] + '"' if 'manaCost' in card.keys() else "NULL",
            card['cmc'] if 'cmc' in card.keys() else 0,
            '"' + parseList(card['colors']) + '"' if 'colors' in card.keys() else "NULL",
            card['type'],
            '"' + card['text'].replace('"','\\"') + '"' if 'text' in card.keys() else "NULL",
            '"' + card['power'] + '"' if 'power' in card.keys() else "NULL",
            '"' + card['toughness'] + '"' if 'toughness' in card.keys() else "NULL",
            card['loyalty'] if 'loyalty' in card.keys() else "NULL",
            card['hand'] if 'hand' in card.keys() else "NULL",
            card['life'] if 'life' in card.keys() else "NULL",
            card['reserved'] if 'reserved' in card.keys() else "NULL",
            legalIn(card,'Vintage'),
            legalIn(card,'Legacy'),
            legalIn(card,'Modern'),
            legalIn(card,'Standard'),
            legalIn(card,'Commander'),)
          cursor.execute(sql_command)
          parsed_cards += [card['name']]
        sql_command = 'INSERT INTO card_set_info VALUES ("%s", "%s", "%s", %s, "%s", %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, "%s");'%(
          card['name'].replace('"','\\"'),
          _set['name'].replace('"','\\"'),
          card['rarity'],
          '"' + card['flavor'].replace('"','\\"') + '"' if 'flavor' in card.keys() else "NULL",
          card['artist'],
          '"' + card['number'] + '"'  if 'number' in card.keys() else "NULL",
          card['multiverseid'] if 'multiverseid' in card.keys() else "NULL",
          '"' + parseList(card['variations']) + '"'  if 'variations' in card.keys() else "NULL",
          '"' + card['watermark'] + '"'  if 'watermark' in card.keys() else "NULL",
          '"' + card['border'] + '"'  if 'border' in card.keys() else '"' + _set['border'] + '"',
          card['timeshifted'] if 'timeshifted' in card.keys() else "NULL",
          padDate(card['releaseDate']) if 'releaseDate' in card.keys() else padDate(_set['releaseDate']),
          card['starter'] if 'starter' in card.keys() else "NULL",
          '"' + card['originalText'].replace('"','\\"') + '"'  if 'originalText' in card.keys() else "NULL",
          '"' + card['originalType'] + '"'  if 'originalType' in card.keys() else "NULL",
          '"' + card['source'] + '"'  if 'source' in card.keys() else "NULL",
          card['imageName'])
        #cursor.execute(sql_command)
      except Exception as e:
        print(card)
        print('Error -->',e)
        traceback.print_exc()
        exit()

download_mtgjson()
#warnings.filterwarnings('error')
conn, cursor = sql_connect()
data = load_mtgjson()
drop_sql_tables(cursor);
setup_sql_db(cursor)
parseSets(data, cursor)
parseCards(data, cursor)
quit(conn)
