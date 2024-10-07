'''
Created on Sep 22, 2023

@author: k2d2
'''

import sys
import getopt
from collections import OrderedDict
from getpass import getpass
from _ast import arg
import app_config
import excel_parser
import mysql.connector
from db_access import MySQLConnection
import db_operations



def EmptyEmployeeAttributeTables(db_con_obj):
  print("************EmptyEmployeeAttributeTables:[start]************")
  con = db_con_obj.GetConnection()
  if not con or not con.is_connected():
    print("EmptyEmployeeAttributeTables: Failed to open DB Connection")
    sys.exit(2)
  cursor = con.cursor()
  for table_name in ("Department", "Branch", "JobTitle"):
    print("Deleting  All Data from Table:", table_name)
    sql = "TRUNCATE TABLE {}".format(table_name)
    print("Executing SQL statement:[{}]".format(sql))
    try:          
      cursor.execute(sql)
      sql = "SELECT COUNT(*) FROM {}".format(table_name)
      print("Row count SQL-[{}]".format(sql))
      print("Counting rows left in table")
      cursor.execute(sql)
      result = cursor.fetchone()
      print("Number of rows left in table={} is {}".format(table_name, result[0]))
      cursor.fetchall()
    except mysql.connector.Error as err:
      print("Failed to execute SQL statement: {}".format(sql))
      print(err.msg)
  print("************EmptyEmployeeAttributeTables:[end]************")


def EmptyEmployeeHierarchyTables(db_con_obj):
  print("************EmptyEmployeeHierarchyTables:[start]************")  
  con = db_con_obj.GetConnection()
  if not con or not con.is_connected():
    print("EmptyEmployeeHierarchyTables: Failed to open DB Connection")
    sys.exit(2)
  cursor = con.cursor()
  for table_name in ("Supervisor", "Peers", "DirectReports"):
    print("Deleting  All Data from Table:", table_name)
    sql = "TRUNCATE TABLE {}".format(table_name)
    print("Executing SQL statement:[{}]".format(sql))
    try:          
      cursor.execute(sql)
      sql = "SELECT COUNT(*) FROM {}".format(table_name)
      print("Row count SQL-[{}]".format(sql))
      print("Counting rows left in table")
      cursor.execute(sql)
      result = cursor.fetchone()
      print("Number of rows left in table={} is {}".format(table_name, result[0]))
      cursor.fetchall()
    except mysql.connector.Error as err:
      print("Failed to execute SQL statement: {}".format(sql))
      print(err.msg)
  print("************EmptyEmployeeHierarchyTables:[end]************")  


def EmptyTable(db_con_obj, table_name):
  print("************EmptyTable:[start]************")  
  con = db_con_obj.GetConnection()
  if not con or not con.is_connected():
    print("EmptyTable: Failed to open DB Connection")
    sys.exit(2)
  cursor = con.cursor()
  print("Deleting  All Data from Table:", table_name)
  sql = "TRUNCATE TABLE {}".format(table_name)
  print("Executing SQL statement:[{}]".format(sql))
  try:          
    cursor.execute(sql)
    sql = "SELECT COUNT(*) FROM {}".format(table_name)
    print("Row count SQL-[{}]".format(sql))
    print("Counting rows left in table")
    cursor.execute(sql)
    result = cursor.fetchone()
    print("Number of rows left in table={} is {}".format(table_name, result[0]))
    cursor.fetchall()
  except mysql.connector.Error as err:
    print("Failed to execute SQL statement: {}".format(sql))
    print(err.msg)
  print("************EmptyTable:[end]************")  


def DisableDatabseForeignKeyChecks(db_con_obj):
  print("************DisableDatabseForeignKeyChecks:[start]************")  
  con = db_con_obj.GetConnection()
  if not con or not con.is_connected():
    print("DisableDatabseForeignKeyChecks: Failed to open DB Connection")
    sys.exit(2)
  cursor = con.cursor()
  try:
    print("Disabling Database Foreign Key checks")
    cursor.execute("SET FOREIGN_KEY_CHECKS = 0")
    print("Successfully Disabled Database Foreign Key checks")
  except mysql.connector.Error as err:
    print("Failed to execute statement: SET FOREIGN_KEY_CHECKS = 0")
    print(err.msg)
  print("************DisableDatabseForeignKeyChecks:[end]************")  

    
def EnableDatabseForeignKeyChecks(db_con_obj):
  print("************EnableDatabseForeignKeyChecks:[start]************")  
  con = db_con_obj.GetConnection()
  if not con or not con.is_connected():
    print("EnableDatabseForeignKeyChecks: Failed to open DB Connection")
    sys.exit(2)
  cursor = con.cursor()
  try:
    print("Enabling Database Foreign Key checks")
    cursor.execute("SET FOREIGN_KEY_CHECKS = 1")
    print("Successfully Enabled Foreign Key checks")
  except mysql.connector.Error as err:
    print("Failed to execute statement: SET FOREIGN_KEY_CHECKS = 1")
    print(err.msg)
  print("************EnableDatabseForeignKeyChecks:[end]************")  

 
def Usage():
  print('Usage: update_employee_list.py [--help] [-h|--host <host_name>] [-d|--db <db_name>] [-f|--fix] [-c|--conf <appconfig_file>] <employee_excel_file>')
    
                
if __name__ == "__main__":
  employee_list_file = None
  appconfigfile = 'update_employee_list.conf'
  db_host= 'localhost'
  db_name= '360_survey_schema'

  try:
    opts, args = getopt.getopt(sys.argv[1:],"h:d:c:", ["help", "host=", "db=","conf="])
  except getopt.GetoptError:
    Usage()
    sys.exit(2)
  print("args=", args)
  print("Number of args=", len(args))
  print("options=", opts)
  print("Number of opts=", len(opts))
  for opt, arg in opts:
    if opt in ('--help'):
      Usage()
      sys.exit(0)
    elif opt in ('-c', '--conf'):
      appconfigfile = arg
      print("Config file specified:", appconfigfile)
    elif opt in ('h', '--host'):
      db_host = arg
    elif opt in ('-d', '--db'):
      db_name = arg
  if (len(args) < 1):
    print("Error: Employee List Excel Filename argument is  mandatory")
    Usage()
    sys.exit(2)
  employee_list_file = args[0]
  db_user=input("DB User:")
  db_password=getpass("DB Password:")
  print("Db Host={}, Db Name={}, Db User={}, Db Password={}".format(db_host, db_name,
                                                                    db_user, db_password))  
  print("Loading Application configuration from file: ", appconfigfile)
  AppConfig = app_config.ReadConfig(appconfigfile)
  app_config.PrintConfig(AppConfig)
  print("Emptying Employee data from database")
  mysql_con_obj = MySQLConnection(db_user, db_password, db_host, db_name)
  DisableDatabseForeignKeyChecks(mysql_con_obj)
  EmptyEmployeeAttributeTables(mysql_con_obj)
  EmptyEmployeeHierarchyTables(mysql_con_obj)
  EmptyTable(mysql_con_obj, 'Employee')
  EnableDatabseForeignKeyChecks(mysql_con_obj)
  print("Uploading employee list from file:", employee_list_file)
  employee_dic = OrderedDict()
  managers_dic = OrderedDict()
  excel_parser.LoadEmployeeData(employee_list_file, AppConfig, employee_dic)
  employee_count= len(employee_dic)
  if (0 == employee_count):
    print("The number of employees loaded is zero, please verify the employee input file content")
    sys.exit(1)
  else:
    print("The number of employee records loaded from file=", employee_count)
  excel_parser.LoadManagerData(employee_list_file, AppConfig, employee_dic, managers_dic)
  manager_count = len(managers_dic)
  if (0 == manager_count):
    print("Warning: The number of managers loaded is zero, please verify the manager input file content")
  else:
    print("The number of manger records loaded from file=", manager_count)
  excel_parser.PrintManagersDic(managers_dic)
  db_operations.AddEmployeeData(mysql_con_obj, employee_dic)
  db_operations.AddManagerData(mysql_con_obj, managers_dic)
