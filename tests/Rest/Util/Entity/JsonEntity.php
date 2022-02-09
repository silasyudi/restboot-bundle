<?php

namespace Tests\Rest\Util\Entity;

class JsonEntity
{

    public static function getPersonWithArrayWithKeysAndWithoutAddressAndBoolTrue(): string
    {
        return <<<PERSON
            {
                "name": "Silas",
                "age": 30,
                "male": true,
                "birtydate": "2000-01-01",
                "phones": {
                    "personal": "99999-9999",
                    "work": "88888-8888"
                }
            }
        PERSON;
    }

    public static function getPersonWithArrayWithoutKeysAndWithAddressAndBoolFalseAndExplicitNullValue(): string
    {
        return <<<PERSON
            {
                "name": "Carol",
                "age": 30,
                "male": false,
                "birtydate": "2000-01-01T00:00:00-02:00",
                "address": {
                    "street": "Large Avenue",
                    "number": 123,
                    "complement": null
                },
                "phones": ["99999-9999","88888-8888"]
            }
        PERSON;
    }

    public static function getAddressWithoutComplement(): string
    {
        return <<<PERSON
            {
                "street": "Large Avenue",
                "number": 123
            }
        PERSON;
    }
}
