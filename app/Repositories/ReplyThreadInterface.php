<?php

namespace App\Repositories;

interface ReplyThreadInterface
{
    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data);

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function find($id);
}
