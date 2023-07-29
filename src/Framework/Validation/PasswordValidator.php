<?php
class PasswordValidator
{
    const MIN_LENGTH = 8;

    public static function validate($password, $confirmPassword)
    {
        self::doPasswordsMatch($password, $confirmPassword);
        self::validateLength($password);
        self::validateUppercase($password);
        self::validateNumber($password);
        self::validateSpecialCharacter($password);
    }

    public static function doPasswordsMatch($password, $confirmPassword)
    {
        return $password === $confirmPassword;
    }

    private static function validateLength($password)
    {
        if (strlen($password) < self::MIN_LENGTH) {
            throw new InvalidPasswordLengthException();
        }
    }

    private static function validateUppercase($password)
    {
        if (!preg_match('/[A-Z]/', $password)) {
            throw new MissingUppercaseCharacterException();
        }
    }

    private static function validateNumber($password)
    {
        if (!preg_match('/\d/', $password)) {
            throw new MissingNumberException();
        }
    }

    private static function validateSpecialCharacter($password)
    {
        if (!preg_match('/[^a-zA-Z\d]/', $password)) {
            throw new MissingSpecialCharacterException();
        }
    }
}