<?php

namespace MDS\Collivery\Api;

use Magento\Checkout\Model\Cart;

class CustomAttributes
{
    private $cart;

    public function __construct(
        Cart $cart
    ) {
        $this->cart = $cart;
    }

    /**
     * @return void
     */
    public function insertCustomAttributes()
    {
        $quote = $this->cart->getQuote();

        $data = [
            'location' => $_GET['location'],
            'town' => $_GET['town'],
            'suburb' => $_GET['suburb']
        ];

        if (isset($_GET['address_type']) && $_GET['address_type'] == 'shipping_address') {
            $addresses = [$quote->getShippingAddress()];
        } elseif (isset($_GET['address_type']) && $_GET['address_type'] == 'billing_address') {
            $addresses = [$quote->getBillingAddress()];
        } else {
            $addresses = [$quote->getShippingAddress(), $quote->getBillingAddress()];
        }

        foreach ($addresses as $address) {
            $address->load($address->getAddressId())->addData($data);
            $address->setId($address->getAddressId())->save();
        }
    }
}
