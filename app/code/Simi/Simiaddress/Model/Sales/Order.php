<?php


namespace Simi\Simiaddress\Model\Sales;


class Order extends \Magento\Sales\Model\Order
{

    private $fullBillingAddress;
    private $fullShippingAddress;

    public function getFullBillingAddress() {
        if(!$this->fullBillingAddress) {
            if($this->getBillingAddress()->getData('customer_address_id')) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $this->fullBillingAddress = $objectManager->create('Magento\Customer\Model\Address')->load($this->getBillingAddress()->getData('customer_address_id'));
            } else {
                $this->fullBillingAddress = $this->getBillingAddress();
            }
        }
        return $this->fullBillingAddress;
    }

    public function getFullShippingAddress() {
        if(!$this->fullShippingAddress) {
            if($this->getShippingAddress()->getData('customer_address_id')) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $this->fullShippingAddress = $objectManager->create('Magento\Customer\Model\Address')->load($this->getShippingAddress()->getData('customer_address_id'));
            } else {
                $this->fullShippingAddress = $this->getShippingAddress();
            }
        }
        return $this->fullShippingAddress;
    }

    public function getFullName($isShipping) {
        $address = $this->getFullBillingAddress();
        if($isShipping) {
            $address = $this->getFullShippingAddress();
        }
        return $address->getFirstname() . ' ' . $address->getLastname();
    }

    public function getBuildingBlock($isShipping) {
        $address = $this->getFullBillingAddress();
        if($isShipping) {
            $address = $this->getFullShippingAddress();
        }
        $buildingNo = $address->getBuildingNo();
        $blockArea = $address->getBlock();
        $result = '';
        if($buildingNo) {
            $result = $result . $buildingNo;
            // if($blockArea) {
            //     $result = $result . ', ' . $blockArea;
            // }
        }
        return $result;
    }

    public function getBlock($isShipping) {
        $address = $this->getFullBillingAddress();
        if($isShipping) {
            $address = $this->getFullShippingAddress();
        }
        $buildingNo = $address->getBuildingNo();
        $blockArea = $address->getBlock();
        $result = '';
        if($buildingNo) {
            // $result = $result . $buildingNo;
            if($blockArea) {
                $result = $result . $blockArea;
            }
        }
        return $result;
    }

    public function getAreaCountry($isShipping) {
        $address = $this->getFullBillingAddress();
        if($isShipping) {
            $address = $this->getFullShippingAddress();
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $area = $objectManager->create('Simi\Simiaddress\Helper\Address')->getAreaByCode($address->getArea())->getAreaLabel() . ' - ';
        $country = $objectManager->create('Magento\Directory\Model\CountryFactory')->create()->loadByCode($address->getCountryId())->getName();
        $city = $address->getCity() . ' - ';
        return $city . $country;
    }

    public function getArea($isShipping) {
        $address = $this->getFullBillingAddress();
        if($isShipping) {
            $address = $this->getFullShippingAddress();
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $area = $objectManager->create('Simi\Simiaddress\Helper\Address')->getAreaByCode($address->getArea())->getAreaLabel();
        $country = $objectManager->create('Magento\Directory\Model\CountryFactory')->create()->loadByCode($address->getCountryId())->getName();
        $city = $address->getCity() . ' - ';
        return $area;
    }

    public function getAvenue($isShipping){
        $address = $this->getFullBillingAddress();
        if($isShipping) {
            $address = $this->getFullShippingAddress();
        }
        if ($address->getAvenue()) {
            return 'Avenue: '. $address->getAvenue();
        }else{
            return '';
        }
    }

}
