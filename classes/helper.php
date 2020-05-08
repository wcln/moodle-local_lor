<?php

namespace local_lor;

class helper
{

    /**
     * Implode an array using a specific column
     *
     * @param  array  $array
     * @param  string  $column
     *
     * @return string
     * @throws \coding_exception
     */
    public static function implode_format(array $array, string $column = 'name')
    {
        return (empty($array))
            ?
            get_string('none', 'local_lor')
            : implode(
                ', ',
                array_column(
                    $array,
                    $column
                )
            );
    }
}
