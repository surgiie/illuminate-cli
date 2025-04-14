<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute input must be accepted.',
    'accepted_if' => 'The :attribute input must be accepted when :other is :value.',
    'active_url' => 'The :attribute input must be a valid URL.',
    'after' => 'The :attribute input must be a date after :date.',
    'after_or_equal' => 'The :attribute input must be a date after or equal to :date.',
    'alpha' => 'The :attribute input must only contain letters.',
    'alpha_dash' => 'The :attribute input must only contain letters, numbers, dashes, and underscores.',
    'alpha_num' => 'The :attribute input must only contain letters and numbers.',
    'array' => 'The :attribute input must be an array.',
    'ascii' => 'The :attribute input must only contain single-byte alphanumeric characters and symbols.',
    'before' => 'The :attribute input must be a date before :date.',
    'before_or_equal' => 'The :attribute input must be a date before or equal to :date.',
    'between' => [
        'array' => 'The :attribute input must have between :min and :max items.',
        'file' => 'The :attribute input must be between :min and :max kilobytes.',
        'numeric' => 'The :attribute input must be between :min and :max.',
        'string' => 'The :attribute input must be between :min and :max characters.',
    ],
    'boolean' => 'The :attribute input must be true or false.',
    'can' => 'The :attribute input contains an unauthorized value.',
    'confirmed' => 'The :attribute input confirmation does not match.',
    'contains' => 'The :attribute input is missing a required value.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute input must be a valid date.',
    'date_equals' => 'The :attribute input must be a date equal to :date.',
    'date_format' => 'The :attribute input must match the format :format.',
    'decimal' => 'The :attribute input must have :decimal decimal places.',
    'declined' => 'The :attribute input must be declined.',
    'declined_if' => 'The :attribute input must be declined when :other is :value.',
    'different' => 'The :attribute input and :other must be different.',
    'digits' => 'The :attribute input must be :digits digits.',
    'digits_between' => 'The :attribute input must be between :min and :max digits.',
    'dimensions' => 'The :attribute input has invalid image dimensions.',
    'distinct' => 'The :attribute input has a duplicate value.',
    'doesnt_end_with' => 'The :attribute input must not end with one of the following: :values.',
    'doesnt_start_with' => 'The :attribute input must not start with one of the following: :values.',
    'email' => 'The :attribute input must be a valid email address.',
    'ends_with' => 'The :attribute input must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'extensions' => 'The :attribute input must have one of the following extensions: :values.',
    'file' => 'The :attribute input must be a file.',
    'filled' => 'The :attribute input must have a value.',
    'gt' => [
        'array' => 'The :attribute input must have more than :value items.',
        'file' => 'The :attribute input must be greater than :value kilobytes.',
        'numeric' => 'The :attribute input must be greater than :value.',
        'string' => 'The :attribute input must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'The :attribute input must have :value items or more.',
        'file' => 'The :attribute input must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute input must be greater than or equal to :value.',
        'string' => 'The :attribute input must be greater than or equal to :value characters.',
    ],
    'hex_color' => 'The :attribute input must be a valid hexadecimal color.',
    'image' => 'The :attribute input must be an image.',
    'in' => 'The selected :attribute input is invalid.',
    'in_array' => 'The :attribute input must exist in :other.',
    'integer' => 'The :attribute input must be an integer.',
    'ip' => 'The :attribute input must be a valid IP address.',
    'ipv4' => 'The :attribute input must be a valid IPv4 address.',
    'ipv6' => 'The :attribute input must be a valid IPv6 address.',
    'json' => 'The :attribute input must be a valid JSON string.',
    'list' => 'The :attribute input must be a list.',
    'lowercase' => 'The :attribute input must be lowercase.',
    'lt' => [
        'array' => 'The :attribute input must have less than :value items.',
        'file' => 'The :attribute input must be less than :value kilobytes.',
        'numeric' => 'The :attribute input must be less than :value.',
        'string' => 'The :attribute input must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'The :attribute input must not have more than :value items.',
        'file' => 'The :attribute input must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute input must be less than or equal to :value.',
        'string' => 'The :attribute input must be less than or equal to :value characters.',
    ],
    'mac_address' => 'The :attribute input must be a valid MAC address.',
    'max' => [
        'array' => 'The :attribute input must not have more than :max items.',
        'file' => 'The :attribute input must not be greater than :max kilobytes.',
        'numeric' => 'The :attribute input must not be greater than :max.',
        'string' => 'The :attribute input must not be greater than :max characters.',
    ],
    'max_digits' => 'The :attribute input must not have more than :max digits.',
    'mimes' => 'The :attribute input must be a file of type: :values.',
    'mimetypes' => 'The :attribute input must be a file of type: :values.',
    'min' => [
        'array' => 'The :attribute input must have at least :min items.',
        'file' => 'The :attribute input must be at least :min kilobytes.',
        'numeric' => 'The :attribute input must be at least :min.',
        'string' => 'The :attribute input must be at least :min characters.',
    ],
    'min_digits' => 'The :attribute input must have at least :min digits.',
    'missing' => 'The :attribute input must be missing.',
    'missing_if' => 'The :attribute input must be missing when :other is :value.',
    'missing_unless' => 'The :attribute input must be missing unless :other is :value.',
    'missing_with' => 'The :attribute input must be missing when :values is present.',
    'missing_with_all' => 'The :attribute input must be missing when :values are present.',
    'multiple_of' => 'The :attribute input must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute input format is invalid.',
    'numeric' => 'The :attribute input must be a number.',
    'password' => [
        'letters' => 'The :attribute input must contain at least one letter.',
        'mixed' => 'The :attribute input must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute input must contain at least one number.',
        'symbols' => 'The :attribute input must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'The :attribute input must be present.',
    'present_if' => 'The :attribute input must be present when :other is :value.',
    'present_unless' => 'The :attribute input must be present unless :other is :value.',
    'present_with' => 'The :attribute input must be present when :values is present.',
    'present_with_all' => 'The :attribute input must be present when :values are present.',
    'prohibited' => 'The :attribute input is prohibited.',
    'prohibited_if' => 'The :attribute input is prohibited when :other is :value.',
    'prohibited_if_accepted' => 'The :attribute input is prohibited when :other is accepted.',
    'prohibited_if_declined' => 'The :attribute input is prohibited when :other is declined.',
    'prohibited_unless' => 'The :attribute input is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute input prohibits :other from being present.',
    'regex' => 'The :attribute input format is invalid.',
    'required' => 'The :attribute input is required.',
    'required_array_keys' => 'The :attribute input must contain entries for: :values.',
    'required_if' => 'The :attribute input is required when :other is :value.',
    'required_if_accepted' => 'The :attribute input is required when :other is accepted.',
    'required_if_declined' => 'The :attribute input is required when :other is declined.',
    'required_unless' => 'The :attribute input is required unless :other is in :values.',
    'required_with' => 'The :attribute input is required when :values is present.',
    'required_with_all' => 'The :attribute input is required when :values are present.',
    'required_without' => 'The :attribute input is required when :values is not present.',
    'required_without_all' => 'The :attribute input is required when none of :values are present.',
    'same' => 'The :attribute input must match :other.',
    'size' => [
        'array' => 'The :attribute input must contain :size items.',
        'file' => 'The :attribute input must be :size kilobytes.',
        'numeric' => 'The :attribute input must be :size.',
        'string' => 'The :attribute input must be :size characters.',
    ],
    'starts_with' => 'The :attribute input must start with one of the following: :values.',
    'string' => 'The :attribute input must be a string.',
    'timezone' => 'The :attribute input must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'uppercase' => 'The :attribute input must be uppercase.',
    'url' => 'The :attribute input must be a valid URL.',
    'ulid' => 'The :attribute input must be a valid ULID.',
    'uuid' => 'The :attribute input must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
