<?php


namespace Simi\Simiaddress\Model\Quote;


class ShippingAddressManagementPlugin extends \Magento\Quote\Model\ShippingAddressManagement
{
    /**
     * @param $subject
     * @param $cartId
     * @param $address
     * @throws CouldNotSaveException
     */
    public function assign(
        $cartId,
        $address
    ) {
        $extAttributes = $address->getExtensionAttributes();

        if ($extAttributes && !$address->getArea()) {
            try {

                $area  = $extAttributes->getArea()?$extAttributes->getArea():'';
                $block        = $extAttributes->getBlock()?$extAttributes->getBlock():'';
                $avenue        = $extAttributes->getAvenue()?$extAttributes->getAvenue():'';
                $buildingNo    = $extAttributes->getBuildingNo()?$extAttributes->getBuildingNo():'';
                $floor       = $extAttributes->getFloor()?$extAttributes->getFloor():'';
                $apartment          = $extAttributes->getApartment()?$extAttributes->getApartment():'';
                $deliveryInstruction          = $extAttributes->getDeliveryInstruction()?$extAttributes->getDeliveryInstruction():'';
                $locationName          = $extAttributes->getLocationName()?$extAttributes->getLocationName():'';

                $address->setArea($area);
                $address->setBlock($block);
                $address->setAvenue($avenue);
                $address->setBuildingNo($buildingNo);
                $address->setFloor($floor);
                $address->setApartment($apartment);
                $address->setDeliveryInstruction($deliveryInstruction);
                $address->setLocationName($locationName);

            } catch (\Exception $e) {
                throw new CouldNotSaveException(
                    __('One custom field could not be added to the address.'),
                    $e
                );
            }
        }

        parent::assign($cartId, $address);
    }
}
