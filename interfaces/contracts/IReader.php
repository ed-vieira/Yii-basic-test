<?php 

namespace app\interfaces\contracts;


interface IReader {

    public function view(int $id);

    public function findById(int $id);

    public function list(array $query);

    public function find(array $query);

    public function paginate(array $query, int $perPage = 10, int $page = 1, array $orderBy= []);


}