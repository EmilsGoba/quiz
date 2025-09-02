<?php 
class Validator {
    public static function string($value, $min = 1, $max = INF) {
        $value = trim($value);

        return is_string($value)
            && mb_strlen($value, 'UTF-8') >= $min
            && mb_strlen($value, 'UTF-8') <= $max;
    }

    public static function alphaOnly($value) {
        // Allows letters from any language, but no digits
        return preg_match('/^\p{L}+(?:[\p{L}\s]*)$/u', $value);
    }

    public static function name($value) {
        $value = trim($value);
        // Allow Unicode letters, hyphens, apostrophes, and spaces. No digits or other symbols.
        return preg_match('/^[\p{L}\p{M}\'\- ]+$/u', $value);
    }

    public static function grade($value) {
        return is_numeric($value) && $value >= 1 && $value <= 10;
    }
}
