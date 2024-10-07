'''
Created on Sep 26, 2023

@author: k2d2
'''

import sys
from db_access import MySQLConnection
from conversion_ops import Branch2Id, JobTitle2Id, Department2Id


def UpdateEmployeeReportingManagers(db_con_obj, employee_id, reporting_managers_str):
  print("************UpdateEmployeeReportingManagers:[start]************")
  if not reporting_managers_str:
    print("ManagerEID:{}: Empty Reporting Manager String".format(employee_id))
    print("************UpdateEmployeeReportingManagers:[end]************")
    return
  con = db_con_obj.GetConnection()
  if not con:
    print("UpdateEmployeeReportingManagers: Failed to open DB Connection")
    sys.exit(2)
  if con.is_connected():
    cursor = con.cursor()
    for rm_id_str in reporting_managers_str.split(','):
      rm_id = rm_id_str.strip()
      rm_data = (employee_id, rm_id)
      check_exists_sql = "SELECT EXISTS(SELECT * FROM Supervisor WHERE EID = %s AND SEID = %s);"
      cursor.execute(check_exists_sql, rm_data)
      result = cursor.fetchone()
      if result[0]:
        print("EID={}, SEID={} already exists in Supervisor Table, No insertion required".format(employee_id, rm_id))
      else:
        print("EID={}, SEID={} does not exist in Supervisor Table, inserting new entry".format(employee_id, rm_id))
        insert_sql = "INSERT INTO Supervisor (EID, SEID) VALUES (%s, %s);"
        cursor.execute(insert_sql, rm_data)
        if cursor.rowcount:
          print("Insertion to Superviosr table successful")
          con.commit()
    cursor.close()       
  print("************UpdateEmployeeReportingManagers:[end]************")


def UpdateEmployeePeerList(db_con_obj, employee_id, peer_list_str):
  print("************UpdateEmployeePeerList:[start]************")
  if not peer_list_str:
    print("ManagerEID:{}: Empty Peer List String".format(employee_id))
    print("************UpdateEmployeePeerList:[end]************")
    return
  con = db_con_obj.GetConnection()
  if not con:
    print("UpdateEmployeePeerList: Failed to open DB Connection")
    sys.exit(2)
  if con.is_connected():
    cursor = con.cursor()
    for peer_id_str in peer_list_str.split(','):
      peer_id = peer_id_str.strip()
      peer_data = (employee_id, peer_id)
      check_exists_sql = "SELECT EXISTS(SELECT * FROM Peers WHERE EID = %s AND PeerID = %s);"
      cursor.execute(check_exists_sql, peer_data)
      result = cursor.fetchone()
      if result[0]:
        print("EID={}, PeerID={} already exists in Peers Table, No insertion required".format(employee_id, peer_id))
      else:
        print("EID={}, PeerID={} does not exist in Supervisor Table, inserting new entry".format(employee_id, peer_id))
        insert_sql = "INSERT INTO Peers (EID, PeerID) VALUES (%s, %s);"
        cursor.execute(insert_sql, peer_data)
        if cursor.rowcount:
          print("Insertion to Peers table successful")
          con.commit()
    cursor.close()       
  print("************UpdateEmployeePeerList:[end]************")


def UpdateEmployeeDirectReports(db_con_obj, employee_id, direct_reports_str):
  print("************UpdateEmployeeDirectReports:[start]************")
  if not direct_reports_str:
    print("ManagerEID:{}: Empty Direct Reports String".format(employee_id))
    print("************UpdateEmployeeDirectReports:[end]************")
    return
  
  con = db_con_obj.GetConnection()
  if not con:
    print("UpdateEmployeeDirectReports: Failed to open DB Connection")
    sys.exit(2)
  if con.is_connected():
    cursor = con.cursor()
    for dr_id_str in direct_reports_str.split(','):
      dr_id = dr_id_str.strip()
      dr_data = (employee_id, dr_id)
      check_exists_sql = "SELECT EXISTS(SELECT * FROM DirectReports WHERE EID = %s AND DirectReportEID = %s);"
      cursor.execute(check_exists_sql, dr_data)
      result = cursor.fetchone()
      if result[0]:
        print("EID={}, DirectReportEID={} already exists in DirectReports Table, No insertion required".format(employee_id, dr_id))
      else:
        print("EID={}, DirectReportEID={} does not exist in Supervisor Table, inserting new entry".format(employee_id, dr_id))
        insert_sql = "INSERT INTO DirectReports (EID, DirectReportEID) VALUES (%s, %s);"
        cursor.execute(insert_sql, dr_data)
        if cursor.rowcount:
          con.commit()
          print("Insertion to DirectReports table successful")
    cursor.close()       
  print("************UpdateEmployeeDirectReports:[end]************")


def AddEmployeeData( db_con_obj, employee_dic):
  print("************AddEmployeeDataToDB:[start]************")
  print("Opening Db Connection")
  con = db_con_obj.GetConnection()
  if not con:
    print("AddEmployeeDataToDB: Failed to open DB Connection")
    sys.exit(2)
  if con.is_connected():
    cursor = con.cursor()
    for employee_id in employee_dic.keys():
      print("--------Adding employee_id:{}: Name{} to db--------".format(employee_id, employee_dic[employee_id]["Name"]))
      branch_id = Branch2Id(db_con_obj, employee_dic[employee_id]["Branch"])
      department_id = Department2Id(db_con_obj, employee_dic[employee_id]["Department"])
      job_title_id = JobTitle2Id(db_con_obj, employee_dic[employee_id]["JobTitle"])
      employee_data = (employee_id, employee_dic[employee_id]["Name"], branch_id, department_id,
                       job_title_id, employee_dic[employee_id]["PhoneNumber"],
                       employee_dic[employee_id]["EmailAddress"]
                       )
      sql = """
      INSERT INTO Employee (EID, Name, BranchID, DepartmentID, JobTitleID, PhoneNumber, 
      Email) VALUES(%s, %s, %s, %s, %s, %s, %s)       
      """
      cursor.execute(sql, employee_data)
      if cursor.rowcount:
        print("Successfully added employee id:{}:{} to db".format(employee_id, employee_dic[employee_id]["Name"]))
        con.commit()
    cursor.close()     
    
    print("Updating Immediate Reporting Manager Data for Employees")
    for employee_id in employee_dic.keys():
      UpdateEmployeeReportingManagers(db_con_obj, employee_id, employee_dic[employee_id]["ReportingManagerEid"])

  print("************AddEmployeeDataToDB:[end]************")    
      


def AddManagerData(db_con_obj, managers_dic):
  print("************AddManagerDataToDb:[start]************")
  for manager_eid in managers_dic.keys():
    print("--------Processing: ManagerEID={}, Name={}--------".format(manager_eid, managers_dic[manager_eid]['Name']))
    job_roles = managers_dic[manager_eid]["job_roles"].keys()
    for job_role in job_roles:
      print("JobRole=", job_role)
      print("\tReportingManagers=", managers_dic[manager_eid]["job_roles"][job_role]["ReportingManagers"])
      UpdateEmployeeReportingManagers(db_con_obj, manager_eid, managers_dic[manager_eid]["job_roles"][job_role]["ReportingManagers"])
      print("\tDirectReports=", managers_dic[manager_eid]["job_roles"][job_role]["DirectReports"])
      UpdateEmployeeDirectReports(db_con_obj, manager_eid, managers_dic[manager_eid]["job_roles"][job_role]["DirectReports"])
      print("\tPeerList=", managers_dic[manager_eid]["job_roles"][job_role]["PeerList"])
      UpdateEmployeePeerList(db_con_obj, manager_eid, managers_dic[manager_eid]["job_roles"][job_role]["PeerList"])
  print("************AddManagerDataToDb:[end]************")      
