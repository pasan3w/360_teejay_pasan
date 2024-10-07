'''
Created on Dec 21, 2023

@author: k2d2
'''

from enum import Enum

class SurveyState(Enum):
  INVALID = 0
  ASSIGN = 1
  INPROGRESS = 2
  COMPLETE =3

  
SurveyState2String = {
  SurveyState.INVALID : "InvalidState",
  SurveyState.ASSIGN : "Assign",
  SurveyState.INPROGRESS : "InProgress",
  SurveyState.COMPLETE : "Complete"
  }

  
class SurveyorType(Enum):
  SELF = 0
  REPORTING_MANAGER = 1
  DIRECT_REPORT = 2
  PEER = 4
  INTERNAL_SURVEYOR = 5
  EXTERNAL_SURVEYOR = 6

  
SurveyorType2String = {
  SurveyorType.SELF : "Self",
  SurveyorType.REPORTING_MANAGER : "ReportingManager",
  SurveyorType.DIRECT_REPORT : "DirectReport",
  SurveyorType.PEER : "Peer",
  SurveyorType.INTERNAL_SURVEYOR : "InternalSurveyor",
  SurveyorType.EXTERNAL_SURVEYOR : "ExternalSurveyor"
  }

ColorMap = {
  SurveyorType.SELF : 'k',
  SurveyorType.REPORTING_MANAGER : 'r',
  SurveyorType.DIRECT_REPORT : 'b',
  SurveyorType.PEER : 'g',
  SurveyorType.INTERNAL_SURVEYOR : 'c',
  SurveyorType.EXTERNAL_SURVEYOR : 'm'
  }


def GetCategoryListFromQuestionaireDetails(questionaire_details_map, category_list):
  print("-------------------------GetCategoryListFromQuestionaireDetails:[start]-------------------------");
  category_list.clear()
  for category_id in questionaire_details_map['CategoryMap'].keys():
    print(f"Adding CategoryId={category_id}, CategoryName={questionaire_details_map['CategoryMap'][category_id]['CategoryName']}")
    category_list.append((category_id, questionaire_details_map['CategoryMap'][category_id]['CategoryName']))
  print(f"Total Number of categories found={len(category_list)}")
  print("-------------------------GetCategoryListFromQuestionaireDetails:[end]-------------------------");


def PrintSurveyFeedbackOfEmployee(survey_id, eid, feedback_map):
  print("---------------------------PrintSurveyFeedbackOfEmployee:[start]--------------------------------\n")
  print(f"-------Feedback received for Employee={eid} in SurveyId={survey_id}---------------------")
  for surveyor_type_key in feedback_map:
    print(f"-------SurveyorType={surveyor_type_key}--------\n")
    print(f"The EIDs of SurveyorType={surveyor_type_key} is {feedback_map[surveyor_type_key]['Surveyors'].keys()}")
    for surveyor_eid_key in feedback_map[surveyor_type_key]['Surveyors']:
      print(f"SurveyorEID={surveyor_eid_key}")
      print(f"\tSurveyorEID={surveyor_eid_key}, Name={feedback_map[surveyor_type_key]['Surveyors'][surveyor_eid_key]['Name']},Department={feedback_map[surveyor_type_key]['Surveyors'][surveyor_eid_key]['Dept']}\n")
      print("\t----Feedback----\n")
      for feedback_item in feedback_map[surveyor_type_key]['Surveyors'][surveyor_eid_key]['Feedback']:
        print(f"\t\tCategoryId={feedback_item[0]}, QuestionId={feedback_item[1]}, Rating={feedback_item[2]}\n")
    print(f"----Ratings Average per Category for Surveyor Type={surveyor_type_key}----")
    for category_id_key in feedback_map[surveyor_type_key]['AverageRatings']:
      print(f"\tCategoryId={category_id_key}, AverageRating={feedback_map[surveyor_type_key]['AverageRatings'][category_id_key]}\n")
  print("---------------------------PrintSurveyFeedbackOfEmployee:[end]--------------------------------\n")

