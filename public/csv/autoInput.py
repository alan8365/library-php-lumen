import csv
import os
import requests
import json

count = 5

backend_url = "http://localhost:8000"

csv_url = os.path.join(os.getcwd(), 'tenlong.csv')

print(csv_url)

headers = {
  'Content-Type': 'application/x-www-form-urlencoded'
}

manager_email = 'Xiao@gmail.com'
manager_password = 10040129

payload = f'email={manager_email}&password={manager_password}'
payload=payload.encode("utf-8").decode("latin1")

response = requests.request("POST", backend_url + '/auth/login', headers=headers, data=payload)

token = json.loads(response.text)['data']['access_token']
headers['Authorization'] = 'Bearer ' + token

with open(csv_url, encoding='utf8') as csvfile:
    spamreader = csv.reader(csvfile)
    for row in spamreader:
        title = row[0]
        author = row[1]
        publisher = row[2]
        isbn = row[3]
        publication_date = row[4]
        summary = row[5]
        img_src = row[6]

        if isbn == 'isbn.2':
          continue

        payload = f'isbn={isbn}&title={title}&author={author}&publication_date={publication_date}&summary={summary}&publisher={publisher}&img_src={img_src}'
        payload=payload.encode("utf-8").decode("latin1")

        response = requests.request("POST", backend_url+'/book', headers=headers, data=payload)
        print(f'{response.status_code}: {title}')
