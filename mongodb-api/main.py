from flask import Flask, request
from pymongo import *
import datetime


PRICE = 0

mongodb_url = "mongodb://localhost:27017/"

#ckeckstock
def mongod_read(name):
    client = MongoClient(mongodb_url)

    collection = client["a3"]["debug"]

    find =  collection.find(projection={'_id':0},sort=[('_id',-1)])
    
    result = None
    
    for doc in find:
        if name in doc["name"]:
            name = str(doc["name"])
            amount = str(doc["amount"])
            text = name + ": "+amount+"\n"
    
    return text

#ckeckstock
def mongod_read_non():
    client = MongoClient(mongodb_url)
    collection = client["a3"]["debug"]

    find =  collection.find(projection={'_id':0},sort=[('_id',-1)])
    
    result = ""
    
    for doc in find:
        name = str(doc["name"])
        amount = str(doc["amount"])
        text = name + ": "+amount+"\n"
        result += text
        
    return result


# deleteall
def delete_all():
    client = MongoClient(mongodb_url)

    collection = client["a3"]["debug"]
    
    print("---全データ削除---")
    collection.drop()

# sell
def mongod_sell(name,amount,price):
    global PRICE
    client = MongoClient(mongodb_url)
    collection = client["a3"]["debug"]
    beforeAmount = check_bug(name)
    PRICE += amount * price
    beforeAmount -= amount
    print(f"PRICE: {PRICE}")
    print("----update----")
    collection.update({"name":name},{"$set":{"amount":beforeAmount}})
    print(f"name:{name}, amount:{amount}")


# sell
def mongod_sell_non(name,amount):
    client = MongoClient(mongodb_url)
    collection = client["a3"]["debug"]
    beforeAmount = check_bug(name)
    beforeAmount -= amount
    print("----update----")
    collection.update({"name":name},{"$set":{"amount":beforeAmount}})
    print(f"name:{name}, amount:{amount}")

# addstock用
def check_bug(name):
    client = MongoClient(mongodb_url)

    collection = client["a3"]["debug"]

    find =  collection.find(projection={'_id':0},sort=[('_id',-1)])
    
    flag = False
    amount = 0
    for doc in find:
        if name in doc["name"]:
            amount = int(doc["amount"])
            flag = True
    if flag == False:
        return False
    else:
        return amount

# addstack
def mongod_write(name,amount):
    client = MongoClient(mongodb_url)
    
    collection = client["a3"]["debug"]
    flag  = check_bug(name)
    if flag != False:
        print("----update----")
        amount = flag + amount
        collection.update({"name":name},{"$set":{"amount":amount}})
    else:
        print("----insert----")
        collection.insert({"name":name,"amount":amount})
    print(f"name:{name}, amount:{amount}")

def is_integer_num(n):
    if isinstance(n, int):
        return True
    if isinstance(n, float):
        return n.is_integer()
    return False


# センサー&パーツ登録用
def registerSensor(user,sensor):
    client = MongoClient(mongodb_url)
    collection = client["sensor"][user]
    now = getCurrentTime()
    collection.insert_one({"time" : now, "name": sensor}) #insert()のサポートはないっぽい

# ESP32登録用
def registerESP32(user,esp32Name):
    client = MongoClient(mongodb_url)
    collection = client["esp32"][user]
    now = getCurrentTime()
    collection.insert_one({"time" : now, "name" : esp32Name})




# 現在時刻設定
def getCurrentTime():
    dt_now = datetime.datetime.now()
    dt_now_jst_aware = datetime.datetime.now(
        datetime.timezone(datetime.timedelta(hours=9))
    )
    currentTime = dt_now.strftime('%Y年%m月%d日 %H:%M:%S')
    return currentTime

app = Flask(__name__)

@app.route("/")
def main():
    result = "hello Flask!"
    return result

@app.route("/register", methods=['GET', 'POST'])
def register():
    if request.method=="POST":
        typeName = request.form['type']
        if typeName == "esp32":
            userName = request.form['user']
            esp32Name = request.form['name']
            registerESP32(userName,esp32Name)
            return f"ユーザ名：{userName}, 登録ESP32名 : {esp32Name}"
        elif typeName == "sensor":
            userName = request.form['user']
            sensorName = request.form['name']
            registerSensor(userName,sensorName)
            return f"ユーザ名：{userName}, 登録センサー名 : {sensorName}"
        return "error"
   

if __name__ == "__main__":
    app.run(host="0.0.0.0",port=5000,debug=True)