<?php

namespace App\Services\AcademyService;

interface IAcademyService
{
    /**
     * Retrieve all academies.
     *
     * @return array
     */
    public function getAllAcademies(): array;

    /**
     * Get an academy by ID.
     *
     * @param int $id
     * @return array
     */
    public function getAcademyById(int $id): array;

    /**
     * Create a new academy.
     *
     * @param array $data
     * @return array
     */
    public function createAcademy(array $data): array;

    /**
     * Update an academy by ID.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateAcademy(int $id, array $data): array;

    /**
     * Delete an academy by ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteAcademy(int $id): array;
}
