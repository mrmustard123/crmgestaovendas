<?php

/*
File: EnumType
Author: Leonardo G. Tellez Saucedo
Created on: 17 jul. de 2025 18:33:10
Email: leonardo616@gmail.com
*/

namespace App\Models\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Custom DBAL type for MySQL ENUMs.
 */
class EnumType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        // En una migración 'diff', Doctrine intentará generar el ENUM como un string.
        // Aquí puedes personalizar el SQL si lo necesitas, pero para un mapeo simple
        // para que no falle el diff, podemos dejarlo simple.
        // El tipo "ENUM" en MySQL se define con una lista de valores, por ejemplo:
        // ENUM('valor1', 'valor2', 'valor3')
        // Si tu columna ENUM en la DB tiene una definición específica (ej. activity.status),
        // este método debería reflejar eso.
        // Para el propósito de que diff funcione, podemos devolver 'string' o un VARCHAR genérico.
        // Sin embargo, para que 'diff' *no* intente cambiar la columna si ya es ENUM,
        // necesitamos que Doctrine lo reconozca.
        // Para este error, simplemente necesitamos que no lo considere "desconocido".
        return 'VARCHAR(' . ($column['length'] ?? 255) . ')'; // O podriaponer un valor arbitrario grande.
    }

    public function getName(): string
    {
        // Este es el nombre interno que Doctrine usará para este tipo.
        // Debe ser 'enum' para que mapee directamente el tipo 'enum' de MySQL.
        return 'my_enum';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        // Cuando Doctrine lee un valor de la base de datos, lo pasa aquí.
        // Puedes convertirlo a un objeto PHP si lo necesitas, pero para ENUMs simples
        // que se mapean a strings, simplemente lo devuelves como está.
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        // Cuando Doctrine escribe un valor a la base de datos, lo pasa aquí.
        // Simplemente lo devolvemos como está, ya que se espera un string.
        return $value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        // No es necesario un comentario SQL adicional para este tipo.
        return true; // Puede ser false también, no es crítico para este problema.
    }
}


