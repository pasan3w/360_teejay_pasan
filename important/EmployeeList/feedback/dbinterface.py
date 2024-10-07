'''
Created on Dec 21, 2023

@author: k2d2
'''

import sys
import os
from collections import OrderedDict
sys.path.append(os.path.join(os.path.dirname(os.getcwd()), '360Survey/Db'))
print(sys.path)
from MySQLConnection import MySQLConnection
import mysql.connector
from reportutils import *


def GetOpenAssignmnetCountForEmployeeInSurvey(db_con_obj, survey_id, eid):
  print("-------------------GetOpenAssignmnetCountForEmployeeInSurvey:[start]------------------------\n");
  print(f"Looking for open assignments in survey={survey_id} to evaluate employee=[{eid}] typeofeid={type(eid)}\n")
  open_assignments = 1

  con = db_con_obj.GetConnection()
  if not con:
    print("GetOpenAssignmnetCountForEmployeeInSurvey: Failed to open DB Connection")
    sys.exit(2)
  if con.is_connected():
    cursor = con.cursor()
    #sql = "SELECT COUNT(*) FROM SurveyAssignment WHERE SurveyID=%d AND ResponseDate IS NULL AND EID=%s";
    sql = "SELECT COUNT(*) FROM SurveyAssignment WHERE SurveyID={} AND EID='{}' AND ISNULL(ResponseDate)".format(survey_id, eid)
    #sql = "SELECT COUNT(*) FROM SurveyAssignment WHERE (ResponseDate IS NULL) AND SurveyID={} AND EID={}".format(survey_id, eid)
    #sql = "SELECT COUNT(*) FROM SurveyAssignment WHERE ISNULL(ResponseDate) AND SurveyID={}".format(survey_id)
    #sql = "SELECT SUM(ISNULL(ResponseDate)) from SurveyAssignment where SurveyID=100 AND EID='OS 01'"
    #lookup_params = (survey_id, eid)
    print("using new count")
    try:      #cursor.execute(sql, lookup_params)
      cursor.execute(sql)
      print(f"Number of rows returned by cursor is={cursor.rowcount}\n")
      row = cursor.fetchone()
      
      #open_assignments = cursor.rowcount
      open_assignments = row[0]
      print(f"Total sum returned={open_assignments}\n")
      if open_assignments:
        print(f"Number of open Assignments for Survey={survey_id} of Employee={eid} is={open_assignments}\n")
        #cursor.fetchall()
      else:
        print("No open Assignments for this user\n")
    except (mysql.connector.Error) as e:
      print(f"GetOpenAssignmnetCountForEmployeeInSurvey: Failed due to exception:{e}\n")
    finally:
      print("closing cursor\n")
      cursor.close()
  print("-------------------GetOpenAssignmnetCountForEmployeeInSurvey:[end]------------------------\n")
  #Error conditions return 1
  print(f"Returning Open Assignment count={open_assignments}")
  return open_assignments


def GetQuestionListIdOfSurvey(db_con_obj, survey_id):
  print("-------------------GetQuestionListIdOfSurvey:[start]------------------------\n")
  question_list_id = 0
  con = db_con_obj.GetConnection()
  try:
    survey_state_sql = f"SELECT QuestionaireID FROM Survey WHERE SurveyID = {survey_id}"
    cursor = con.cursor()
    cursor.execute(survey_state_sql)
    result_row = cursor.fetchone()
    if (result_row):
      question_list_id = result_row[0];
      print(f"Survey SurveyID={survey_id}, QuestionaireID={question_list_id}")
  except (mysql.connector.Error, mysql.connector.Warning) as e:
    print(f"GetQuestionListIdOfSurvey: Failed due to exception:{e}\n")
  print("-------------------GetQuestionListIdOfSurvey:[start]------------------------\n")
  return question_list_id
  

def DepartmentId2DepartmentName(db_con_obj, department_id):
  print("-------------------DepartmentId2DepartmentName:[start]------------------------\n");
  department_name = None
  con = db_con_obj.GetConnection()
  if not con:
    print("DepartmentId2DepartmentName: Failed to open DB Connection")
    sys.exit(2)
  if not con.is_connected():
    print("Database is not connected\n")
    sys.exit(2)
  sql= f"SELECT DepartmentName FROM Department WHERE DepartmentID={department_id}"
  try:
    cursor = con.cursor()
    cursor.execute(sql)
    row = cursor.fetchone()
    if row:
      print(f"The DepartmentName of DepartmentId={department_id} is={row[0]}\n")
      department_name = row[0]
  except (mysql.connector.Error, mysql.connector.Warning) as e:
    print(f"DepartmentId2DepartmentName: Failed due to exception:{e}\n")
  finally:
    cursor.close()  
  print("-------------------DepartmentId2DepartmentName:[end]------------------------\n");
  return department_name


def GetQuestionaireDetailsFromQuestionListId(db_con_obj, question_list_id, questionaire_details_map):
  print("-------------------------GetQuestionaireDetailsFromQuestionListId:[start]-------------------------");
  con = db_con_obj.GetConnection()
  if not con:
    print("GetQuestionaireDetailsFromQuestionListId: Failed to open DB Connection")
    sys.exit(2)
  questionaire_details_map.clear();
  category_list_sql = f"SELECT  CategoryID, CategoryName FROM QuestionCategoryTable WHERE QuestionListID ={question_list_id} ORDER BY CategoryID ASC";
  questionaire_details_map['QuestionListID'] = question_list_id
  questionaire_details_map['CategoryMap'] = OrderedDict()
  try:
    cursor = con.cursor()
    cursor.execute(category_list_sql)
    category_detail_rows = cursor.fetchall()
    if (not len(category_detail_rows)):
      print("Warning: GetQuestionaireDetails: Empty Question List returned by DB")
      return
    
    for category_details_row in category_detail_rows:
      category_id = category_details_row[0];
      category_name = category_details_row[1];
      questionaire_details_map['CategoryMap'][category_id] = {
        'CategoryName' : category_name,
        'QuestionMap' : OrderedDict()
        }
      print(f"Looking up questions for QuestionCategoryId ID:{question_list_id}, CategoryId={category_id}, CategoryName:{category_name}")
      question_list_sql = f"SELECT QuestionNumber, Question FROM QuestionsTable WHERE QuestionListID ={question_list_id} AND CategoryID ={category_id} ORDER BY QuestionNumber ASC"
      cursor.execute(question_list_sql)
      question_list_rows = cursor.fetchall()
      for question_details_row in question_list_rows:
        question_number = question_details_row[0]
        question = question_details_row[1]
        print(f"\tAdding question number={question_number}, Question={question}")
        questionaire_details_map['CategoryMap'][category_id]['QuestionMap'][question_number] = question
  except (mysql.connector.Error, mysql.connector.Warning) as e:
    print(f"GetQuestionaireDetailsFromQuestionListId: Failed due to exception:{e}\n")
  finally:
    cursor.close()
  print("-------------------GetQuestionaireDetailsFromQuestionListId:[end]------------------------");
  

def GetSurveyFeedbackForEmployeeBySurveyorType(db_con_obj, survey_id, eid, feedback_map):
  print("-------------------GetSurveyFeedbackForEmployeeBySurveyorType:[start]------------------------\n");
  feedback_map.clear()
  con = db_con_obj.GetConnection()
  if not con:
    print("GetSurveyFeedbackForEmployeeBySurveyorType: Failed to open DB Connection")
    sys.exit(2)
  open_survey_assignments = GetOpenAssignmnetCountForEmployeeInSurvey(db_con_obj, survey_id, eid)
  if open_survey_assignments:
    print(f"{open_survey_assignments} Open Survey Assignments exists for Employee={eid} exists in Survey={survey_id}\n")
    return
  if not con.is_connected():
    print("Database is not connected\n")
    sys.exit(2)
  try:
    cursor = con.cursor()
    assignments_sql = "SELECT SurveyAssignment.SurveyorEID, SurveyAssignment.SurveyorType, Employee.Name,"
    assignments_sql += " Employee.DepartmentID FROM SurveyAssignment INNER JOIN Employee on SurveyAssignment.SurveyorEID = Employee.EID"
    assignments_sql += " WHERE SurveyAssignment.SurveyID={} AND SurveyAssignment.EID='{}'".format(survey_id, eid)
    print(f"Executing SQL=[{assignments_sql}]\n")
    cursor.execute(assignments_sql)
    print(f"Number of rows returned:{cursor.rowcount}")
    survey_assignments = cursor.fetchall()
    feedback_avg_map = OrderedDict()
    for survey_assignment in survey_assignments:
      surveyor_eid = str(survey_assignment[0])
      surveyor_type_id = survey_assignment[1]
      surveyor_name = survey_assignment[2]
      surveyor_dept_id = survey_assignment[3]
      surveyor_type = SurveyorType2String[SurveyorType(surveyor_type_id)]
      surveyor_dept = DepartmentId2DepartmentName(db_con_obj, surveyor_dept_id)
      print(f"Surveyor Type={surveyor_type}, surveyor_eid={surveyor_eid}")
      print(f"Dictionary Keys:{feedback_map.keys()}")
      if surveyor_type not in feedback_map:
        print(f"Adding new surveyor type to dic:{surveyor_type}\n")
        feedback_map[surveyor_type] = {'AverageRatings' : {},
                                       'Surveyors' : {}}
                                       
        feedback_map[surveyor_type]['Surveyors'][surveyor_eid] = {'Name' : surveyor_name,
                                                     'Dept' : surveyor_dept,
                                                     'Feedback' : []}
        feedback_avg_map[surveyor_type] = OrderedDict()
      elif surveyor_eid not in feedback_map[surveyor_type]['Surveyors']:
        print(f"Creating Hash key surveyor_type={surveyor_type}, surveyor_eid={surveyor_eid}\n")
        feedback_map[surveyor_type]['Surveyors'][surveyor_eid] = {'Name' : surveyor_name,
                                                       'Dept' : surveyor_dept,
                                                       'Feedback' : []}
      print(f"feedback_map[{surveyor_type}[Surveyors].keys={feedback_map[surveyor_type]['Surveyors'].keys()}")
      print(feedback_map[surveyor_type])
      feedback_sql = "SELECT QuestionCategoryID, QuestionID, Rating"
      feedback_sql += " FROM SurveyFeedback WHERE SurveyID={} AND EID='{}' AND SurveyorEID='{}'".format(survey_id, eid, surveyor_eid)
      print(f"Executing internale query:[{feedback_sql}]\n")
      cursor.execute(feedback_sql)
      print(f"SurveyorId={surveyor_eid} Name={surveyor_name} Type={surveyor_type} Feedback for Employee={eid} Survey={survey_id}\n")
      feedback_list = cursor.fetchall()
      for feedback_instance in feedback_list:
        category_id = feedback_instance[0]
        question_id = feedback_instance[1]
        rating = feedback_instance[2]
        print(f"\tCategoryId={category_id}, QuestionId={question_id}, Rating={rating}\n")
        feedback_map[surveyor_type]['Surveyors'][surveyor_eid]['Feedback'].append((category_id, question_id, rating))
        if not category_id in feedback_avg_map[surveyor_type]:
          print(f"Adding category id:{category_id} to average map surveyor_type={surveyor_type}, rating={rating}\n")
          feedback_avg_map[surveyor_type][category_id]={
            'RatingTotal' : rating,
            'RatingCount' : 1
            }
        else:
          print(f"Adding rating to existing surveyor_type={surveyor_type}, question_category_id={category_id}\n")
          feedback_avg_map[surveyor_type][category_id]['RatingTotal'] += rating
          feedback_avg_map[surveyor_type][category_id]['RatingCount'] += 1
          print(f"After Add RatingTotal for surveyor_type={surveyor_type}, category id:{category_id}, ")
          print(f"RatingTotal={feedback_avg_map[surveyor_type][category_id]['RatingTotal']}, RatingCount={feedback_avg_map[surveyor_type][category_id]['RatingCount']}\n")
    print("Calculating Rating Averages per Surveyor Type\n")
    for surveyor_type_key in feedback_avg_map:
      print(f"Calculating average for Surveyor Type={surveyor_type_key}\n")
      for category_id_key in feedback_avg_map[surveyor_type_key]:
        ratings_average = round(feedback_avg_map[surveyor_type_key][category_id_key]['RatingTotal'] / feedback_avg_map[surveyor_type_key][category_id_key]['RatingCount'], 1)
        feedback_map[surveyor_type_key]['AverageRatings'][category_id_key] = ratings_average
        print(f"\tCategoryId={category_id_key}, RatingTotal={feedback_avg_map[surveyor_type_key][category_id_key]['RatingTotal']}, ")
        print(f"Number-of-Ratings={feedback_avg_map[surveyor_type_key][category_id_key]['RatingCount']}, AverageRating={ratings_average}\n")

  except (mysql.connector.Error, mysql.connector.Warning) as e:
      print(f"GetSurveyFeedbackForEmployeeBySurveyorType: Failed due to exception:{e}\n")
  finally:
    cursor.close()
  
  print("-------------------GetSurveyFeedbackForEmployeeBySurveyorType:[end]------------------------\n");


def GetSurveyFeedbackForEmployee(db_con_obj, survey_id, eid, feedback_map):
  print("-------------------GetSurveyFeedbackForEmployee:[start]------------------------\n");
  feedback_map.clear()
  con = db_con_obj.GetConnection()
  if not con:
    print("GetSurveyFeedbackForEmployee: Failed to open DB Connection")
    sys.exit(2)
  open_survey_assignments = GetOpenAssignmnetCountForEmployeeInSurvey(db_con_obj, survey_id, eid)
  if open_survey_assignments:
    print(f"{open_survey_assignments} Open Survey Assignments exists for Employee={eid} exists in Survey={survey_id}\n")
    return
  if not con.is_connected():
    print("Database is not connected\n")
    sys.exit(2)
  try:
    cursor = con.cursor()
    assignments_sql = "SELECT SurveyAssignment.SurveyorEID, SurveyAssignment.SurveyorType, Employee.Name,"
    assignments_sql += " Employee.DepartmentID FROM SurveyAssignment INNER JOIN Employee on SurveyAssignment.SurveyorEID = Employee.EID"
    assignments_sql += " WHERE SurveyAssignment.SurveyID={} AND SurveyAssignment.EID='{}'".format(survey_id, eid)
    print(f"Executing SQL=[{assignments_sql}]\n")
    cursor.execute(assignments_sql)
    print(f"Number of rows returned:{cursor.rowcount}")
    survey_assignments = cursor.fetchall()
    feedback_avg_map = OrderedDict()
    for survey_assignment in survey_assignments:
      surveyor_eid = str(survey_assignment[0])
      surveyor_type_id = survey_assignment[1]
      surveyor_name = survey_assignment[2]
      surveyor_dept_id = survey_assignment[3]
      surveyor_type = SurveyorType2String[SurveyorType(surveyor_type_id)]
      surveyor_dept = DepartmentId2DepartmentName(db_con_obj, surveyor_dept_id)
      print(f"Surveyor Type={surveyor_type}, surveyor_eid={surveyor_eid}")
      print(f"Dictionary Keys:{feedback_map.keys()}")
      if surveyor_type not in feedback_map:
        print(f"Adding new surveyor type to dic:{surveyor_type}\n")
        feedback_map[surveyor_type] = {'AverageRatings' : {},
                                       'Surveyors' : {}}
                                       
        feedback_map[surveyor_type]['Surveyors'][surveyor_eid] = {'Name' : surveyor_name,
                                                     'Dept' : surveyor_dept,
                                                     'Feedback' : []}
        feedback_avg_map[surveyor_type] = OrderedDict()
      elif surveyor_eid not in feedback_map[surveyor_type]['Surveyors']:
        print(f"Creating Hash key surveyor_type={surveyor_type}, surveyor_eid={surveyor_eid}\n")
        feedback_map[surveyor_type]['Surveyors'][surveyor_eid] = {'Name' : surveyor_name,
                                                       'Dept' : surveyor_dept,
                                                       'Feedback' : []}
      print(f"feedback_map[{surveyor_type}[Surveyors].keys={feedback_map[surveyor_type]['Surveyors'].keys()}")
      print(feedback_map[surveyor_type])
      feedback_sql = "SELECT QuestionCategoryID, QuestionID, Rating"
      feedback_sql += " FROM SurveyFeedback WHERE SurveyID={} AND EID='{}' AND SurveyorEID='{}'".format(survey_id, eid, surveyor_eid)
      print(f"Executing internale query:[{feedback_sql}]\n")
      cursor.execute(feedback_sql)
      print(f"SurveyorId={surveyor_eid} Name={surveyor_name} Type={surveyor_type} Feedback for Employee={eid} Survey={survey_id}\n")
      feedback_list = cursor.fetchall()
      for feedback_instance in feedback_list:
        category_id = feedback_instance[0]
        question_id = feedback_instance[1]
        rating = feedback_instance[2]
        print(f"\tCategoryId={category_id}, QuestionId={question_id}, Rating={rating}\n")
        feedback_map[surveyor_type]['Surveyors'][surveyor_eid]['Feedback'].append((category_id, question_id, rating))
        if not category_id in feedback_avg_map[surveyor_type]:
          print(f"Adding category id:{category_id} to average map surveyor_type={surveyor_type}, rating={rating}\n")
          feedback_avg_map[surveyor_type][category_id]={
            'RatingTotal' : rating,
            'RatingCount' : 1
            }
        else:
          print(f"Adding rating to existing surveyor_type={surveyor_type}, question_category_id={category_id}\n")
          feedback_avg_map[surveyor_type][category_id]['RatingTotal'] += rating
          feedback_avg_map[surveyor_type][category_id]['RatingCount'] += 1
          print(f"After Add RatingTotal for surveyor_type={surveyor_type}, category id:{category_id}, ")
          print(f"RatingTotal={feedback_avg_map[surveyor_type][category_id]['RatingTotal']}, RatingCount={feedback_avg_map[surveyor_type][category_id]['RatingCount']}\n")
    print("Calculating Rating Averages per Surveyor Type\n")
    for surveyor_type_key in feedback_avg_map:
      print(f"Calculating average for Surveyor Type={surveyor_type_key}\n")
      for category_id_key in feedback_avg_map[surveyor_type_key]:
        ratings_average = round(feedback_avg_map[surveyor_type_key][category_id_key]['RatingTotal'] / feedback_avg_map[surveyor_type_key][category_id_key]['RatingCount'], 1)
        feedback_map[surveyor_type_key]['AverageRatings'][category_id_key] = ratings_average
        print(f"\tCategoryId={category_id_key}, RatingTotal={feedback_avg_map[surveyor_type_key][category_id_key]['RatingTotal']}, ")
        print(f"Number-of-Ratings={feedback_avg_map[surveyor_type_key][category_id_key]['RatingCount']}, AverageRating={ratings_average}\n")

  except (mysql.connector.Error, mysql.connector.Warning) as e:
      print(f"GetSurveyFeedbackForEmployee: Failed due to exception:{e}\n")
  finally:
    cursor.close()
  print("-------------------GetSurveyFeedbackForEmployee:[end]------------------------\n");

  


