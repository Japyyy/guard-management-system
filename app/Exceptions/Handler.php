public function register(): void
{
    $this->renderable(function (\Throwable $e, $request) {
        if ($request->expectsJson()) {
            $status = 500;

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $status = 422;
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], $status);
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $status);
        }
    });
}