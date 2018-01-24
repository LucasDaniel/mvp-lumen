<?php

namespace App\Repositories;

use Illuminate\Http\Request;

/**
 * Abstract Repository functions for all Repositories at Project
 * 
*/
abstract class AbstractRepository
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;
    public    $messages;
    public    $rules;

    public function __construct()
    {
    	$this->messages = [
		    'required' =>  'O campo :attribute é obrigatório.',
		    'max'      =>  'O campo :attribute passou do limite de :max caracteres',
		    'min'      =>  'O campo :attribute deve ter o número mínimo de :min caracteres',
		    'email'    =>  'O campo :attribute deve possuir um endereço de e-mail válido',
		    'string'   =>  'O campo :attribute não é string',
		    'integer'  =>  'O campo :attribute não é inteiro'
		];
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findAll()
    {
        return $this->model->all();
    }

    public function create(Request $request)
    {
        return $this->model->create($this->verifyAndSetData($request));
    }

    public function update(Request $request, $id)
    {
    	$this->verifyCode($id);
        return $this->model->find($id)->update($this->verifyAndSetData($request));
    }

    public function firstOrCreate(Request $request)
    {
    	$this->verifyAndSetData($request);
        return $this->model->firstOrCreate($request->all());
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
    {
        $modelClone = clone $this->model;

        if (!empty($criteria) && count($criteria) >= 1) {
            foreach ($criteria as $c) {
                $modelClone = $modelClone->where($c[0], $c[1], $c[2]);
            }
        }
        if (!empty($orderBy) && count($orderBy) >= 1) {
            foreach ($orderBy as $key => $value) {
                $modelClone = $modelClone->orderBy(key($value), $value[key($value)]);
            }
        }

        if ($offset !== null && $offset >= 0 && $limit !== null && $limit >= 0) {
            $modelClone = $modelClone->skip($offset*$limit)->take($limit);
        }

        return $modelClone->get();
    }

    public function findOneBy(array $criteria)
    {
        return $this->findBy($criteria)->first();
    }

    // from Doctrine
    public function __call($method, $arguments)
    {
        if (substr($method, 0, 6) == 'findBy') {
            $by = substr($method, 6, strlen($method));
            $method = 'findBy';
        } else {
            if (substr($method, 0, 9) == 'findOneBy') {
                $by = substr($method, 9, strlen($method));
                $method = 'findOneBy';
            } else {
                throw new \Exception(
                    "Undefined method '$method'. The method name must start with " .
                    "either findBy or findOneBy!", -1
                );
            }
        }
        if (!isset($arguments[0])) {
            // we dont even want to allow null at this point, because we cannot (yet) transform it into IS NULL.
            throw new \Exception('You must have one argument', -1);
        }

        $fieldName = lcfirst($by);

        return $this->$method([$fieldName, '=', $arguments[0]]);
    }

    public function paginate($pages)
    {
        return $this->model->paginate($pages);
    }

    protected function verifyAndSetData(Request $request)
    {
    	$data = $request->all();
    	if (empty($data)){
    		throw new \Exception("Favor inserir os dados para validação",-2);
    	}
    	foreach ($data as $key => $value){
    		$data[$key] = addslashes(trim($value));
    	}
    	return $data;
    }

    protected function verifyCode(string $code)
    {
        if (empty($code) || intval($code) === 0 || empty($this->model->find($code))){
            throw new \Exception("Código inválido",-4);
        }
    }

    public static function validateData(Request $request, array $rules, array $messages)
    {   
        $validator = \Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails() === true) {
            $errorMessages = $validator->errors()->getMessages();
            foreach ($errorMessages as $key => $value){
                throw new \Exception($value[0],-3);
            }
        }
    }
}