<?php

declare(strict_types=1);

return [
    \GeorgRinger\News\Domain\Model\News::class => [
        'subclasses' => [
            0 => \Hosni\HosniSite\Domain\Model\News::class,
        ],
    ],
    \Hosni\HosniSite\Domain\Model\News::class => [
        'tableName' => 'tx_news_domain_model_news',
        'recordType' => 0,
    ],
];
