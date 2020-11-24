<?php
declare(strict_types=1);

namespace App\Repositories;

interface RepositoryInterface
{
    public function all(array $columns = ['*']);

    public function create(array $attributes);

    public function update($id, array $attributes = [], array $options = []);

    public function delete($id);

    public function get($id, array $columns = []);
}
