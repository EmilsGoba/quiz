<?php 
class Validator {
    public static function string($value, $min = 1, $max = INF) {
        $value = trim($value);

        return is_string($value)
            && mb_strlen($value, 'UTF-8') >= $min
            && mb_strlen($value, 'UTF-8') <= $max;
    }

    public static function alphaOnly($value) {
        // Allows letters from any language, plus spaces (kept for backward compat; not used here)
        return preg_match('/^\p{L}+(?:[\p{L}\s]*)$/u', $value);
    }

    public static function name($value) {
        $value = trim($value);
        // Allows letters, hyphens, apostrophes, spaces (kept for backward compat; not used here)
        return preg_match('/^[\p{L}\p{M}\'\- ]+$/u', $value);
    }

    public static function grade($value) {
        return is_numeric($value) && $value >= 1 && $value <= 10;
    }

    /**
     * Letters-only (Unicode) with length bounds.
     * No spaces, numbers, or symbols. Max 30 chars by default.
     */
    public static function lettersOnly($value, $min = 1, $max = 30) {
        $value = trim((string)$value);
        $len = mb_strlen($value, 'UTF-8');
        if ($len < $min || $len > $max) {
            return false;
        }
        // Letters only (Unicode). Disallows spaces and any other chars.
        return (bool) preg_match('/^\p{L}+$/u', $value);
    }

    /**
     * Full validator for topic name used by createtopics.php
     * Returns an error message string, or null if OK.
     */
    public static function validateTopicName($value) {
        $value = trim((string)$value);
        if ($value === '') {
            return "Topic name is required.";
        }
        if (mb_strlen($value, 'UTF-8') > 30) {
            return "Topic name must be at most 30 characters.";
        }
         if (!preg_match('/^[\p{L}\p{M}]+(?:\s[\p{L}\p{M}]+)*$/u', $value)) {
        return "Topic name can contain letters and spaces only (no numbers or symbols).";
    }
        return null;
    }

    /**
     * Simple uniqueness helper. Assumes $table and $column are trusted identifiers.
     * Returns true if the value does NOT exist yet.
     */
    public static function unique($db, $table, $column, $value) {
        $sql = "SELECT COUNT(*) AS c FROM {$table} WHERE {$column} = :v";
        $row = $db->query($sql, ["v" => $value])->fetch();
        return empty($row["c"]);
    }
}
