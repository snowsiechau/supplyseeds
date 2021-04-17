<?php


namespace Simi\Simiaddress\Model\Customer;


class Address extends \Magento\Customer\Model\Address
{

    public function updateData($address)
    {
        $this->setData('area', $address->getArea());
        $this->setData('block', $address->getBlock());
        $this->setData('avenue', $address->getAvenue());
        $this->setData('building_no', $address->getBuildingNo());
        $this->setData('floor', $address->getFloor());
        $this->setData('apartment', $address->getApartment());
        $this->setData('delivery_instruction', $address->getDeliveryInstruction());
        $this->setData('location_name', $address->getLocationName());

        parent::updateData($address);
    }

}
