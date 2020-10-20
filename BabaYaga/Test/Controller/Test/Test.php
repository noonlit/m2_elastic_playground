<?php

namespace BabaYaga\Test\Controller\Test;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Indexer\IndexerRegistry;

class Test extends Action
{
    /**
     * @var IndexerRegistry
     */
    private $r;

    public function __construct(
        Context $context,
        IndexerRegistry $r
    ) {
        $this->r = $r;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $this->r->get('catalogsearch_fulltext')->reindexAll();

        /** @var Raw $resultRaw */
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $result->setContents("It works!");

        return $result;
    }
}
