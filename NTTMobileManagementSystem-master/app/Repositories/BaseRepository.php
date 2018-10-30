<?php

namespace App\Repositories;

use App\Events\PreModelDelete;
use Prettus\Repository\Eloquent\BaseRepository as Base;

abstract class BaseRepository extends Base {
    /**
     * @return mixed
     */
    public function count() {
        $class = $this->model();

        return $class::count();
    }

    /**
     * @param array $where
     * @return mixed
     */
    public function whereCount(array $where) {
        return $this->findWhere($where)->count();
    }

    /**
     * @param array $where
     * @param array $with
     * @param $sort
     * @param $order
     * @param $skip
     * @param $take
     * @return mixed
     */
    public function loadTable(array $where, $with, $sort, $order, $skip, $take) {
        $class = $this->model();

        return (is_null($with)) ?
            $class::where($where)->orderBy($sort, $order)->skip($skip)->take($take)->get() :
            $class::where($where)->with($with)->orderBy($sort, $order)->skip($skip)->take($take)->get();
    }

    /**
     * @param $sort
     * @param $order
     * @param $skip
     * @param $take
     * @return mixed
     */
    public function loadTable1($with, $sort, $order, $skip, $take) {
        $class = $this->model();

        return (is_null($with)) ?
            $class::orderBy($sort, $order)->skip($skip)->take($take)->get() :
            $class::with($with)->orderBy($sort, $order)->skip($skip)->take($take)->get();
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteWithPreNotify($id) {
        event(new PreModelDelete($this->find($id)));

        return $this->delete($id);
    }
}
