<?php

namespace Simi\Simicustomize\Plugin\Backend\Block\Widget\Button\Toolbar;

use Magento\Backend\Block\Widget\Button\ButtonList;
use Magento\Backend\Block\Widget\Button\Toolbar as ToolbarContext;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\AbstractBlock;

class AddProcessingButtonPlugin {
	/**
	 * @var Registry
	 */
	private $coreRegistry;
	/**
	 * @var \Magento\Framework\Authorization\PolicyInterface
	 */
	private $policyInterface;
	/**
	 * @var \Magento\Backend\Model\Auth\Session
	 */
	private $adminSession;

	/**
	 * AddRmaButtonPlugin constructor.
	 *
	 * @param Registry                                         $coreRegistry
	 * @param \Magento\Framework\Authorization\PolicyInterface $policyInterface
	 * @param \Magento\Backend\Model\Auth\Session              $adminSession
	 */
	public function __construct(
		Registry $coreRegistry,
		\Magento\Framework\Authorization\PolicyInterface $policyInterface,
		\Magento\Backend\Model\Auth\Session $adminSession
	) {
		$this->coreRegistry    = $coreRegistry;
		$this->policyInterface = $policyInterface;
		$this->adminSession    = $adminSession;
	}

	/**
	 * @param ToolbarContext $toolbar
	 * @param AbstractBlock  $context
	 * @param ButtonList     $buttonList
	 *
	 * @return array
	 */
	public function beforePushButtons(
		ToolbarContext $toolbar,
		\Magento\Framework\View\Element\AbstractBlock $context,
		\Magento\Backend\Block\Widget\Button\ButtonList $buttonList
	) {
		if ( ! $context instanceof \Magento\Sales\Block\Adminhtml\Order\View ) {
			return [ $context, $buttonList ];
		}

		$order                 = $this->getOrder();
		if ( $order->getStatus() == 'canceled' || $order->getStatus() == 'failed') {
			$buttonList->add( 'processing_order_change_status',
				[
					'label'      => __( 'Processing' ),
					'onclick'    => 'setLocation(\'' . $context->getUrl( 'simicustomize/order/processing/' ) . '\')',
					'class'      => 'processing_status',
					'sort_order' => ( count( $buttonList->getItems() ) + 1 ) * 10,
				]
			);
		}

		return [ $context, $buttonList ];
	}

	/**
	 * @return \Magento\Sales\Model\Order
	 */
	private function getOrder() {
		return $this->coreRegistry->registry( 'sales_order' );
	}
}