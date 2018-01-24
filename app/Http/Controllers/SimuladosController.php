<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Contracts\ControllerInterface;
use App\Repositories\SimuladoRepository;
use Illuminate\Http\Request;

/**
 * Controller for operations on Simulado Model
 * 
*/
final class SimuladosController extends Controller implements ControllerInterface
{
	protected $repository;

    /**
     * Create a new controller instance.
     * 
     * @param App\Repositories\SimuladoRepository $repo Repository data of model Simulado
     * @return void
    */
    public function __construct(SimuladoRepository $repo)
    {
        $this->repository = $repo;
    }

    /**
     * Insert a new Simulado on Database
     *
     * @param Illuminate\Http\Request $request Request params from endpoint
     *
     * @return json Response
    */
    public function insert(Request $request)
    {
    	try {
            $this->repository->insertData($request);
            return response()->json(['message'=>'Simulado inserido com sucesso','code'=>1],200);
    	} catch ( \Exception $e ){
            return response()->json(['message'=>$e->getMessage(),'code'=>$e->getCode()],422);
    	}
    }

    /**
     * Update a Simulado row on Database
     *
     * @param Illuminate\Http\Request $request Request params from endpoint
     * @param string                  $code    Code for update
     *
     * @return json Response
    */
    public function update(Request $request, string $code)
    {
        try {
            $this->repository->updateData($request,$code);
            return response()->json(['message'=>'Simulado atualizado com sucesso','code'=>1],201);
        } catch ( \Exception $e ){
            return response()->json(['message'=>$e->getMessage(),'code'=>$e->getCode()],422);
        }
    }

    /**
     * Lista um simulado
     *
     * @param Illuminate\Http\Request $request Request params from endpoint
     * @param int                     $limit   Limite de linhas
     *
     * @return json Response
    */
    public function list(Request $request, int $limit, int $offset)
    {
        try {
            return response()->json($this->repository->buscarSimulado($request, $limit, $offset),200);
        } catch ( \Exception $e ){
            return response()->json(['message'=>$e->getMessage(),'code'=>$e->getCode()],422);
        }
    }
}
