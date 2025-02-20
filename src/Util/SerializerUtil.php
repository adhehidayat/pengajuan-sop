<?php

namespace App\Util;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerUtil
{
    /**
     * @throws ExceptionInterface
     */
    public static function normalize(mixed $data, ?string $format = null, array $context = []): float|array|\ArrayObject|bool|int|string|null
    {
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer]);

        return $serializer->normalize($data, $format, $context);
    }
}
