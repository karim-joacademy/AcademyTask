<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademyRequests\StoreAcademyRequest;
use App\Http\Requests\AcademyRequests\UpdateAcademyRequest;
use App\Services\AcademyService;
use Exception;
use Illuminate\Http\JsonResponse;

class AcademyController extends Controller
{
    protected AcademyService $academyService;

    public function __construct(AcademyService $academyService)
    {
        $this->academyService = $academyService;
    }

    /**
     * Get all academies.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = $this->academyService->getAllAcademies();

        if (!$result['success']) {
            return response()->json([
                'message' => 'No Academies were found.',
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json($result['academies'], 200);
    }

    /**
     * Store a new academy.
     *
     * @param StoreAcademyRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(StoreAcademyRequest $request): JsonResponse
    {
        $result = $this->academyService->createAcademy($request->validated());

        if (!$result['success']) {
            return response()->json([
                'message' => 'Failed to create academy.',
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json($result['academy'], 201);
    }

    /**
     * Get academy by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->academyService->getAcademyById($id);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json($result['academy'], 200);
    }

    /**
     * Update academy by ID.
     *
     * @param UpdateAcademyRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateAcademyRequest $request, int $id): JsonResponse
    {
        $result = $this->academyService->updateAcademy($id, $request->validated());

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json($result['academy'], 200);
    }

    /**
     * Delete academy by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->academyService->deleteAcademy($id);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json([
            'message' => 'Academy and all of its related fields are deleted successfully.',
        ], 200);
    }
}
