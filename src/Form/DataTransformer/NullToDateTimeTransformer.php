<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class NullToDateTimeTransformer implements DataTransformerInterface
{
    // Cette méthode transforme la donnée de l'objet en une valeur que le formulaire peut afficher
    public function transform(mixed $value): mixed
    {
        // Si la valeur est null, on retourne null
        if ($value === null) {
            return null;
        }

        // Si la valeur n'est pas un objet \DateTime, on renvoie une exception
        if (!$value instanceof \DateTimeInterface) {
            throw new \UnexpectedValueException('Expected a \DateTimeInterface object.');
        }

        // Sinon, on retourne la valeur (qui est un objet \DateTime)
        return $value;
    }

    // Cette méthode transforme la donnée soumise par le formulaire en une valeur qui sera stockée dans l'entité
    public function reverseTransform(mixed $value): mixed
    {
        // Si la valeur est une chaîne vide, on retourne null
        if ($value === null || $value === '') {
            return null;
        }

        // Si la valeur n'est pas un objet \DateTime, on renvoie une exception
        if (!$value instanceof \DateTimeInterface) {
            throw new \UnexpectedValueException('Expected a \DateTimeInterface object.');
        }

        return $value;
    }
}