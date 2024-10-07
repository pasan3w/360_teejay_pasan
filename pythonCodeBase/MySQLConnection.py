'''
Created on Nov 10, 2023

@author: k2d2
'''

import mysql.connector
from mysql.connector import errorcode

class MySQLConnection(object):
  '''
  classdocs
  '''


  def __init__(self,  user, password, host_name, db_name):
    self.User = user
    self.Password = password
    self.DbHost = host_name
    self.DbName = db_name
    self.connection = None

  def GetConnection(self):
    if (self.connection):
      return self.connection
    try:
      print("Opening connection to db:{} on Host:{}".format(self.DbHost, self.DbHost))
      self.connection = mysql.connector.connect(user=self.User, password=self.Password, 
                                                host=self.DbHost, database=self.DbName,
                                                auth_plugin='mysql_native_password')
    except mysql.connector.Error as err:
      self.connection = None
      if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
        print("DB Access Denied, possible password mismatch, try again")
      elif err.errno == errorcode.ER_BAD_DB_ERROR:
        print("Access Failed: trying to access Invalid database")
      else:
        print("Access Failed:")
        print(err.msg)

    return self.connection
    
  def __del__(self):
    print("Closing db connection")
    self.connection.close()
    self.connection = None
            