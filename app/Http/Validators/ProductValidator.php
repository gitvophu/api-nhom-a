<?php
/**
 * Created by PhpStorm.
 * User: phong
 * Date: 4/22/2019
 * Time: 3:28 PM
 */
use Event;
use App\Http\Models\Product;
use Illuminate\Support\MessageBag as MessageBag;
class ProductValidator extends \Illuminate\Support\Facades\Validator
{
    protected $obj_product;

    public function __construct()
    {
        // add rules
        self::$rules = [
            'product_name' => ["required"],
            'product_overview' => ["required"],
            'product_description' => ["required"],
        ];

        // set configs
        self::$configs = $this->loadConfigs();

        // model
        $this->obj_product = new Product();

        // language
        $this->lang_front = 'product-front';
        $this->lang_admin = 'product-admin';

        // event listening
        Event::listen('validating', function($input)
        {
            self::$messages = [
                'product_name.required'          => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.name')]),
                'product_overview.required'      => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.overview')]),
                'product_description.required'   => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.description')]),
            ];
        });


    }

    /**
     *
     * @param ARRAY $input is form data
     * @return type
     */
    public function validate($input) {

        $flag = parent::validate($input);
        $this->errors = $this->errors ? $this->errors : new MessageBag();

        //Check length
        $_ln = self::$configs['length'];

        $params = [
            'name' => [
                'key' => 'product_name',
                'label' => trans($this->lang_admin.'.fields.name'),
                'min' => $_ln['product_name']['min'],
                'max' => $_ln['product_name']['max'],
            ],
            'overview' => [
                'key' => 'product_overview',
                'label' => trans($this->lang_admin.'.fields.overview'),
                'min' => $_ln['product_overview']['min'],
                'max' => $_ln['product_overview']['max'],
            ],
            'description' => [
                'key' => 'product_description',
                'label' => trans($this->lang_admin.'.fields.description'),
                'min' => $_ln['product_description']['min'],
                'max' => $_ln['product_description']['max'],
            ],
        ];

        $flag = $this->isValidLength($input['product_name'], $params['name']) ? $flag : FALSE;
        $flag = $this->isValidLength($input['product_overview'], $params['overview']) ? $flag : FALSE;
        $flag = $this->isValidLength($input['product_description'], $params['description']) ? $flag : FALSE;

        return $flag;
    }

}