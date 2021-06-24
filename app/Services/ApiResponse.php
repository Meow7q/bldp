<?php


namespace App\Services;


trait ApiResponse
{
    /**
     * @param string $message
     * @param int $status_code
     * @param int $code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function message(string $message, int $status_code = 200, $code = false)
    {
        $data = [
            'status' => 1,
            'message' => $message,
        ];
        if($code){
            $data = [
                'code'=> $code,
                'status' => 1,
                'message' => $message,
            ];
        }
        return response($data, $status_code);
    }


    /**
     * @param string $message
     * @param int $status_code
     * @param int $code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function fail(string $message,  int $status_code = 400, $code = false)
    {
        $data = [
            'status' => 0,
            'message' => $message,
        ];
        if($code){
            $data = [
                'code'=> $code,
                'status' => 0,
                'message' => $message,
            ];
        }
        return response($data, $status_code);
    }

    /**
     * @param array $data
     * @param int $status_code
     * @param false $code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function success(array $data, int $status_code = 200, $code = false)
    {
        $data = [
            'status' => 1,
            'data' => $data,
        ];
        if($code){
            $data = [
                'code'=> $code,
                'status' => 1,
                'data' => $data,
            ];
        }
        return response($data, $status_code);
    }


    /**
     * @param array $data
     * @param int $status_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $data, int $status_code)
    {
        // 客户端设备唯一ID
        $client_hash = request()->header('X-Client-Hash');

        if (is_null($client_hash) || empty($client_hash)) {
            $client_hash = session()->getId();
        }

        return response()->json($data)->withHeaders([
            'X-Client-Hash' => $client_hash
        ])->setStatusCode($status_code);
    }
}
