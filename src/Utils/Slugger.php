<?php
namespace App\Utils;

use Doctrine\Common\Persistence\ObjectRepository;

class Slugger
{
    /**
     * Returns a hyphenated slug in lowercase.
     * @param string $value
     * @param bool $underscore
     * Replaces dashes with underscores in the generated slug if true.
     * @return string
     */
    public function generateSlug(string $value, bool $underscore = false): string
    {
        $spaceReplacer = $underscore ? '_' : '-';
        $replaceCharacters = str_replace(
            ['æ', 'ø', 'å'],
            ['ae', 'oe', 'aa'],
            $value
        );
        $noSpaces = str_replace(' ', $spaceReplacer, $replaceCharacters);
        $alphaNumericOnly = preg_replace('/[^0-9a-zA-Z-_]/', '', $noSpaces);
        $trimmed = trim($alphaNumericOnly, $spaceReplacer);
        $withoutRepeatingDashes = preg_replace('/' . $spaceReplacer . '+/', $spaceReplacer, $trimmed);
        $slug = strtolower($withoutRepeatingDashes);

        return $slug;
    }

    /**
     * Generates a unique slug for an entity class.
     * @param string $value
     * @param ObjectRepository $repository
     * @param int $entityId
     * Id of the entity object that the slug is assigned to.
     *
     * Used to prevent generating a new slug when an existing entity object is updated,
     * if the generated slug already belongs to that object.
     * @param string $appendWith
     * Specifies what string the unique slug should end with, followed by a counter.
     *
     * Defaults to "-".
     * @return string
     */
    public function generateUniqueSlug(string $value,
                                       ObjectRepository $repository,
                                       int $entityId = null,
                                       string $appendWith = '-'): string
    {
        $slug = $this->generateSlug($value);
        $uniqueSlug = $slug;
        $i = 1;
        while ($this->checkIfSlugExists($uniqueSlug, $repository, $entityId)) {
            $uniqueSlug = $slug . $appendWith . $i;
            $i++;
        }

        return $uniqueSlug;
    }

    /**
     * Checks if a slug already exists for an entity class.
     * @param string $slug
     * @param ObjectRepository $repository
     * @param int $ignoreId
     * Ignores the specified entity id while checking if the slug exists.
     * @return bool
     */
    public function checkIfSlugExists(string $slug, ObjectRepository $repository, int $ignoreId = null): bool
    {
        $entity = $repository->findOneBy(['slug' => $slug]);
        if ($entity) {
            if ($ignoreId && $entity->getId() === $ignoreId) {
                return false;
            }
            return true;
        }

        return false;
    }
}
