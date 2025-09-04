<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Censurator
{

    public function __construct(private readonly ContainerBagInterface $params)
    {


    }


    public function purify(?string $text): string
    {
        if ($text === null) {
            return '';
        }

        $filename = $this->params->get('app.censurator_filename');

        if (file_exists($filename)) {
            $words = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($words as $word) {
                $word = trim($word);
                $replacement = str_repeat("*", mb_strlen($word));
                $text = preg_replace('/\b' . preg_quote($word, '/') . '\b/ui', $replacement, $text);
            }
        }

        return $text;
    }
}
