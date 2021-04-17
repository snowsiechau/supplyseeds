<?php

namespace Simi\Simicustomize\Observer;

use Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\ObjectManagerInterface as ObjectManager;

class Frontendcontrollerpredispatch implements ObserverInterface
{
    private $simiObjectManager;

    public function __construct(ObjectManager $simiObjectManager)
    {
        $this->simiObjectManager = $simiObjectManager;
    }

    public function execute(Observer $observer)
    {
        $excludedUrls = array('admin', 'simiconnector', 'simicustompayment',
            'rest/v2', 'rest/V1', 'graphql', 'admin_141kw6', 'simicustomize', 'tap', 'landingpage', 'b2b', 'createPassword', 'createpassword', 'forgotpassword'
        );
        $uri = $_SERVER['REQUEST_URI'];

        $isExcludedCase = false;
        foreach ($excludedUrls as $key => $excludedUrl) {
            if ($excludedUrl != '' && (strpos($uri, $excludedUrl) !== false)) {
                $isExcludedCase = true;
            }
        }
        // if ($uri === '/') {
        //     $isExcludedCase = true;
        // }
        // if (!$isExcludedCase) {
        //     $url = 'http://supplyseeds.com/landingpage/index.html';
        //     header('Location: '.$url);
        //     exit;
        // }
    }
}
