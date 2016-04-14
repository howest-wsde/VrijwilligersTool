#!/usr/bin/env python2

# install prerequisites
import os
os.system("sudo apt-get update")
os.system("sudo apt-get install build-essential python-dev libmysqlclient-dev --assume-yes")

os.system("sudo pip install MySQL-python")
os.system("sudo pip install requests")
os.system("sudo pip install elasticsearch")

import MySQLdb
import requests
from elasticsearch import Elasticsearch

mysql_host = "localhost"
mysql_user = "homestead"
mysql_password = "secret"
mysql_database = "homestead"

es_host = "localhost"
es_port = 9200
es_index = mysql_database

#clear es
requests.delete("http://" + es_host + ":" + str(es_port) + "/_all")

es = Elasticsearch(["http://" + es_host + ":" + str(es_port)])

connection = MySQLdb.connect(
    host=mysql_host,
    user=mysql_user,
    passwd=mysql_password)

cursor = connection.cursor()
cursor.execute("USE " + mysql_database)
cursor.execute("SHOW TABLES")
cursor.fetchall()

for (table_name,) in cursor:
    print("==>Working on: " + table_name)

    table_cursor = connection.cursor()
    table_cursor.execute("SHOW columns FROM " + table_name)
    table_columns = [column[0] for column in table_cursor.fetchall()]
    print("Tables: " + str(table_columns))

    table_cursor.execute("SELECT * FROM " + table_name)
    for record in table_cursor.fetchall():
        record_dict = dict()
        id = record[0]
        for i in range(1, len(record)):
            record_dict[table_columns[i]] = record[i]
        res = es.index(index=es_index, doc_type=table_name, id=id, body=record_dict)
        print(res['created'])
    table_cursor.close()

cursor.close()
connection.close()

