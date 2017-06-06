<?php


namespace Unrlab\Domain\Query;

use JMS\Serializer\Annotation as JMS;
use Unrlab\Domain\Mapping\Index;
use Unrlab\Domain\Query\DSL\BaseDSL;

/*
GET /_search
{
  "query": {
    "bool": {
      "must": [
        { "match": { "title":   "Search"        }},
        { "match": { "content": "Elasticsearch" }}
      ],
      "filter": [
        { "term":  { "status": "published" }},
        { "range": { "publish_date": { "gte": "2015-01-01" }}}
      ]
    }
  }
}
*/

class Query implements \JsonSerializable
{
    const MATCH = "match";
    /**
     * @var Must[]
     * @JMS\Type("array<Unrlab\Domain\Query\Must>")
     */
    protected $mustDataList = [];
    /**
     * @var Should[]
     * @JMS\Type("array<Unrlab\Domain\Query\Should>")
     */
    protected $shouldDataList = [];
    /**
     * @var Filter[]
     * @JMS\Type("array<Unrlab\Domain\Query\Filter>")
     */
    protected $filterList = [];

    /**
     * @var Index
     * @JMS\Type("Unrlab\Domain\Mapping\Index")
     */
    protected $index;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $type;

    /**
     * @param Must $must
     * @return Query
     */
    public function addMust(Must $must): self
    {
        $this->mustDataList[] = $must;

        return $this;
    }

    /**
     * @param Must[] $mustList
     * @return Query
     */
    public function setMustList(array $mustList): self
    {
        $this->mustDataList = $mustList;

        return $this;
    }

    /**
     * @param Should $should
     * @return Query
     */
    public function addShould(Should $should): self
    {
        $this->shouldDataList[] = $should;

        return $this;
    }

    /**
     * @param Should[] $shouldList
     * @return Query
     */
    public function setShouldList(array $shouldList): self
    {
        $this->shouldDataList[] = $shouldList;

        return $this;
    }

    /**
     * @param Filter $filter
     * @return Query
     */
    public function addFilter(Filter $filter): self
    {
        $this->filterList[] = $filter;

        return $this;
    }

    /**
     * @param Filter[] $filterList
     * @return Query
     */
    public function setFilterList(array $filterList): self
    {
        $this->filterList = $filterList;

        return $this;
    }

    /**
     * @return Index
     */
    public function getIndex(): Index
    {
        return $this->index;
    }

    /**
     * @param Index $index
     * @return Query
     */
    public function setIndex(Index $index): self
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Query
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function buildRoute(): string
    {
        $route = "/" . $this->getIndex()->getName();
        if ($this->getType() && !empty($this->getType())) {
            $route .= "/" . $this->getType();
        }
        $route .= "/_search";

        return $route;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $query = [
            "query" => [
                "bool" => []
            ]
        ];
        $query["query"]["bool"]["must"][] = ["match_all" => new \stdClass()];
        if (count($this->mustDataList) > 0) {
            $query["query"]["bool"]["must"][] = $this->buildSubQuery($this->mustDataList);
        }
        if (count($this->shouldDataList) > 0) {
            $query["query"]["bool"]["should"] = $this->buildSubQuery($this->shouldDataList);
        }
        if (count($this->filterList) > 0) {
            $query["query"]["bool"]["filter"] = $this->buildSubQuery($this->filterList);

        }

        return $query;
    }

    /**
     * @param BaseDSL[] $list
     * @return array
     */
    private function buildSubQuery(array $list): array
    {
        $subQuery = [];
        if (count($list) > 0) {
            foreach ($list as $el) {
                $subQuery[] = $el->jsonSerialize();
            }
        }

        return $subQuery;
    }
}