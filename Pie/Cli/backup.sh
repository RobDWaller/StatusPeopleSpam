#!/bin/bash

mysqldump -u root -h localhost -pVfr45tgb# statuspeople_spam > /var/data_download/statuspeople_spam.sql
mysqldump -u root -h localhost -pVfr45tgb# statuspeople_blog > /var/data_download/statuspeople_blog.sql
mysqldump -u root -h localhost -pVfr45tgb# statuspeople_spam_api > /var/data_download/statuspeople_spam_api.sql
mysqldump -u root -h localhost -pVfr45tgb# statuspeople > /var/data_download/statuspeople.sql
mysqldump -u root -h localhost -pVfr45tgb# sttsp > /var/data_download/sttsp.sql

zip /var/data_download/spam_sql /var/data_download/statuspeople_spam.sql
zip /var/data_download/blog_sql /var/data_download/statuspeople_blog.sql
zip /var/data_download/spam_api_sql /var/data_download/statuspeople_spam_api.sql
zip /var/data_download/statuspeople_sql /var/data_download/statuspeople.sql
zip /var/data_download/sttsp_sql /var/data_download/sttsp.sql




