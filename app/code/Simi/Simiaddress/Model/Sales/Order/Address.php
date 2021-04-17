<?php


namespace Simi\Simiaddress\Model\Sales\Order;


class Address extends \Magento\Sales\Model\Order\Address
{
    const ADDRESS_AREA = 'area';
    const ADDRESS_BLOCK = 'block';
    const ADDRESS_AVENUE = 'avenue';
    const ADDRESS_BUILDING_NO = 'building_no';
    const ADDRESS_FLOOR = 'floor';
    const ADDRESS_APARTMENT = 'apartment';
    const ADDRESS_DELIVERY_INSTRUCTION = 'delivery_instruction';
    const ADDRESS_LOCATION_NAME = 'location_name';

    /**
     * {@inheritdoc}
     */
    public function getArea(){
        return $this->_get(self::ADDRESS_AREA);
    }

    /**
     * {@inheritdoc}
     */
    public function setArea($area){
        return $this->setData(self::ADDRESS_AREA, $area);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlock(){
        return $this->_get(self::ADDRESS_BLOCK);
    }


    /**
     * {@inheritdoc}
     */
    public function setBlock($block){
        return $this->setData(self::ADDRESS_BLOCK, $block);
    }


    /**
     * {@inheritdoc}
     */
    public function getAvenue(){
        return $this->_get(self::ADDRESS_AVENUE);
    }


    /**
     * {@inheritdoc}
     */
    public function setAvenue($avenue){
        return $this->setData(self::ADDRESS_AVENUE, $avenue);
    }


    /**
     * {@inheritdoc}
     */
    public function getBuildingNo(){
        return $this->_get(self::ADDRESS_BUILDING_NO);
    }


    /**
     * {@inheritdoc}
     */
    public function setBuildingNo($building_no){
        return $this->setData(self::ADDRESS_BUILDING_NO, $building_no);
    }


    /**
     * {@inheritdoc}
     */
    public function getFloor(){
        return $this->_get(self::ADDRESS_FLOOR);
    }


    /**
     * {@inheritdoc}
     */
    public function setFloor($floor){
        return $this->setData(self::ADDRESS_FLOOR, $floor);
    }


    /**
     * {@inheritdoc}
     */
    public function getApartment(){
        return $this->_get(self::ADDRESS_APARTMENT);
    }


    /**
     * {@inheritdoc}
     */
    public function setApartment($apartment){
        return $this->setData(self::ADDRESS_APARTMENT, $apartment);
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveryInstruction(){
        return $this->_get(self::ADDRESS_DELIVERY_INSTRUCTION);
    }


    /**
     * {@inheritdoc}
     */
    public function setDeliveryInstruction($deliveryInstruction){
        return $this->setData(self::ADDRESS_DELIVERY_INSTRUCTION, $deliveryInstruction);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocationName(){
        return $this->_get(self::ADDRESS_LOCATION_NAME);
    }


    /**
     * {@inheritdoc}
     */
    public function setLocationName($locationName){
        return $this->setData(self::ADDRESS_LOCATION_NAME, $locationName);
    }
}
