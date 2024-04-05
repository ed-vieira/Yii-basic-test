<?php 

namespace app\interfaces\classes\service;

use app\interfaces\contracts\IRepository;

abstract class Service {

    protected $repository;

    public function __construct(IRepository $repository) {
        $this->repository = $repository;
    }

    public function store(array $data) {
       return $this->repository->store($data);
    }

    public function update(int $id, array $data) {
        return $this->repository->update($id, $data);
    }

    public function view(int $id) {
        return $this->repository->view($id);
    }

    public function findById(int $id) {
        return $this->repository->findById($id);
    }

    public function list(array $query) {
        return $this->repository->list($query);
    }

    public function find(array $query) {
        return $this->repository->find($query);
    }


    public function getErrors(): array {
        return $this->repository->getErrors();
    }

    public function destroy(int $id) {
        return $this->repository->destroy($id);
    }

    public function paginate(array $query, int $perPage = 10, int $page = 1, array $orderBy= []) {
        return $this->repository->paginate($query, $perPage, $page, $orderBy);
    }

    public function getErrorMessages(): string {
        $message = '';
        foreach ($this->getErrors() as $k => $errors) {
            foreach($errors as $error) {
                $message.="$error ";
            }
        }
        return $message;
    }

}