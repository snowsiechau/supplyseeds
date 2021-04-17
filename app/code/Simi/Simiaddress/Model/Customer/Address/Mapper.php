<?php


namespace Simi\Simiaddress\Model\Customer\Address;


class Mapper extends \Magento\Customer\Model\Address\Mapper
{

    public function toFlatArray($addressDataObject)
    {
        $result = parent::toFlatArray($addressDataObject);
        $result['area'] = $addressDataObject->getArea();
        $result['block'] = $addressDataObject->getBlock();
        $result['avenue'] = $addressDataObject->getAvenue();
        $result['building_no'] = $addressDataObject->getBuildingNo();
        $result['floor'] = $addressDataObject->getFloor();
        $result['apartment'] = $addressDataObject->getApartment();
        $result['delivery_instruction'] = $addressDataObject->getDeliveryInstruction();
        $result['location_name'] = $addressDataObject->getLocationName();

        return $result;
    }
}
