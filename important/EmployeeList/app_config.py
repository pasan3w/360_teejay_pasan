'''
Created on Sep 25, 2023

@author: k2d2

'''

import configparser


def ReadConfig(config_file):
  print("************ReadConfig:[start]************")
  config = configparser.ConfigParser()
  config.optionxform = str
  print("\tReading config file=", config_file)
  config.read(config_file)
  if 'company.employee.excelconfig' in config:
    print("\tCompany Employee List ExcelConfig: Number of keys loaded=", len(config['company.employee.excelconfig']))
    # for key in config['company.employee.excelconfig']:
    # print('\tKey=', key, ', Description=', config['company.employee.excelconfig'][key])
  if 'company.manager.excelconfig' in config:
    print("\tCompany Manager List ExcelConfig: Number of keys loaded=", len(config['company.manager.excelconfig']))
    # for key in config['company.manager.excelconfig']:
    # print('\tKey=', key, ', Description=', config['company.manager.excelconfig'][key])    
  print("************ReadConfig:[end]************")
  return config


def PrintConfig(app_config):
  print("************PrintConfig:[start]************")  
  sections = app_config.sections()
  for section in sections:
    print("--------Section=", section, "--------")
    options = app_config.options(section)
    for option in options:
      try:
        value = app_config.get(section, option) 
        print("\tAdd option={}, value={}".format(option, value))
      except:
        print("Error reading option=", option)
  print("************PrintConfig:[end]************")  
