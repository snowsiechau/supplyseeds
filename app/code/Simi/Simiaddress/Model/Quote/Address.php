<?php


namespace Simi\Simiaddress\Model\Quote;


class Address extends \Magento\Quote\Model\Quote\Address
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
        return $this->getData(self::ADDRESS_AREA);
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
        return $this->getData(self::ADDRESS_BLOCK);
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
        return $this->getData(self::ADDRESS_AVENUE);
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
        return $this->getData(self::ADDRESS_BUILDING_NO);
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
        return $this->getData(self::ADDRESS_FLOOR);
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
        return $this->getData(self::ADDRESS_APARTMENT);
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
        return $this->getData(self::ADDRESS_DELIVERY_INSTRUCTION);
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
        return $this->getData(self::ADDRESS_LOCATION_NAME);
    }


    /**
     * {@inheritdoc}
     */
    public function setLocationName($locationName){
        return $this->setData(self::ADDRESS_LOCATION_NAME, $locationName);
    }

    public function exportCustomerAddress()
    {
        $addressDataObject = parent::exportCustomerAddress();

        if(!$this->getArea() && $this->getExtensionAttributes()) {
            $this->setArea($this->getExtensionAttributes()->getArea());
            $this->setBlock($this->getExtensionAttributes()->getBlock());
            $this->setAvenue($this->getExtensionAttributes()->getAvenue());
            $this->setBuildingNo($this->getExtensionAttributes()->getBuildingNo());
            $this->setFloor($this->getExtensionAttributes()->getFloor());
            $this->setApartment($this->getExtensionAttributes()->getApartment());
            $this->setDeliveryInstruction($this->getExtensionAttributes()->getDeliveryInstruction());
            $this->setLocationName($this->getExtensionAttributes()->getLocationName());
        }

        $addressDataObject->setArea($this->getArea());
        $addressDataObject->setBlock($this->getBlock());
        $addressDataObject->setAvenue($this->getAvenue());
        $addressDataObject->setBuildingNo($this->getBuildingNo());
        $addressDataObject->setFloor($this->getFloor());
        $addressDataObject->setApartment($this->getApartment());
        $addressDataObject->setDeliveryInstruction($this->getDeliveryInstruction());
        $addressDataObject->setLocationName($this->getLocationName());

        $addressDataObject->setCustomAttribute(self::ADDRESS_AREA, $this->getArea());
        $addressDataObject->setCustomAttribute(self::ADDRESS_BLOCK, $this->getBlock());
        $addressDataObject->setCustomAttribute(self::ADDRESS_AVENUE, $this->getAvenue());
        $addressDataObject->setCustomAttribute(self::ADDRESS_BUILDING_NO, $this->getBuildingNo());
        $addressDataObject->setCustomAttribute(self::ADDRESS_FLOOR, $this->getFloor());
        $addressDataObject->setCustomAttribute(self::ADDRESS_APARTMENT, $this->getApartment());
        $addressDataObject->setCustomAttribute(self::ADDRESS_DELIVERY_INSTRUCTION, $this->getDeliveryInstruction());
        $addressDataObject->setCustomAttribute(self::ADDRESS_LOCATION_NAME, $this->getLocationName());

        return $addressDataObject;
    }

    public function importCustomerAddressData(\Magento\Customer\Api\Data\AddressInterface $address)
    {
        parent::importCustomerAddressData($address);

        if(!$this->getArea()) {
            $this->setArea($address->getArea());
            $this->setBlock($address->getBlock());
            $this->setAvenue($address->getAvenue());
            $this->setBuildingNo($address->getBuildingNo());
            $this->setFloor($address->getFloor());
            $this->setApartment($address->getApartment());
            $this->setDeliveryInstruction($address->getDeliveryInstruction());
            $this->setLocationName($address->getLocationName());
        }

        return $this;
    }
}
