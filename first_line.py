import csv
import sys

f = open(sys.argv[1]) #thankyou @jjm
csv_f = csv.reader(f) 
l = []
for row in csv_f:
    l.append(row)
output = l[0]
print(output)