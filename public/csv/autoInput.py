import csv
import os
import requests

count = 5

backend_url = "http://localhost:8000/book"

csv_url = os.path.join(os.getcwd(), 'csv', 'tenlong.csv')

headers = {
  'Content-Type': 'application/x-www-form-urlencoded'
}

with open(csv_url, encoding='utf8') as csvfile:
    spamreader = csv.reader(csvfile)
    for row in spamreader:
        title = row[1]
        author = row[2]
        publisher = row[3]
        isbn = row[4]
        publication_date = row[5]
        summary = row[6]

        if isbn == 'isbn.2':
            continue

        payload = f'isbn={isbn}&title={title}&author={author}&publication_date={publication_date}&summary={summary}&publisher={publisher}'
        payload=payload.encode("utf-8").decode("latin1")

        response = requests.request("POST", backend_url, headers=headers, data=payload)
        # print(response.text.encode('utf8'))

