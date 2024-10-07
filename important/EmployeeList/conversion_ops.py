'''
Created on Sep 22, 2023

@author: k2d2
'''

import db_access
import mysql.connector
from mysql.connector import errorcode


def Branch2Id(con_obj, branch_name):
  print("************Branch2Id[Start]************")
  print("BranchName=[{}]".format(branch_name))
  branch_id = None
  con = con_obj.GetConnection()
  if (con.is_connected()):
    cursor = con.cursor()
    sql = "SELECT BranchID FROM Branch WHERE BranchName = %s;"
    cursor.execute(sql, (branch_name,))
    result = cursor.fetchone()
    if result:
      branch_id = result[0]
    else:
      print("BranchName:{} does not exist in Branch table, Updating table".format(branch_name))
      sql = "INSERT INTO Branch (BranchName) VALUES (%s);"
      cursor.execute(sql, (branch_name,))
      if cursor.rowcount:
        print("Successfully added BranchName:{} to Branch Table".format(branch_name))
        branch_id = cursor.lastrowid
        print("Inserted:BranchName={}, Generated BranchId = {}".format(branch_name, branch_id))
        con.commit()
      else:
        print("Failed to insert BranchName={} in to Branch table".format(branch_name))
    cursor.close()
  print("************Branch2Id[End]************")
  return branch_id


def Department2Id(con_obj, department_name):
  print("************Department2Id[Start]************")
  print("DepartmentName=[{}]".format(department_name))
  department_id = None
  con = con_obj.GetConnection()
  if (con.is_connected()):
    cursor = con.cursor()
    sql = "SELECT DepartmentID FROM Department WHERE DepartmentName = %s;"
    cursor.execute(sql, (department_name,))
    result = cursor.fetchone()
    if result:
      department_id = result[0]
    else:
      print("DepartmentName:{} does not exist in Department table, Updating table".format(department_name))
      sql = "INSERT INTO Department (DepartmentName) VALUES (%s);"
      cursor.execute(sql, (department_name,))
      if cursor.rowcount:
        print("Successfully added DepartmentName:{} to Department Table".format(department_name))
        department_id = cursor.lastrowid
        print("Inserted:DepartmentName={}, Generated DepartmentID = {}".format(department_name, department_id))
        con.commit()
      else:
        print("Failed to insert DepartmentName={} in to Department table".format(department_name))
    cursor.close()
  print("************Department2Id[End]************")
  return department_id


def JobTitle2Id(con_obj, job_title_name):
  print("************JobTitle2Id[Start]************")
  print("JobTitleName=[{}]".format(job_title_name))
  job_title_id = None
  con = con_obj.GetConnection()
  if (con.is_connected()):
    cursor = con.cursor()
    sql = "SELECT JobTitleID FROM JobTitle WHERE JobTitleName = %s;"
    cursor.execute(sql, (job_title_name,))
    result = cursor.fetchone()
    if result:
      job_title_id = result[0]
    else:
      print("JobTitleName:{} does not exist in JobTitle table, Updating table".format(job_title_name))
      sql = "INSERT INTO JobTitle (JobTitleName) VALUES (%s);"
      cursor.execute(sql, (job_title_name,))
      if cursor.rowcount:
        print("Successfully added JobTitleName:{} to JobTitle Table".format(job_title_name))
        job_title_id = cursor.lastrowid
        print("Inserted:JobTitleName={}, Generated JobTitleID = {}".format(job_title_name, job_title_id))
        con.commit()
      else:
        print("Failed to insert JobTitleName={} in to JobTitle table".format(job_title_name))
    cursor.close()
  print("************JobTitle2Id[End]************")
  return job_title_id


def BranchId2Name(con_obj, branch_id):
  print("************BranchId2BranchName[Start]************")
  print("BranchId=[{}]".format(branch_id))
  branch_name = ""
  con = con_obj.GetConnection()
  if (con.is_connected()):
    cursor = con.cursor()
    sql = "SELECT BranchName FROM Branch WHERE BranchID = %s;"
    cursor.execute(sql, (branch_id,))
    result = cursor.fetchone()
    if result:
      branch_name = result[0]
    else:
      print("Warning: BrancID:{} does not exist in Branch table".format(branch_id))
    cursor.close()
  print("************BranchId2BranchName[End]************")
  return branch_name


def DepartmentId2DepartmentName(con_obj, department_id):
  print("************DepartmentId2DepartmentName[Start]************")
  print("DepartmentId=[{}]".format(department_id))
  department_name = ""
  con = con_obj.GetConnection()
  if (con.is_connected()):
    cursor = con.cursor()
    sql = "SELECT DepartmentName FROM Department WHERE DepartmentID = %s;"
    cursor.execute(sql, (department_id,))
    result = cursor.fetchone()
    if result:
      department_name = result[0]
    else:
      print("Warning: DepartmentID:{} does not exist in Department table".format(department_id))
    cursor.close()
  print("************DepartmentId2DepartmentName[End]************")
  return department_name 


def JobTitleId2JobTitleName(con_obj, job_title_id):
  print("************JobTitleId2JobTitleName[Start]************")
  print("JobTitleId=[{}]".format(job_title_id))
  job_title_name = ""
  con = con_obj.GetConnection()
  if (con.is_connected()):
    cursor = con.cursor()
    sql = "SELECT JobTitleName FROM JobTitle WHERE JobTitleID = %s;"
    cursor.execute(sql, (job_title_id,))
    result = cursor.fetchone()
    if result:
      job_title_name = result[0]
    else:
      print("Warning: JobTitleID:{} does not exist in JobTitle table".format(job_title_id))
    cursor.close()
  print("************JobTitleId2JobTitleName[End]************")
  return job_title_name 


