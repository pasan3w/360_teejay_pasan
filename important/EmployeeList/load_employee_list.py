#!/usr/bin/python3
'''
Created on July 23, 2023

@author: k2d2
'''

import sys
import getopt
from collections import OrderedDict
from getpass import getpass
from _ast import arg
import app_config
import excel_parser
from db_access import MySQLConnection
import db_operations

  
def Usage():
  print('Usage: load_employee_list.py [--help] [-h|--host <host_name>] [-d|--db <db_name>] [-f|--fix] [-c|--conf <appconfig_file>] <employee_excel_file>')
    
                
if __name__ == "__main__":
  employee_list_file = None
  appconfigfile = 'load_employee_list.conf'
  db_host= '50.87.232.129'
  db_name= 'thrwcons_hayleys_360'

  try:
    opts, args = getopt.getopt(sys.argv[1:],"h:d:fc:", ["help", "fix", "host=", "db=","conf="])
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
  print("Loading employee list from file:", employee_list_file)
  employee_dic = OrderedDict()
  managers_dic = OrderedDict()
  excel_parser.LoadEmployeeData(employee_list_file, AppConfig, employee_dic)
  employee_count= len(employee_dic)
  if (0 == employee_count):
    print("The number of employees loaded is zero, please verify the employee input file content")
    sys.exit(1)
  else:
    print("The number of employee recors loaded from file=", employee_count)
  excel_parser.LoadManagerData(employee_list_file, AppConfig, employee_dic, managers_dic)
  manager_count = len(managers_dic)
  if (0 == manager_count):
    print("Warning: The number of managers loaded is zero, please verify the manager input file content")
  else:
    print("The number of manger records loaded from file=", manager_count)
  excel_parser.PrintManagersDic(managers_dic)
  mysql_con_obj = MySQLConnection(db_user, db_password, db_host, db_name)
  db_operations.AddEmployeeData(mysql_con_obj, employee_dic)
  db_operations.AddManagerData(mysql_con_obj, managers_dic)

 
  


