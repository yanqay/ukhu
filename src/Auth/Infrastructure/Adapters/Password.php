<?php

namespace App\Auth\Infrastructure\Adapters;

/**
 * Generates a strong password
 * 
 * Password has N length containing at least one lower case letter,
 * one uppercase letter, one digit, and one special character. The remaining characters
 * in the password are chosen at random from those four sets.
 *
 * The available characters in each set are user friendly - there are no ambiguous
 * characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
 * makes it much easier for users to manually type or speak their passwords.
 *
 * Note: the $add_dashes option will increase the length of the password by
 * floor(sqrt(N)) characters.
 */
class Password
{
    private $password;
    private $length;
    private $add_dashes;
    private $available_sets;
    const PASSWORD_SALT = 'changethisprettylongkeytowhateveryouwant';

    public function __construct()
    {
        $this->length = 9;
        $this->add_dashes = false;
        $this->available_sets = 'luds';
        $this->password = $this->generate();
    }

    public function get()
    {
        return $this->password ?? false;
    }

    function generate()
    {
        $sets = array();
        if (strpos($this->available_sets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (strpos($this->available_sets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (strpos($this->available_sets, 'd') !== false) {
            $sets[] = '23456789';
        }
        if (strpos($this->available_sets, 's') !== false) {
            $sets[] = '!@#$%&*?';
        }

        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $this->length - count($sets); $i++)
            $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if (!$this->add_dashes)
            return $password;

        $dash_len = floor(sqrt($this->length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    public static function hashPassword($password)
    {
        return hash('sha256', self::PASSWORD_SALT . $password);
    }
}
