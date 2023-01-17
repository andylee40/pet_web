#!/bin/bash

cd /var/www/html/hong_pet/寵物食品申報網/
printf "%0.s-" {1..50}
printf "\n"
STARTDATE=`date +"%Y-%m-%d %T"`
echo Start : ${STARTDATE}
/home/ubuntu/miniconda3/bin/python3.8 food_auto_1228.py
ENDDATE=`date +"%Y-%m-%d %T"`
echo END : ${ENDDATE}
printf "%0.s-" {1..50}
printf "\n"