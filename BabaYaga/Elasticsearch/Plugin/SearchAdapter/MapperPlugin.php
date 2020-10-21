<?php
declare(strict_types=1);

namespace BabaYaga\Elasticsearch\Plugin\SearchAdapter;

use Magento\Elasticsearch\Elasticsearch5\SearchAdapter\Mapper;
use Magento\Framework\Search\RequestInterface;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Class MapperPlugin.
 *
 * https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-field-value-factor
 *
 * Adds a field value factor query to the "should" branch of the default Magento bool query.
 */
class MapperPlugin
{
    /**
     * @param Mapper $subject
     * @param array $searchQuery
     * @param RequestInterface $request
     * @return array
     */
    public function afterBuildQuery(Mapper $subject, array $searchQuery, RequestInterface $request) {
        if (!isset($searchQuery['body']['query']['bool']['should'])) {
            return $searchQuery;
        }

        $queryString = $this->recursiveFindQueryString($searchQuery['body']['query']['bool']['should']);

        if (!$queryString) {
            return $searchQuery;
        }

        $searchQuery['body']['query']['bool']['should'][] = [
            'function_score' => [
                'query' => [
                    'multi_match' => [
                        'query' => $queryString,
                        'fields' => ['name'] // TODO: perhaps not hardcode the product name field.
                    ]
                ],
                'field_value_factor' => [
                    'field' => 'search_boost',
                    'missing' => 0
                ]
            ]
        ];

        return $searchQuery;
    }

    private function recursiveFindQueryString(array $haystack)
    {
        $iterator  = new RecursiveArrayIterator($haystack);
        $recursive = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($recursive as $key => $value) {
            if ($key === 'query' && is_string($value)) {
                return $value;
            }
        }
    }
}
