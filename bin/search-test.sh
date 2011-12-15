# ================================================
# Testing font search with n-gram in ElasticSearch
# ================================================

curl -X DELETE http://localhost:9200/fonts
curl -X PUT    http://localhost:9200/fonts -d '
{
  "settings" : {
    "index" : {
      "analysis" : {
        "analyzer" : {
          "my_analyzer" : {
            "type"      : "custom",
            "tokenizer" : "lowercase",
            "filter"    : ["my_ngram"]
          }
        },
        "filter" : {
          "my_ngram" : {
            "type" : "nGram",
            "min_gram" : 3,
            "max_gram" : 5
          }
        }
      }
    }
  },
  "mappings": {
    "font": {
      "properties": {
        "fontFamily": {
          "type": "string",
          "analyzer": "my_analyzer",
          "boost": 10
        }
      }
    }
  }
}
'

curl -X POST "http://localhost:9200/fonts/font" -d '
{
  "id"         : "123",
  "fontFamily" : "HelveticaNeueBold",
  "fontWeight" : "normal",
  "fontStyle"  : "normal",
  "sources"    : [
    "helvetica-neue-bold.ttf",
    "helvetica-neue-bold.eot"
  ]
}'

curl -X POST "http://localhost:9200/fonts/font" -d '
{
  "id"         : "123",
  "fontFamily" : "HelveticaNeueLight",
  "fontWeight" : "light",
  "fontStyle"  : "normal",
  "sources"    : [
    "helvetica-neue-light.ttf",
    "helvetica-neue-light.eot"
  ]
}'

curl -X POST "http://localhost:9200/fonts/_refresh"

curl -X GET  "http://localhost:9200/fonts/_search?q=fontFamily:Ligh&pretty=true"
