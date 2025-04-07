<?php
namespace Hosni\HosniSite\Domain\Model;

class News extends \GeorgRinger\News\Domain\Model\News 
{
    protected int $isFocus = 0;
    protected int $number = 0;
    protected ?string $slugTest = '';
    protected ?string $password = '';

    /**
     * @param int $number
     * @return void
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $isFocus
     * @return void
     */
    public function setIsFocus(int $isFocus): void
    {
        $this->isFocus = $isFocus;
    }

    /**
     * @return int
     */
    public function getIsFocus(): int
    {
        return $this->isFocus;
    }

    /**
     * @param string $slugTest
     * @return void
     */
    public function setSlugTest(string $slugTest): void
    {
        $this->slugTest = $slugTest;
    }
    /**
     * @return string
     */
    public function getSlugTest(): string
    {
        return $this->slugTest;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}