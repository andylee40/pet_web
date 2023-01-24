from bs4 import BeautifulSoup
from datetime import datetime
import os
import pymysql
import datetime as yester
import requests
import urllib.parse
import pandas as pd 
import numpy as np
import time
import math
import re
from sqlalchemy import create_engine
from sqlalchemy.types import String, Integer,Date
from sqlalchemy import MetaData
from sqlalchemy.engine.url import URL
from sqlalchemy.ext.declarative import declarative_base

def Scrapy():
        global df
        cookies = {
            '_ga_R2CRY7L0BM': 'GS1.1.1658196874.1.0.1658196878.0',
            '_ga': 'GA1.3.1145436827.1657783134',
            '_gid': 'GA1.3.1417567587.1658709440',
            'ASP.NET_SessionId': 'hd0smdyzhaftz30sbbd2fij5',
        }

        headers = {
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Language': 'zh-TW,zh;q=0.9,en-US;q=0.8,en;q=0.7',
            'Cache-Control': 'max-age=0',
            'Connection': 'keep-alive',
            # Requests sorts cookies= alphabetically
            # 'Cookie': '_ga_R2CRY7L0BM=GS1.1.1658196874.1.0.1658196878.0; _ga=GA1.3.1145436827.1657783134; _gid=GA1.3.1417567587.1658709440; ASP.NET_SessionId=hd0smdyzhaftz30sbbd2fij5',
            'Origin': 'https://petfood.coa.gov.tw',
            'Referer': 'https://petfood.coa.gov.tw/web/page-food.aspx',
            'Sec-Fetch-Dest': 'document',
            'Sec-Fetch-Mode': 'navigate',
            'Sec-Fetch-Site': 'same-origin',
            'Sec-Fetch-User': '?1',
            'Upgrade-Insecure-Requests': '1',
            'User-Agent': 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Mobile Safari/537.36',
            'sec-ch-ua': '".Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"',
            'sec-ch-ua-mobile': '?1',
            'sec-ch-ua-platform': '"Android"',
        }
        #爬取網址
        url='https://petfood.coa.gov.tw/web/page-food.aspx'

        #儲存資料
        colist=[]

        #從首頁開始
        page=1

        #圖片連結重新編碼
        def encode(x):
            x = urllib.parse.quote(x)
            return x 

        #開始爬蟲
        while True:
            try:
                time.sleep(2)
                data = {
                    '__VIEWSTATE': '/wEPDwUKLTQ5NzkzNTM5NGRkoqN7/KU0QqcMXm2xkOKOyFVnlmbKZE+HZOyH5HCIzjY=',
                    '__VIEWSTATEGENERATOR': 'B8139607',
                    'acttype': 'query',
                    'id': '',
                    'qrycode': '',
                    'qrycode1': '',
                    'qrycode2': '',
                    'qrycode3': '',
                    'qrycode4': '',
                    'qrycode5': '',
                    'qrycode6': '',
                    'selectpage': page,
                    'pageto': page,
                    'pagerecs': '200',
                        }
                response = requests.post(url,cookies=cookies, headers=headers, data=data)
                content=response.text
                soup=BeautifulSoup(content,'lxml')
                maxpage=int(soup.find("select",attrs={'name':'selectpage'}).find_all('option')[-1].get('value'))
                div = soup.find_all("div", class_= "modal-content")
                col=soup.find_all("tr")[1:-2]
                for tt in col[::2]:
                    try:
                        
#                         if tt.find("td", {"style" : "background-color:#009900;color:#FFF;"}).text.replace(" ","")=='下架':
#                             continue
#                         else:
                        data2={'狀態':tt.find("td", {"style" : "background-color:#009900;color:#FFF;"}).text.replace(" ",""),
                                    '標題':tt.find_all('h3')[0].get_text(),
                                    '業者名稱':tt.find("h4", text=re.compile("業者名稱.*")).find_next_sibling("p").text,
                                    '產品來源':tt.find("h4", text=re.compile("產品來源.*")).find_next_sibling("p").text,
                                    '產品種類':tt.find("h4", text=re.compile("產品種類.*")).find_next_sibling("p").text,
                                    '重量容量錠數':tt.find("h4", text=re.compile("重量/容量/錠數.*")).find_next_sibling("p").text ,
                                    '主要原料及添加物':tt.find("h4", text=re.compile("主要原料及添加物.*")).find_next_sibling("p").text ,
                                    '主要營養成分及含量':tt.find("h4", text=re.compile("主要營養成分及含量.*")).find_next_sibling("p").text ,
                                    '適用寵物種類及使用方法及保存方法':tt.find("h4", text=re.compile("適用寵物種類、使用方法及保存方法.*")).find_next_sibling("p").text ,
                                    '製造或加工業者工廠名稱':tt.find("h4", text=re.compile("製造或加工業者工廠名稱.*")).find_next_sibling("p").text,
                                    '產品外包裝照片':encode('https://petfood.coa.gov.tw'+(tt.find("h4", text="產品外包裝照片").find_next_sibling("p").find('img').get('src')))}
                        colist.append(data2)
                    except:
                        data2={'狀態':tt.find("td", {"style" : "background-color:red;color:#FFF;"}).text.replace(" ",""),
                                    '標題':tt.find_all('h3')[0].get_text(),
                                    '業者名稱':tt.find("h4", text=re.compile("業者名稱.*")).find_next_sibling("p").text,
                                    '產品來源':tt.find("h4", text=re.compile("產品來源.*")).find_next_sibling("p").text,
                                    '產品種類':tt.find("h4", text=re.compile("產品種類.*")).find_next_sibling("p").text,
                                    '重量容量錠數':tt.find("h4", text=re.compile("重量/容量/錠數.*")).find_next_sibling("p").text ,
                                    '主要原料及添加物':tt.find("h4", text=re.compile("主要原料及添加物.*")).find_next_sibling("p").text ,
                                    '主要營養成分及含量':tt.find("h4", text=re.compile("主要營養成分及含量.*")).find_next_sibling("p").text ,
                                    '適用寵物種類及使用方法及保存方法':tt.find("h4", text=re.compile("適用寵物種類、使用方法及保存方法.*")).find_next_sibling("p").text ,
                                    '製造或加工業者工廠名稱':tt.find("h4", text=re.compile("製造或加工業者工廠名稱.*")).find_next_sibling("p").text,
                                    '產品外包裝照片':encode('https://petfood.coa.gov.tw'+(tt.find("h4", text="產品外包裝照片").find_next_sibling("p").find('img').get('src')))}
                        colist.append(data2)
                        continue
                if page < maxpage:
                    print("目前完成頁數為 {0} , 剩下 {1} 頁未完成".format(page,maxpage-page))
                    page+=1
                else:
                    print("目前完成頁數為 {} , 這是最後一頁".format(page))
                    print('-'*50)
                    print("工作完成")
                    break
            except Exception as e:
                print("錯誤頁數:",page)
                print(e)
                break
        #匯出檔案
        df = pd.DataFrame(colist)
        df.replace("\r","", regex=True, inplace=True)
        df.replace("\n","", regex=True, inplace=True)
        df.replace("\r\n","", regex=True, inplace=True)
        df.to_csv('food_daily/petfood_'+(datetime.now().strftime("%Y%m%d"))+'.csv',index=False)
        df.to_csv('food_daily/petfood_'+(datetime.now().strftime("%Y%m%d"))+'.zip', compression={'method': 'zip', 'archive_name': 'petfood_'+
                                                                                            (datetime.now().strftime("%Y%m%d"))+'.csv'},index=False)                                                                 
        return colist,df
    
    
def Clear(pet_food_different):
    
    #今日資料
    df_new=pd.read_csv('food_daily/petfood_'+yester.date.today().strftime("%Y%m%d")+'.csv',dtype='object',keep_default_na=False)
    pattern = re.compile(r'\s+')
    df_new=df_new.applymap(lambda x: pattern.sub('', x) if isinstance(x, str) else x)
    
    yesterday = yester.date.today() - yester.timedelta(days=1)
    oldfile='food_daily/petfood_'+yesterday.strftime("%Y%m%d")+'.csv'
    df_old=pd.read_csv(oldfile,dtype='object',keep_default_na=False)
    df_old=df_old.applymap(lambda x: pattern.sub('', x) if isinstance(x, str) else x)

    #異動資料
    df_add=df_old[df_old['狀態']=='上架'].append(df_new[df_new['狀態']=='上架'])
    df_drop=df_old[df_old['狀態']=='下架'].append(df_new[df_new['狀態']=='下架'])

    #新上架資料
    add = df_add.drop_duplicates(subset=['標題','業者名稱'],keep=False).dropna(subset=['標題']).reset_index(drop=True)
    add['新增時間']=datetime.now().strftime("%Y%m%d")
    add['移除時間']=''

    #新下架資料
    drop=df_drop.drop_duplicates(subset=['標題','業者名稱'],keep=False).dropna(subset=['標題']).reset_index(drop=True)
    drop['新增時間']=''
    drop['移除時間']=datetime.now().strftime("%Y%m%d")
    change=add.append(drop).reset_index(drop=True).iloc[:,1:]

    hostname="localhost"
    dbname="pet"
    uname="帳號"
    pwd="密碼"
    #Create SQLAlchemy engine to connect to MySQL Database
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}/{db}?charset=utf8mb4".format(host=hostname, db=dbname, user=uname, pw=pwd))
    change.to_sql(pet_food_different, engine, index=False,if_exists='append')

def Insert2():
    newst='/var/www/html/hong_pet/寵物食品申報網/food_daily/petfood_'+(datetime.now().strftime("%Y%m%d"))+'.csv'
    #連線資料庫
    connection= pymysql.connect(user='帳號',password='密碼',host='localhost',database='pet',local_infile=1)
    #清空資料庫
    cursor = connection.cursor()
    query1="TRUNCATE TABLE pet_food3"
    cursor.execute(query1)
    connection.commit()
    #插入最新資料
    query2="LOAD DATA LOCAL INFILE '" +newst+ """' INTO TABLE pet_food3  CHARACTER SET utf8mb4 FIELDS TERMINATED BY ',' ENCLOSED BY '"'
    IGNORE 1 ROWS;"""
    cursor.execute(query2)
    connection.commit()
    #關閉游標
    cursor.close()
    #關閉資料庫連線
    connection.close()
    print('資料插入pet_food成功')        
        
        
#刪除前2天csv
def Removecsv():
    today2 = yester.date.today() #獲得今天的日期
    yesterday2 = today2 - yester.timedelta(days=2)
    oldfile3='food_daily/petfood_'+yesterday2.strftime("%Y%m%d")+'.csv'
    try:
        os.remove(oldfile3)
        #os.remove(todayfile2)
    except OSError as e:
        print(e)
    else:
        print("刪除{}成功".format(oldfile3))
    
if __name__ == '__main__':
    Scrapy()
    Clear('pet_food_different')
    Insert2()
    Removecsv()
       
