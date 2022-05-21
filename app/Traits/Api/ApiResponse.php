<?php
namespace App\Traits\Api;

use App\MyConfig\Globals;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{

    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }   

    protected function errorResponse(int $code = 400, $data = [])
    {
        $structure = Globals::JSON_RESPONSE;
        $structure['result']['state'] = 'ERROR';
        $structure['result']['data'] = $data;
        $structure['code'] = $code;
        
        return response()->json($structure ,200);
    }

    protected function showAll(int $code = 200, Collection $collection)
    {        
        $structure = Globals::JSON_RESPONSE;
        $structure['result']['state'] = 'OK';
        $structure['result']['data'] = $collection;
        $structure['code'] = $code;

        return $this->successResponse($structure, $code);
    }
    
    protected function showOne(int $code = 200, Model $model)
    {
        $structure = Globals::JSON_RESPONSE;
        $structure['result']['state'] = 'OK';
        $structure['result']['data'] = $model;
        $structure['code'] = $code;
        return $this->successResponse($structure, $code);
    }

    protected function showPagination(int $code = 200, Collection $collection)
    {        
        $paginated = $this->pagination($collection);
        $structure = Globals::JSON_RESPONSE;
        $structure['result']['state'] = 'OK';
        $structure['result']['data'] = $paginated;
        $structure['code'] = $code;

        return $this->successResponse($structure, $code);
    }

    private function pagination(Collection $collection) : LengthAwarePaginator
    {
        $rules = [
            'per_page' => ['integer', 'min:2', 'max:200']
        ];
    
        request()->validate($rules);
    
        $page = LengthAwarePaginator::resolveCurrentPage();
    
        $perPage = 2;
        if(request()->has('per_page')){
            $perPage = (int) request()->per_page;
        }
    
        $results = $collection->slice( ($page-1) * $perPage, $perPage )->values();
    
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
        
        // Agregar la lista de parametros para ser ordenados porque LengthAwarePaginator elimina todos los parametros por defecto
        $paginated->appends( request()->all() );
        
        return $paginated;
    }

}