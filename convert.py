import csv
import sys
import pymysql
conn = pymysql.connect(host='localhost',database='dmbi',user='root',password='')
cursor = conn.cursor()

f = open(sys.argv[1]) 
csv_f = csv.reader(f) 

database_file_format = []

str = "SELECT * FROM format"
try:
    cursor.execute(str)
    conn.commit()
    results = cursor.fetchall()
    for row in results:
        op = ''.join(row)
        database_file_format.append(op)

except:
	conn.rollback()
cursor.close()
conn.close()


l = []
final_format = []
for row in csv_f:
   l.append(row)

uploaded_file_format = l[0]




for colval in database_file_format:
    if colval in uploaded_file_format:
        final_format.append(colval)
        uploaded_file_format.remove(colval)



x=0
for remaining_values_in_lis in uploaded_file_format:
    x=x+1 
    # final_format.append(remaining_values_in_lis)
    s = "Insert Into format values("+"'"+remaining_values_in_lis+"'"+");"
    try:
        cursor.execute(s)
        conn.commit()
    except:
        conn.rollback()

print(final_format)

