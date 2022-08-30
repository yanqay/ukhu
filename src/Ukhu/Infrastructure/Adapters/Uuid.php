<?php

namespace App\Ukhu\Infrastructure\Adapters;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    public static function binaryToStringUUID($binaryUUID) : string
    {
        $string = unpack("H*", $binaryUUID);

        $uuidArray = preg_replace(
            "/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/", 
            "$1-$2-$3-$4-$5", 
            $string
        );

        return array_pop($uuidArray);
    }

    public static function stringUUIDToBinary(string $id)
    {
        self::assertIsValidStringUuid($id);
        return pack("H*", str_replace('-', '', $id));
    }

    public static function assertIsValidStringUuid(string $id) : bool
    {
        if (!RamseyUuid::isValid($id)){
            throw new \Exception("Invalid string UUID");
        }
        return true;
    }

    public static function generateStringUuid() : string
    {
        return RamseyUuid::uuid4()->toString();
    }
}
