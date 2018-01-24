<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Simulado;

/**
 * Repository data for Simulado Model
 * 
*/
final class SimuladoRepository extends AbstractRepository implements RepositoryInterface
{
    const DEFAULT_ORDER = [['banca'=>'asc']];

	public function __construct(Simulado $model)
	{
		$this->model = $model;
        $this->rules = ['banca'=>'required|min:3|string'];
        parent::__construct();
	}

    /**
     * Insert a new data on Model
     * @param Illuminate\Http\Request $request Request params from route
     * @return mixed true or Exception
    */
	public function insertData(Request $request)
	{
        self::validateData($request, $this->rules, $this->messages);
		$this->create($request);
        return true;
	}

    /**
     * Updates a data on Model
     * @param Illuminate\Http\Request $request Request params from route
     * @return mixed true or Exception
    */
    public function updateData(Request $request, string $code)
    {
        self::validateData($request, $this->rules, $this->messages);
        $operation = $this->update($request, $code);
        return $this->model->{$this->model->primaryKey};
    }

    /**
     * Find a simulado
     * @param Illuminate\Http\Request $request Request params from route
     * @param $limit Limit of rows
     * @param $limit Offset of page
     * @return mixed true or Exception
    */
    public function buscarSimulado(Request $request, int $limit, int $offset)
    {
        $get_all   = $request->all();
        $order     = (isset($get_all['order']) ? [['order'] => $get_all['type_order']] : self::DEFAULT_ORDER);
        return $this->findBy($get_all, $order, $limit, $offset)->toArray();
    }
}