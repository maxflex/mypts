<?php

function extract_fields($object, $fields, $merge = [])
{
    $return = ['id' => $object->id];
    foreach ($fields as $field) {
        $return[$field] = $object->{$field};
    }
    return array_merge($return, $merge);
}
