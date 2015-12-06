<?php

namespace data;

class DataSearchHotelResult
{
    private $ota;
    private $name;
    private $link;
    private $price;

    public function getOta()
    {
        return $this->ota;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setAttribute($attributeName, $value)
    {
        $this->$attributeName = $value;
    }
}