<?php

namespace app\models\definitions;

/**
 * @SWG\Definition(required={"username", "email"})
 *
 * @SWG\Property(property="id", type="integer")
 * @SWG\Property(property="name", type="string")
 * @SWG\Property(property="cpf", type="string")
 * @SWG\Property(property="photo", type="string")
 * @SWG\Property(property="gender", type="string")
 * @SWG\Property(property="post_code", type="string")
 * @SWG\Property(
 *   property="address", type="object",
 *   @SWG\Property(property="country", type="integer"),
 *   @SWG\Property(property="state", type="string"),
 *   @SWG\Property(property="city", type="string"),
 *   @SWG\Property(property="street", type="string"),
 *   @SWG\Property(property="number", type="string"),
 *   @SWG\Property(property="complement", type="string"), 
 * )

 */
class Customer {}