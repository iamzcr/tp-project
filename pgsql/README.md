# deep_pgsql
一个pgsql使用的例子
#补充一些pgsql的知识(翻译)：
原文地址：http://clarkdave.net/2013/06/what-can-you-do-with-postgresql-and-json/</br>
9.2以下安装json扩展方法原文地址：http://clarkdave.net/2013/06/adding-json-enhancements-to-postgresql-9-2/</br>
#创建数据库
createdb json_test</br>
psql json_test</br>
#创建表并插入数据
CREATE TABLE books ( id integer, data json );</br>

INSERT INTO books VALUES (1,
  '{ "name": "Book the First", "author": { "first_name": "Bob", "last_name": "White" } }');</br>
INSERT INTO books VALUES (2,
  '{ "name": "Book the Second", "author": { "first_name": "Charles", "last_name": "Xavier" } }');</br>
INSERT INTO books VALUES (3,
  '{ "name": "Book the Third", "author": { "first_name": "Jim", "last_name": "Brown" } }');</br>
#屌屌的查询：
SELECT id, data->>'name' AS name FROM books;</br>
这个查询直接查询json里面的name字段，这是mysql5.7以下版本做不到的</br>
还有更屌得查询：</br>
SELECT id, data->'author'->>'first_name' as author_first_name FROM books;</br>
你甚至可以拿json里面的字段作为条件查询</br>
SELECT * FROM books WHERE data->>'name' = 'Book the First';</br>
SELECT * FROM books WHERE data->'author'->>'first_name' = 'Charles';</br>
酷毙了！！！
