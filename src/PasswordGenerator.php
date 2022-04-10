<?php

namespace OutOfOffice\PasswordGenerator;

use Laravel\Nova\Fields\Field;

class PasswordGenerator extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'password-generator';
}
