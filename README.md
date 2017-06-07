# ES_Client

[![Build Status](https://travis-ci.org/jguido/ES_Client.svg?branch=master)](https://travis-ci.org/jguido/ES_Client) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/ef784529-0c3f-405f-ba45-323e9a1f70c6/mini.png)](https://insight.sensiolabs.com/projects/ef784529-0c3f-405f-ba45-323e9a1f70c6)
 
 Running an elastic search docker container :
 ```
 docker run -p 9200:9200 -e "http.host=http://127.0.0.1" -d --name=es -e "transport.host=127.0.0.1" docker.elastic.co/elasticsearch/elasticsearch:5.4.0
 ```
 
From the official elastic search site : [https://www.elastic.co/guide/en/elasticsearch/reference/current/docker.html](https://www.elastic.co/guide/en/elasticsearch/reference/current/docker.html)

 
 for helping you can run a kibana container :
 ```
 docker run -d --name=es_kibana -p 5601:5601 -e ELASTICSEARCH_URL=http://172.17.0.2:9200 -e ELASTICSEARCH_PASSWORD=changeme -e ELASTICSEARCH_USERNAME=elastic docker.elastic.co/kibana/kibana:5.4.0
 ```
 
 
