<?php
declare(strict_types=1);

namespace BabaYaga\Elasticsearch\Model\Config\Source\Options;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class SearchBoost.
 *
 * Options for the search boost attribute.
 */
class SearchBoost extends AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('--'), 'value'=>''],
            ['label' => __('1'), 'value'=>'1'],
            ['label' => __('2'), 'value'=>'2'],
            ['label' => __('3'), 'value'=>'3'],
            ['label' => __('4'), 'value'=>'4'],
            ['label' => __('5'), 'value'=>'5'],
            ['label' => __('6'), 'value'=>'6'],
            ['label' => __('7'), 'value'=>'7'],
        ];

        return $this->_options;

    }

}
