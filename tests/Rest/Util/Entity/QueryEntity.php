<?php

namespace Tests\Rest\Util\Entity;

class QueryEntity
{
    public static function getPersonWithArrayWithKeysAndWithoutAddressAndBoolCastFromOne(): string
    {
        return '?name=Silas&age=30&male=1&birtydate=2000-01-01&phones[personal]=99999-9999&phones[work]=88888-8888';
    }

    public static function getPersonWithArrayWithoutKeysAndWithAddressAndBoolCastFromZeroAndExplicitEmptyValue(): string
    {
        return '?name=Carol&age=30&male=0&birtydate=2000-01-01&phones[0]=99999-9999&phones[1]=88888-8888'
            . '&address[street]=Large%20Avenue&address[number]=123&address[complement]=';
    }

    public static function getAddressWithoutComplement(): string
    {
        return '?street=Large%20Avenue&number=123';
    }
}
