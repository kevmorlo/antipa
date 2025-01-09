import mysql.connector
import pandas as pd

# Load data files
corona = pd.read_csv('C:/Users/Jesuismoi/Desktop/Dossier/Utilisé/corona.csv')
localisation = pd.read_csv('C:/Users/Jesuismoi/Desktop/Dossier/Utilisé/localisation.csv')  # Country and continent data
variole = pd.read_csv('C:/Users/Jesuismoi/Desktop/Dossier/Utilisé/variole.csv')
variole = variole[~variole['iso_code'].str.contains("OWID", na=False)]  # Exclude OWID rows

# Adjust data
def correction(country_name):
    if 'Bosnia and Herzegovina' in country_name:  return 'Bosnia And Herzegovina'
    elif 'United States' in country_name: return 'USA'
    elif 'Democratic Republic of Congo' in country_name: return 'Democratic Republic Of The Congo'
    elif 'Czechia' in country_name: return 'Czech Republic'
    elif 'United Kingdom' in country_name: return 'UK'
    elif 'Vietnam' in country_name: return 'Viet Nam'
    else:
        return country_name

variole['location'] = variole['location'].apply(correction)

# Create Disease table
diseases = pd.DataFrame({'id': [1, 2], 'name': ['Coronavirus', 'Monkeypox']})

# Create Localization table
localisation['id'] = range(1, len(localisation) + 1)
localization = localisation.rename(columns={'country': 'country', 'continent': 'continent'})

# Merge Data Coronavirus and Localization
corona = corona.merge(localisation, how='left', left_on='country', right_on='country')

# Prepare insertion for ReportCase Data and Coronavirus
corona['diseaseId'] = 1  
coronavirus_report_cases = corona.rename(columns={
    'cumulative_total_cases': 'totalconfirmed',
    'cumulative_total_deaths': 'totalDeath',
    'active_cases': 'totalActive',
    'date': 'dateInfo',
    'id': 'localizationId'
})[['totalconfirmed', 'totalDeath', 'totalActive', 'localizationId', 'dateInfo', 'diseaseId']]

# Prepare insertion ReportCase Data for Monkeypox
variole = variole.rename(columns={
    'total_cases': 'totalconfirmed',
    'total_deaths': 'totalDeath',
    'location': 'country',
    'date': 'dateInfo'
})
variole = variole.merge(localisation, how='left', on='country')
variole['diseaseId'] = 2  
monkeypox_report_cases = variole.rename(columns={
    'id': 'localizationId'
})[['totalconfirmed', 'totalDeath', 'localizationId', 'dateInfo', 'diseaseId']]

# Merge ReportCase Data
report_cases = pd.concat([coronavirus_report_cases, monkeypox_report_cases], ignore_index=True)

# Remove null and negative data
report_cases = report_cases.dropna()  
report_cases = report_cases[
    (report_cases['totalconfirmed'] >= 0) &
    (report_cases['totalDeath'] >= 0) &
    (report_cases['totalActive'] >= 0)
]

config = {
    'host': 'localhost',
    'port': '3306',
    'user': 'root',
    'password': '',
    'database': 'listing_db',
    'collation': "utf8mb4_general_ci"
}

connection = mysql.connector.connect(**config)
cursor = connection.cursor()

# Insert Disease
for index, row in diseases.iterrows():
    cursor.execute("INSERT INTO Disease (id, name) VALUES (%s, %s)", (row['id'], row['name']))

# Insert Localization
for index, row in localization.iterrows():
    cursor.execute("INSERT INTO Localization (id, country, continent) VALUES (%s, %s, %s)",
                   (row['id'], row['country'], row['continent']))

# Insert ReportCase
for index, row in report_cases.iterrows():
    print("nb ligne traitée : " + str(index))
    cursor.execute("""
        INSERT INTO ReportCase (totalconfirmed, totalDeath, totalActive, localizationId, dateInfo, diseaseId) 
        VALUES (%s, %s, %s, %s, %s, %s)""",
        (row['totalconfirmed'], row['totalDeath'], row['totalActive'], row['localizationId'], row['dateInfo'], row['diseaseId']))

connection.commit()

cursor.close()
connection.close()
print("Insertion effectuée avec succès")
