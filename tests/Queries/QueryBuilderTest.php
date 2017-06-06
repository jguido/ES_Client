<?php


namespace Tests\Queries;


use Tests\Tools\TestHelper;
use Unrlab\Domain\Query\Value\Date;
use Unrlab\Domain\Query\Value\Text;
use Unrlab\Domain\Query\Filter;
use Unrlab\Domain\Query\Must;
use Unrlab\Domain\Query\Query;

class QueryBuilderTest extends TestHelper
{
    public function testShouldReturnAValidSimpleQuery()
    {
        $attendedQuery = '{"query":{"bool":{"must":[{"match":{"title":"Search"}},{"match":{"content":"Elasticsearch"}}],"filter":[{"term":{"status":"published"}},{"range":{"publish_date":{"gte":"2015-01-01"}}}]}}}';

        $must1 = new Must(Query::MATCH, "title", new Text("Search"));
        $must2 = new Must(Query::MATCH, "content", new Text("Elasticsearch"));

        $filter1 = new Filter(Filter::TERM, "status", new Text("published"));
        $filter2 = new Filter(Filter::RANGE, "publish_date", new Date(Date::GTE, "2015-01-01"));

        $queryBuilder = new Query();
        $query = $queryBuilder
            ->addMust($must1)
            ->addMust($must2)
            ->addFilter($filter1)
            ->addFilter($filter2);

        self::assertEquals($attendedQuery, json_encode($query));

    }

}