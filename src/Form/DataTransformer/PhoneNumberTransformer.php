<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PhoneNumberTransformer implements DataTransformerInterface
{
    // Cette méthode prend une valeur (le numéro de téléphone) et retourne une chaîne de caractères
    public function transform($value): string
    {
        if (!$value) {
            return '';
        }

        // Ajouter des espaces tous les deux chiffres pour le format d'affichage
        return substr($value, 0, 2) . ' ' . substr($value, 2, 2) . ' ' . substr($value, 4, 2) . ' ' . substr($value, 6, 2) . ' ' . substr($value, 8, 2);
    }

    // Cette méthode prend une chaîne de caractères (le numéro avec espaces) et retourne une chaîne sans espaces
    public function reverseTransform($value): mixed
    {
        // Supprimer les espaces avant de soumettre
        return str_replace(' ', '', $value);
    }
}