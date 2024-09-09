<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PhoneNumberTransformer implements DataTransformerInterface
{
    /**
     * Transforms a phone number from the model to a format suitable for the view.
     *
     * @param mixed $value The phone number to transform
     * @return mixed The transformed phone number or an empty string if null
     */
    public function transform(mixed $value): mixed
    {
        // Retourne le numéro de téléphone formaté pour l'affichage
        return $value ? $this->formatPhoneNumber((string) $value) : '';
    }

    /**
     * Transforms a phone number from the view to a format suitable for the model.
     *
     * @param mixed $value The phone number to transform
     * @return mixed The transformed phone number or null if empty
     */
    public function reverseTransform(mixed $value): mixed
    {
        // Si la valeur est null ou vide, retourne null
        if ($value === null || trim($value) === '') {
            return null;
        }

        // Retire les espaces
        $value = str_replace(' ', '', $value);

        // Assure que le numéro commence par un zéro
        if (strlen($value) > 0 && $value[0] !== '0') {
            $value = '0' . $value;
        }

        return $value;
    }

    /**
     * Format the phone number with spaces for better readability.
     *
     * @param string $phoneNumber The phone number to format
     * @return string The formatted phone number
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Formate le numéro de téléphone pour l'affichage avec des espaces
        // Enlève tout ce qui n'est pas un chiffre pour éviter les problèmes
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
        // Formate avec des espaces après chaque groupe de 2 chiffres
        return preg_replace('/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $phoneNumber);
    }
}