<?php
namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Select;
// Validation
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Identical;


class ItemsForm extends Form
{
    public function initialize()
    {
        /**
         * quantity
         */
        $qunatity = new Numeric('quantity', [
            "class" => "form-control",
            "placeholder" => "Quantity"
        ]);

        $qunatity->addValidators([
            new PresenceOf(['message' => 'Quantity is required']),
            new Numericality(['message' => 'Quantity must be number']),
        ]);

         /**
         * item Price 
         */
        $itemPrice = new Text('item_price', [
            "class" => "form-control",
            "placeholder" => "Quantity",
            "value" => round(rand(10,15)/rand(1,3),2),
            'readonly' => 'readonly '
        ]);

        $itemPrice->addValidators([
            new PresenceOf(['message' => 'Price is required']),
            new Numericality(['message' => 'Price must be a number']),
        ]);
        
        /**
         * item name 
         */
        $item = new Select(
            'item_name',
            ['pizza'=>'PIZZA','pasta' => "PASTA",'grill'=> 'GRILL','curry'=> "CURRY"],
            [
                'using' => [
                    'id',
                    'name'
                ],
                'useEmpty' => true,
                'emptyText' => 'Select One',
                'emptyValue' => '',
                "class" => "form-control",
                
            ]
            );
          $item->addValidators([
                new PresenceOf(['message' => 'Name is required']),
            ]);
        $this->add($item);

        /**
         * Submit Button
         */
        $submit = new Submit('submit', [
            "value" => "Save",
            "class" => "btn btn-secondary",
        ]);

        $orderId = new Hidden('order_id');
        $id = new Hidden('id');
        
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        ]));
        $this->add($qunatity);
        $this->add($itemPrice);
        $this->add($orderId);
        $this->add($id);
        $this->add($csrf);
        $this->add($submit);
    }
}