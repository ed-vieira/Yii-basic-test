<?php

namespace app\models\definitions;

/**
 * @SWG\Definition(required={"name", "price", "customer_id"})
 * @SWG\Property(property="id", type="integer")
 * @SWG\Property(property="name", type="string")
 * @SWG\Property(property="price", type="integer")
 * @SWG\Property(property="photo", type="string")
 * @SWG\Property(property="customer_id", type="integer")
 * @SWG\Property(property="created_at", type="string")
 * @SWG\Property(property="updated_at", type="string")
 */
class Product {}