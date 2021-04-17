<?php

/**
 * Connector data helper
 */

namespace Simi\Simiconnector\Helper\Options;

class Configurable extends \Simi\Simiconnector\Helper\Options
{

    public function helper($helper)
    {
        return $this->simiObjectManager->get($helper);
    }

    public function getPrice($product, $price, $includingTax = null)
    {
        if (!($includingTax === null)) {
            $price = $this->catalogHelper->getTaxPrice($product, $price, true);
        } else {
            $price = $this->catalogHelper->getTaxPrice($product, $price);
        }
        return $price;
    }

    public function getOptions($product)
    {
        $layout = $this->simiObjectManager->get('Magento\Framework\View\LayoutInterface');
        $block  = $layout->createBlock('Magento\ConfigurableProduct\Block\Product\View\Type\Configurable');
        $block->setProduct($product);
        $options                         = [];
        $configurable_options            = json_decode($block->getJsonConfig(), 1);

        if (isset($configurable_options['attributes'])) {
            foreach ($configurable_options['attributes'] as $attribute_code => $attribute_details) {
                if (isset($attribute_details['options'])) {
                    $updatedOptions = array();
                    foreach ($attribute_details['options'] as $option_key => $option_data) {
                        if (
                            isset($option_data['products']) &&
                            is_array($option_data['products']) &&
                            count($option_data['products']) != 0
                        ) {
                            $updatedOptions[] = $option_data;
                        }
                    }
                    $attribute_details['options'] = $updatedOptions;
                    $configurable_options['attributes'][$attribute_code] = $attribute_details;
                }
            }
        }

        $options['configurable_options'] = $configurable_options;

        if (!($product->getOptions() === null) && $this->simiObjectManager
                ->get('Simi\Simiconnector\Helper\Data')->countArray($product->getOptions())) {
            $custom_options            = $this
                    ->helper('Simi\Simiconnector\Helper\Options\Simple')->getOptions($product);
            $options['custom_options'] = $custom_options['custom_options'];
        }
        return $options;
    }
}
