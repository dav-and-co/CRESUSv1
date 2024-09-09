<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PhoneNumberExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('format_phone_number', [$this, 'formatPhoneNumber']),
        ];
    }

    public function formatPhoneNumber(string $phoneNumber): string
    {
        // Si le numéro est null ou vide, retourner une chaîne vide
        if (empty($phoneNumber)) {
            return '';
        }

        // Ajoute des espaces pour une meilleure lisibilité
        return preg_replace('/(\d{2})(?=\d)/', '$1 ', $phoneNumber);
    }
}