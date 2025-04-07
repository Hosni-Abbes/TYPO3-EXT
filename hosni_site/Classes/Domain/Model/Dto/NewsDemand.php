<?php
namespace Hosni\HosniSite\Domain\Model\Dto;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand as BaseNewsDemand;


class NewsDemand extends BaseNewsDemand
{
   protected int $isFocus = 0;

    /**
     * @param int $isFocus
     */
    public function setIsFocus(int $isFocus): NewsDemand
    {
        $this->isFocus = $isFocus;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsFocus(): int
    {
        return $this->isFocus;
    }

}
