<?php
namespace Hosni\HosniSite\Domain\Model;

class Product extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    protected string $title = '';
    protected string $desc = '';

    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $desc
     * @return void
     */
    public function setDesc(string $desc): void
    {
        $this->desc = $desc;
    }
    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

}