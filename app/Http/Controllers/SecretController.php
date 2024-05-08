<?php

namespace App\Http\Controllers;

use App\Input\SecretInput;
use App\Repositories\SecretRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecretController extends Controller
{
    public function __construct(
        protected SecretRepository $secretRepository,
    ) {}

    public function generate(Request $request): JsonResponse
    {
        $validator = validator()->make($request->only(['secret', 'passphrase', 'ttl']), [
            'secret' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {
            $secret = $this->secretRepository->store(new SecretInput(
                secret: $request->secret,
                passphrase: $request->passphrase,
                ttl: $request->ttl,
            ));
        } catch (QueryException $exception) {
            return response()->json([
                'message' => 'Error on generating secret',
                'errors' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([
            'data' => $secret,
        ], 201);
    }

    public function burn(Request $request, string $secret): JsonResponse
    {
        try {
            $this->secretRepository->delete(new SecretInput(
                secretKey: $secret,
            ));
        } catch (QueryException $exception) {
            return response()->json([
                'message' => 'Error on deleting secret',
                'errors' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([], 204);
    }

    public function index(Request $request, string $secret): JsonResponse
    {
        try {
            $secretData = $this->secretRepository->get(new SecretInput(
                secretKey: $secret,
            ));
        } catch (QueryException $exception) {
            return response()->json([
                'message' => 'Error on getting secret',
                'errors' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([
            'data' => $secretData,
        ], 200);
    }
}
