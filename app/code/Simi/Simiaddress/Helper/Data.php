<?php


namespace Simi\Simiaddress\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    public $simiObjectManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $simiObjectManager
    ) {

        $this->simiObjectManager = $simiObjectManager;
        parent::__construct($context);
    }

}
