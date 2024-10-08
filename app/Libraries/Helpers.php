<?php

namespace App\Libraries;

class Helpers
{
    /**
     * @return mixed|null
     */
    public static function enhance($object, $language)
    {
        if (json_decode($object) == null || !isset(json_decode($object)->ar)) {
            return null;
        }

        $user_languages = config('user.languages');
        $alter_lang = ($language == 'ar') ? 'en' : 'ar';

        if (isset($language) && array_search($language, $user_languages) !== false) {
            $result = json_decode($object)->$language;

            if (strlen($result) == 0) {
                $result = json_decode($object)->$alter_lang;
            }
        } else {
            $result = json_decode($object);

            if (isset($result->ar) && strlen($result->ar) > 0 && (!isset($result->en) || strlen($result->en) == 0)) {
                $result->en = $result->ar;
            } elseif (isset($result->en) && strlen($result->en) > 0 && (!isset($result->ar) || strlen($result->ar) == 0)) {
                $result->ar = $result->en;
            }
        }

        return $result;
    }


    /**
     * @param $object
     * @return false|string|null
     */
    public static function insertObject($object): false|string|null
    {
        if (json_decode($object) == null || !isset(json_decode($object)->ar)) {
            return null;
        }

        $result = [
            'ar' => $object->ar,
            'en' => $object->en,
        ];

        return json_encode($result);
    }

    /**
     * Get media type from MIME type.
     *
     * @param string $mimeType
     * @return string
     */
    public static function getMediaTypeFromMimeType(string $mimeType): string
    {
        // Map MIME types to media types
        $mediaTypeMap = [
            'image/jpeg' => 'image',
            'image/png' => 'image',
            'image/gif' => 'image',
            'video/mp4' => 'video',
            'audio/mpeg' => 'audio',
            // Add more MIME types and their corresponding media types as needed
        ];

        return $mediaTypeMap[$mimeType] ?? 'unknown'; // Default to 'unknown' if MIME type is not mapped
    }

}
