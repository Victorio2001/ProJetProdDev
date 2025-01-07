<?php

namespace BibliOlen\Tools;

class Tools
{
    /**
     * Hash a password with the chosen algorithm
     * @param string $password
     * @param string $algo
     * @return string
     */
    public static function hashPassword(string $password, string $algo = PASSWORD_DEFAULT): string
    {
        return password_hash($password, $algo);
    }

    /**
     * Verify a password with the chosen algorithm
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Generate a random string
     * @param int $length
     * @return string
     */
    public static function generateRandomString(int $length = 10): string
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    /**
     * Generate a random temporary password
     * @return string
     */
    public static function generateRandomTemporaryPassword(): string
    {    
        $majuscules = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $minuscules = 'abcdefghijklmnopqrstuvwxyz';
        $chiffres = '0123456789';
        $speciaux = '!@#$%^&*()-_+=';
        $motDePasse = 
        $majuscules[random_int(0, strlen($majuscules) - 1)] . 
        $minuscules[random_int(0, strlen($minuscules) - 1)] . 
        $chiffres[random_int(0, strlen($chiffres) - 1)] . 
        $speciaux[random_int(0, strlen($speciaux) - 1)];
        $allChars = $majuscules . $minuscules . $chiffres . $speciaux;
        $length = 10;
        for ($i = 0; $i < $length; $i++) {
            $motDePasse .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        return Tools::hashPassword(str_shuffle($motDePasse));
    }

    /**
     * Remove accents from a string
     * @param string $s
     * @return string
     */
    public static function removeAccents(string $s): string
    {
        $str = htmlentities($s, ENT_NOQUOTES, 'UTF-8');

        $str = preg_replace('#&([A-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-z]{2})lig;#', '\1', $str);

        return preg_replace('#&[^;]+;#', '', $str);
    }

    /**
     * Truncate a string
     * @param string $s
     * @param int $length
     * @return string
     */
    public static function truncateStr(string $s, int $length): string
    {
        if (strlen($s) <= $length) {
            return $s;
        }

        $text = $s . " ";
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));

        return $text . "...";
    }

    public static function dd($var): void
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        die();
    }

    public static function d($var): void
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    public static function getFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    public static function getFileFromRoot(array $paths): string
    {
        $root = getcwd();
        array_unshift($paths, $root);
        return implode(DIRECTORY_SEPARATOR, $paths);
    }
}
