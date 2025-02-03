<?php

namespace App\Services;

use App\Models\Academy;
use Exception;
use Illuminate\Support\Facades\Log;

class AcademyService
{
    /**
     * @return array
     */
    public function getAllAcademies(): array
    {
        try {
            $academies = Academy::all();

            return [
                'success' => true,
                'academies' => $academies->isEmpty() ? [] : $academies->toArray(),
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error retrieving academies: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while retrieving academies.',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Get academy by ID.
     *
     * @param int $id
     * @return array
     */
    public function getAcademyById(int $id): array
    {
        try {
            $academy = Academy::query()->find($id);

            if (!$academy) {
                return [
                    'success' => false,
                    'message' => 'Academy not found.',
                    'status' => 404,
                ];
            }

            return [
                'success' => true,
                'academy' => $academy,
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error retrieving academy by ID: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while retrieving the academy.',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Create a new academy.
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createAcademy(array $data): array
    {
        try {
            $academy = Academy::query()->create($data);

            return [
                'success' => true,
                'academy' => $academy,
                'status' => 201,
            ];
        } catch (Exception $e) {
            Log::error("Error creating academy: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to create academy.',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Update academy by ID.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateAcademy(int $id, array $data): array
    {
        try {
            $academy = Academy::query()->find($id);

            if (!$academy) {
                return [
                    'success' => false,
                    'message' => 'Academy not found.',
                    'status' => 404,
                ];
            }

            $academy->update($data);

            return [
                'success' => true,
                'academy' => $academy,
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error updating academy: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while updating the academy.',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Delete academy by ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteAcademy(int $id): array
    {
        try {
            $academy = Academy::query()->find($id);

            if (!$academy) {
                return [
                    'success' => false,
                    'message' => 'Academy not found.',
                    'status' => 404,
                ];
            }

            $academy->delete();

            return [
                'success' => true,
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error deleting academy: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while deleting the academy.',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
